<?php

namespace App\Jobs;

use App\Models\Membership;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class HandleWebhookJob extends ProcessWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $payload;

    public function handle()
    {
        $this->payload = $this->webhookCall['payload'] ?? [];

        $method = $this->payload['type'] ?? '';

        Log::channel('queue')->info('HandleWebhookJob started for type: '.$method);

        if (! in_array($method, ['CustomerUpdate', 'CustomerNew', 'MembershipUpdate', 'MembershipNew'])) {
            throw new \BadMethodCallException("Method [$method] does not exist.");
        }

        $this->{lcfirst($method)}();

        Log::channel('queue')->info('HandleWebhookJob completed for type: '.$method);
    }

    // https://bossnl-production-2.mendixcloud.com/rest-doc/sportivity-api#/CustomersUpdate/get_CustomersUpdate
    public function customerUpdate()
    {
        Log::channel('queue')->info('Start call Sportivity::CustomersUpdate');
        $response = Http::sportivity()->get('CustomersUpdate');
        $data = json_decode($response, true);
        Log::channel('queue')->debug(print_r($data, true));

        if ($data['GetCustomersUpdate'] == ['NoUpdates']) {
            Log::channel('queue')->info('There were no updates');

            return;
        }

        foreach ($data['GetCustomersUpdate'] ?? [] as $customer) {
            $this->createOrUpdateUser($customer);
        }
    }

    // https://bossnl-production-2.mendixcloud.com/rest-doc/sportivity-api#/Customers/get_Customers__cid_
    public function customerNew()
    {
        if (empty($this->payload['CustomerId'])) {
            Log::channel('queue')->error('CustomerId is empty, skipping');

            return;
        }

        $customer_id = $this->payload['CustomerId'];

        Log::channel('queue')->info('Start call Sportivity::Customers'.$customer_id.'?Mem=true');
        $response = Http::sportivity()->get('Customers/'.$customer_id.'?Mem=true');
        $customer = json_decode($response, true);
        Log::channel('queue')->debug(print_r($customer, true));

        $membershipsData = $customer['CustomerMemberships'] ?? [];
        unset($customer['CustomerMemberships']);

        $user = $this->createOrUpdateUser($customer);

        if (empty($user)) {
            Log::channel('queue')->error('createOrUpdateUser failed, skipped membership updates.');

            return;
        }

        foreach ($membershipsData as $membership) {
            $membership_id = $membership['MembershipID'] ?? $membership['MembershipId'] ?? '';
            $this->createOrUpdateMembership($membership_id, $user);
        }

        Log::channel('queue')->info('Related membershipdata has been saved.');
    }

    // https://bossnl-production-2.mendixcloud.com/rest-doc/sportivity-api#/MembershipsUpdate/get_MembershipsUpdate
    public function membershipUpdate()
    {
        Log::channel('queue')->info('Start call Sportivity::MembershipsUpdate');
        $response = Http::sportivity()->get('MembershipsUpdate');
        $data = json_decode($response, true);
        Log::channel('queue')->debug(print_r($data, true));

        if ($data['GetMembershipsUpdate'] == ['NoUpdates']) {
            Log::channel('queue')->info('There were no updates');

            return;
        }

        foreach ($data['GetMembershipsUpdate'] ?? [] as $membershipData) {
            $membership_id = $membershipData['MembershipID'] ?? $membershipData['MembershipId'] ?? '';
            if (empty($membership_id)) {
                Log::channel('queue')->error('MembershipID is empty, skipping (1)');

                continue;
            }

            $this->createOrUpdateMembership($membership_id);
        }
    }

    // https://bossnl-production-2.mendixcloud.com/rest-doc/sportivity-api#/Memberships/get_Memberships__mid_
    public function membershipNew()
    {
        $membership_id = $this->payload['MembershipID'] ?? $this->payload['MembershipId'] ?? null;
        if (empty($membership_id)) {
            Log::channel('queue')->error('MembershipID is empty, skipping (2)');

            return;
        }

        $this->createOrUpdateMembership($membership_id);
    }

    public function createOrUpdateUser($data)
    {
        if (empty($data['CustomerId'])) {
            Log::channel('queue')->error('CustomerId was empty, skipping');

            return null;
        }

        Log::channel('queue')->info('Looking up user by customer id '.$data['CustomerId']);
        $user = User::where('sportivity_customer_id', $data['CustomerId'])->first();

        if (empty($user)) {
            if (empty($data['Email'])) {
                Log::channel('queue')->error('Email was empty, skipping');

                return false;
            }

            $user = new User;

            $user->sportivity_customer_id = $data['CustomerId'];
            $user->sportivity_customer_data = $data;

            $password = $data['BirthDate'] ?? Str::random(8);
            $password = str_replace('/', '-', $password);
            $user->password = Hash::make($password);

            Log::channel('queue')->alert("User not found, created new with password '".$password."'");
        } else {
            if (! empty($user->sportivity_customer_data) && is_array($user->sportivity_customer_data)) {
                $data = array_merge($user->sportivity_customer_data, $data);
            }
            $user->sportivity_customer_data = $data;
        }

        if (! empty($data['Email'])) {
            $user->email = $data['Email'];
        }

        if (! empty($data['FirstName']) || ! empty($data['MiddleName']) || ! empty($data['LastName'])) {
            $user->name = implode(' ', array_filter([
                $data['FirstName'] ?? '',
                $data['MiddleName'] ?? '',
                $data['LastName'] ?? '',
            ]));
        }

        try {
            $user->save();

            Log::channel('queue')->info('User with id '.$user->id.' has been saved.');

            return $user;
        } catch (\Exception $e) {
            Log::channel('queue')->error('Saving failed: '.$e->getMessage());
            @mail('johanmontenij@gmail.com', 'Ri-Jo customersUpdate Error', $e->getMessage());
        }
    }

    public function createOrUpdateMembership($membership_id, ?User $user = null)
    {
        Log::channel('queue')->info('Start call Sportivity::Memberships/'.$membership_id);
        $response = Http::sportivity()->get('Memberships/'.$membership_id);
        $data = json_decode($response, true);
        Log::channel('queue')->debug(print_r($data, true));

        if (empty($data['CustomerId'])) {
            Log::channel('queue')->error('CustomerId empty, skipping');

            return null;
        }

        if (empty($user)) {
            $user = User::where('sportivity_customer_id', $data['CustomerId'])->first();
        }

        if (empty($user)) {
            Log::alert('No user found with sportivity_customer_id '.$data['CustomerId'].
                       ', trying again with customerNew()');

            $this->payload['CustomerId'] = $data['CustomerId'];
            $this->customerNew();

            return;
        }

        $membership = Membership::where('sportivity_membership_id', $membership_id)->first();
        if (! empty($membership)) {
            $membership->data = $data;

            try {
                $membership->save();
            } catch (\Exception $e) {
                Log::channel('queue')->error('Saving failed: '.$e->getMessage());
                @mail('johanmontenij@gmail.com', 'Ri-Jo membershipNew Error', $e->getMessage());
            }

            Log::channel('queue')
                ->info('Membership ('.$membership_id.') for user '.$user->id.' has been updated.');

            return;
        }

        try {
            $user->memberships()->create([
                'sportivity_membership_id' => $membership_id,
                'data' => $data,
            ]);

            Log::channel('queue')->info('New membership ('.$membership_id.') for user '.$user->id.' has been added.');
        } catch (\Exception $e) {
            Log::channel('queue')->error('Saving failed: '.$e->getMessage());
            @mail('johanmontenij@gmail.com', 'Ri-Jo membershipNew Error', $e->getMessage());
        }
    }
}

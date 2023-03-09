<?php

namespace App\Console\Commands;

use App\Models\Membership;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SportivityUpdateCustomerData extends Command
{
    protected array $payload;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sportivity:update-customer {customer_id : The Sportivity customer id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update customer Sportivity-data for a single user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $customer_id = $this->argument('customer_id');

        $this->info('Start call Sportivity::Customers'.$customer_id.'?Mem=true');

        $response = Http::sportivity()->get('Customers/'.$customer_id.'?Mem=true');
        $customer = json_decode($response, true);

//        $this->info(print_r($customer, true));

        $membershipsData = $customer['CustomerMemberships'] ?? [];
        unset($customer['CustomerMemberships']);

        $user = $this->createOrUpdateUser($customer);

        if (empty($user)) {
            $this->error('createOrUpdateUser failed, skipped membership updates.');

            return Command::FAILURE;
        }

        foreach ($membershipsData as $membership) {
            $membership_id = $membership['MembershipID'] ?? $membership['MembershipId'] ?? '';
            $this->createOrUpdateMembership($membership_id, $user);
        }

        return Command::SUCCESS;
    }

    public function createOrUpdateUser($data)
    {
        if (empty($data['CustomerId'])) {
            $this->error('CustomerId was empty, skipping');

            return null;
        }

        $this->info('Looking up user by customer id '.$data['CustomerId']);
        $user = User::where('sportivity_customer_id', $data['CustomerId'])->first();

        if (empty($user)) {
            if (empty($data['Email'])) {
                $this->error('Email was empty, skipping');

                return false;
            }

            $user = new User;

            $user->sportivity_customer_id = $data['CustomerId'];
            $user->sportivity_customer_data = $data;

            $password = $data['BirthDate'] ?? Str::random(8);
            $password = str_replace('/', '-', $password);
            $user->password = Hash::make($password);

            $this->warn("User not found, created new with password '".$password."'");
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

            $this->info('User with id '.$user->id.' has been saved.');

            return $user;
        } catch (\Exception $e) {
            $this->error('Saving failed: '.$e->getMessage());
            @mail('johanmontenij@gmail.com', 'Ri-Jo customersUpdate Error', $e->getMessage());
        }
    }

    public function createOrUpdateMembership($membership_id, ?User $user = null)
    {
        $this->info('Start call Sportivity::Memberships/'.$membership_id);
        $response = Http::sportivity()->get('Memberships/'.$membership_id);
        $data = json_decode($response, true);
//        $this->info(print_r($data, true));

        if (empty($data['CustomerId'])) {
            $this->error('CustomerId empty, skipping');

            return null;
        }

        if (empty($user)) {
            $user = User::where('sportivity_customer_id', $data['CustomerId'])->first();
        }

        if (empty($user)) {
            $this->warn('No user found with sportivity_customer_id '.$data['CustomerId'].
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
                $this->error('Saving failed: '.$e->getMessage());
                @mail('johanmontenij@gmail.com', 'Ri-Jo membershipNew Error', $e->getMessage());
            }

            $this
                ->info('Membership ('.$membership_id.') for user '.$user->id.' has been updated.');

            return;
        }

        try {
            $user->memberships()->create([
                'sportivity_membership_id' => $membership_id,
                'data' => $data,
            ]);

            $this->info('New membership ('.$membership_id.') for user '.$user->id.' has been added.');
        } catch (\Exception $e) {
            $this->error('Saving failed: '.$e->getMessage());
            @mail('johanmontenij@gmail.com', 'Ri-Jo membershipNew Error', $e->getMessage());
        }
    }

    public function customerNew()
    {
        if (empty($this->payload['CustomerId'])) {
            $this->error('CustomerId is empty, skipping');

            return;
        }

        $customer_id = $this->payload['CustomerId'];

        $this->info('Start call Sportivity::Customers'.$customer_id.'?Mem=true');
        $response = Http::sportivity()->get('Customers/'.$customer_id.'?Mem=true');
        $customer = json_decode($response, true);
        $this->info(print_r($customer, true));

        $membershipsData = $customer['CustomerMemberships'] ?? [];
        unset($customer['CustomerMemberships']);

        $user = $this->createOrUpdateUser($customer);

        if (empty($user)) {
            $this->error('createOrUpdateUser failed, skipped membership updates.');

            return;
        }

        foreach ($membershipsData as $membership) {
            $membership_id = $membership['MembershipID'] ?? $membership['MembershipId'] ?? '';
            $this->createOrUpdateMembership($membership_id, $user);
        }

        $this->info('Related membershipdata has been saved.');
    }
}

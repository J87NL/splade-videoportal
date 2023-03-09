<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', [
            'users' => SpladeTable::for(User::class)
                ->defaultSort('name')
                ->withGlobalSearch(columns: ['name', 'email', 'sportivity_customer_id'])
                ->selectFilter(key: 'is_admin', options: [1 => __('Yes'), 0 => __('No')], label: __('Is admin'))
                ->column(key: 'name', label: __('Name'), canBeHidden: false, sortable: true)
                ->column(key: 'email', label: __('Email'), canBeHidden: true, sortable: true)
                ->column(key: 'sportivity_customer_id', label: __('Customernumber'), canBeHidden: true, sortable: true)
                ->column(key: 'is_admin', label: __('Is admin'), canBeHidden: true, sortable: true)
                ->column('action', label: __('Options'), canBeHidden: false)
                ->rowModal(fn (User $user) => route('admin.users.edit', ['user' => $user]))
                ->paginate(25),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $user->memberships()->create(['data' => ['MembershipActive' => true]]);

        event(new Registered($user));

        return redirect(route('admin.users.index'));
    }

    public function edit(User $user): View
    {
        $user->load('memberships');

        return view('admin.users.edit', [
            'user' => $user,
        ]);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->validated();

        $newPassword = $data['password'];
        unset($data['password']);

        if (! empty($newPassword)) {
            $data['password'] = Hash::make($newPassword);
        }

        $user->update($data);

        Toast::title(__('Saved successfully'))
            ->message(__(":Type ':title' has been updated", ['type' => __('user'), 'title' => $user->name]));

        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user)
    {
        $title = $user->name;

        $user->delete();

        Toast::title(__('Removed successfully'))
            ->message(__(":Type ':title' has been removed", ['type' => __('user'), 'title' => $title]));

        return redirect()->route('admin.users.index');
    }
}

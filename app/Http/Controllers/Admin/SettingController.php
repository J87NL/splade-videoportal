<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index', [
            'settings' => SpladeTable::for(Setting::class)
                ->defaultSort('key')
                ->withGlobalSearch(columns: ['key', 'value'])
                ->column(key: 'key', label: __('Key'), canBeHidden: false, sortable: true)
                ->column(key: 'value', label: __('Value'), canBeHidden: false, sortable: true)
                ->column(key: 'action', label: __('Options'), canBeHidden: false)
                ->rowModal(fn (Setting $setting) => route('admin.settings.edit', ['setting' => $setting]))
                ->paginate(25),
        ]);
    }

    public function create(): View
    {
        return view('admin.settings.create');
    }

    public function store(SettingRequest $request)
    {
        $setting = Setting::create($request->validated());

        Toast::title(__('Saved successfully'))
            ->message(__("New :type ':title' successfully added", ['type' => __('Setting'), 'title' => $setting->key]));

        return redirect()->route('admin.settings.index');
    }

    public function edit(Setting $setting)
    {
        return view('admin.settings.edit', [
            'setting' => $setting,
        ]);
    }

    public function update(SettingRequest $request, Setting $setting)
    {
        $setting->update($request->validated());

        // Clear cache for @setting()-directive
        Cache::forget('setting-'.$setting->key);
        Artisan::call('view:clear');

        Toast::title(__('Saved successfully'))
            ->message(__(":Type ':title' has been updated", ['type' => __('Setting'), 'title' => $setting->key]));

        return redirect()->route('admin.settings.index');
    }

    public function destroy(Setting $setting)
    {
        $title = $setting->key;

        $setting->delete();

        Toast::title(__('Removed successfully'))
            ->message(__(":Type ':title' has been removed", ['type' => __('Setting'), 'title' => $title]));

        return redirect()->route('admin.settings.index');
    }
}

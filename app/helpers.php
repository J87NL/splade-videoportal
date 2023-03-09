<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (! function_exists('setting')) {
    function setting($expression = '')
    {
        $setting = Cache::rememberForever('setting-'.$expression, function () use ($expression) {
            return Setting::select('value')->where('key', $expression)->first();
        });

        if (empty($setting)) {
            return '';
        }

        return $setting->value;
    }
}

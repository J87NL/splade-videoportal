<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use ProtoneMedia\Splade\Facades\Splade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! defined('STDIN')) {
            define('STDIN', fopen('php://stdin', 'r'));
        }

        Blade::directive('setting', function ($expression) {
            $expression = Str::of($expression)->trim("'")->trim('"');

            return setting($expression);
        });

        Http::macro('sportivity', function () {
            return Http::withHeaders([
                'X-API-TOKEN' => config('sportivity.api.token'),
            ])->baseUrl(config('sportivity.api.url'));
        });

        Model::shouldBeStrict();

        Splade::defaultToast(function ($toast) {
            $toast->autoDismiss(5);
        });
    }
}

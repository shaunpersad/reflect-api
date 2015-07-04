<?php namespace App\Providers;

use App\Services\Api\V1\Api;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Symfony\Component\Translation\TranslatorInterface', function ($app) {
            return $app['translator'];
        });

        $this->app->singleton('App\Contracts\ApiContract', function($app) {

            return new Api(
                $app['Illuminate\Contracts\Auth\Guard'],
                $app['Illuminate\Validation\Factory'],
                $app['Illuminate\Contracts\Events\Dispatcher'],
                $app['Illuminate\Contracts\Cache\Repository'],
                $app['log']
            );
        });
    }
}

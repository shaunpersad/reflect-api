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
        $this->app->bind('Illuminate\Contracts\Mail\Mailer', function($app) {

            config(['services' => [

                'mailgun' => [
                    'domain' => env('MAILGUN_DOMAIN', ''),
                    'secret' => env('MAILGUN_SECRET', '')
                ]
            ]]);

            return $app['mailer'];
        });

        $this->app->singleton('App\Contracts\ApiContract', function($app) {

            return new Api(
                $app['Illuminate\Contracts\Auth\Guard'],
                $app['validator'],
                $app['Illuminate\Contracts\Events\Dispatcher'],
                $app['Illuminate\Contracts\Cache\Repository']
            );
        });
    }
}

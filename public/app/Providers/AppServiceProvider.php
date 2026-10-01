<?php

namespace App\Providers;

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
        $this->app->scoped(\App\Services\ContactDetails::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Support\Facades\View::composer(['partials.footer', 'pages.contact'], function ($view) {
            $journal = $view->getData()['journal'] ?? null;
            $view->with('contactDetails', app(\App\Services\ContactDetails::class)->resolve((int) data_get($journal, 'j_id', 0)));
        });
    }
}

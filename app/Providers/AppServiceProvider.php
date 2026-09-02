<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('leads.partials.create_modal', function ($view) {
            $staff = \App\Models\User::whereIn('role', ['admin', 'sales'])->get();
            $allServices = \App\Models\Lead::whereNotNull('services')->pluck('services')->flatten()->unique()->filter()->values()->all();
            if (empty($allServices)) {
                $allServices = ['ISO 9001', 'ISO 14001', 'ISO 45001', 'ISO 27001', 'CE Marking', 'BIS Certification', 'FSSAI', 'GMP', 'Hallmark', 'GST Registration'];
            }
            $view->with(compact('staff', 'allServices'));
        });
    }
}

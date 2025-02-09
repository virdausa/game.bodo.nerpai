<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Contoh penggunaan Gate untuk authorization
        Gate::before(function ($user, $ability) {
            // Cek apakah sedang dalam mode perusahaan
            if (session()->has('company_id')) {
                $companyUser = auth()->user()->companyUser();
                $employee = $companyUser->employee(); // Pastikan ini mengambil Employee
    
                if ($employee && $employee->can($ability, 'company')) {
                    return true;
                }
            }
        });
    }
}

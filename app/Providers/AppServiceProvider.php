<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Relations\Relation;

use App\Models\Company\Supplier;
use App\Models\Company\Warehouse;
use App\Models\Company\Customer;
use App\Models\Company\Purchase;
use App\Models\Company\Sale;


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
    public function boot()
	{
        URL::forceScheme('http');

		if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        // morph
        Relation::morphMap([
            'SUP' => Supplier::class,
            'WH' => Warehouse::class,
            'CUST' => Customer::class,
            'PO' => Purchase::class,
            'SO' => Sale::class,
        ]);
	}
}

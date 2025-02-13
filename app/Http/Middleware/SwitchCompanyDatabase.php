<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Company;

class SwitchCompanyDatabase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil perusahaan yang dipilih dari sesi
        $companyId = Session::get('company_id');

        if ($companyId) {
            // Cari perusahaan di database utama
            $company = Company::find($companyId);

            if ($company) {
                // Set koneksi database sesuai perusahaan yang dipilih
                Config::set('database.connections.tenant', [
                    'driver'    => 'mysql',
                    'host'      => env('DB_HOST', '127.0.0.1'),
                    'port'      => env('DB_PORT', '3306'),
                    'database'  => 'nerpai_'.$company->id,
                    'username'  => env('DB_USERNAME', 'root'),
                    'password'  => env('DB_PASSWORD', ''),
                    'charset'   => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix'    => '',
                    'strict'    => true,
                    'engine'    => null,
                ]);

                // Ubah koneksi default untuk sesi ini
                DB::purge('tenant');
                DB::setDefaultConnection('tenant');
            }
        } else {
            // Jika tidak ada perusahaan dipilih, hapus koneksi database sesi
            Config::set('database.connections.tenant', []);
        }

        return $next($request);
    }
}

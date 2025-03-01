<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

use App\Models\Store\StoreEmployee;

class StoreLoginAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $store_id = session('company_store_id');

        if(!$store_id){
            return redirect()->route('stores.index')->with('error', 'Anda belum memilih store !');
        }
        
        $employee = session('employee');

        $store_employee = StoreEmployee::where('store_id', $store_id)
                                        ->where('employee_id', $employee->id)
                                        ->where('status', 'active')
                                        ->first();

        if (!$store_employee) {
            return redirect()->route('stores.index')->with('error', 'Anda tidak memiliki akses ke store ini');
        }

        Session::put('company_store_employee_id', $store_employee->id);

        return $next($request);
    }
}

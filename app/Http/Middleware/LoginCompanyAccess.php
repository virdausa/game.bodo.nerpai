<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\CompanyUser;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class LoginCompanyAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $companyId = session('company_id');

        if(!$companyId){
            return redirect()->route('companies.index')->with('error', 'Anda belum memilih perusahaan');
        }

        $isAuthorized = CompanyUser::where('user_id', $user->id)
                        ->where('status', 'approved')
                        ->exists();

        if (!$isAuthorized) {
            // Hapus session company
            session()->forget('company_id');  
            session()->forget('company_name');
            session()->forget('company_database_url');  

            return redirect()->route('companies.index')->with('error', 'Anda tidak memiliki akses ke perusahaan ini');
        }

        $companyUser = CompanyUser::where('user_id', $user->id)->first();
        Session::put('companyUser_id', $companyUser->id);
        if($companyUser->employee()->exists()){
            Session::put('employee', $companyUser->employee);
        }

        return $next($request);
    }
}

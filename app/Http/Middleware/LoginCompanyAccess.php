<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\CompanyUser;

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
            return redirect()->route('company.index')->with('error', 'Anda belum memilih perusahaan');
        }

        $isAuthorized = CompanyUser::where('user_id', $user->id)
                        ->where('status', 'approved')
                        ->exists();

        if (!$isAuthorized) {
            return redirect()->route('exit.company', 'companies')->with('error', 'Anda tidak memiliki akses ke perusahaan ini');
        }

        return $next($request);
    }
}

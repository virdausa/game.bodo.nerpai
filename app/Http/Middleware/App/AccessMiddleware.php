<?php

namespace App\Http\Middleware\App;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class AccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if(!$user) {
            return redirect()->route('login')->with('error', 'Please login first :)');
        }

        $route = $request->route();
        $first_uri = explode('/', $route->uri)[0];

        // check if its a permission
        $list_permission = session('list_permission');
        if(!$list_permission) {
            // add all permission to session
            $list_permission = Permission::all();
            session(['list_permission' => $list_permission]);
        }

        $permission = $list_permission->where('name', $route->uri)->first();

        if($permission) {
            if(!$user->can($permission->name)) {
                abort(403, "Unauthorized action to: {$route->uri}");
            }
        }

        return $next($request);
    }
}

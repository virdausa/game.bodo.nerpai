<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Pipeline\Pipeline;

use App\Http\Middleware\StoreLoginAccess;

class StoreMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $middlewareList = [
            StoreLoginAccess::class,
        ];

        return app(Pipeline::class)
            ->send($request)
            ->through($middlewareList)
            ->then(fn($request) => $next($request));

        return $next($request);
    }
}

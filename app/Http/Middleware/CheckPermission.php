<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permissionSlug
     * @return mixed
     */

    public function handle($request, Closure $next, $permissionSlug)
    {
        if (!Auth::user()->availablePermissions()->pluck('slug')->contains($permissionSlug)) {
            return redirect()->route('home')->with('custom_alert_type', 'info')->with('custom_alert_message', 'You do not have the required permission.');
        }

        return $next($request);
    }
     
}



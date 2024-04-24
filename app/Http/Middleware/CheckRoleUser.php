<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRoleUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $roles = func_get_args();
        array_shift($roles);
    
        if (Auth::check() && in_array(Auth::user()->level, $roles)) {
            return $next($request);
        }

        if (Auth::check()) {
            // Redirect based on the user's level
            switch (Auth::user()->level) {
                case 1:
                    return redirect('list_shipments');
                    break;
                case 2:
                    return redirect('list_shipments');
                    break;
                default:
                    return redirect('list_shipments');
            }
        }
    
        return redirect('list_shipments');
    }
}

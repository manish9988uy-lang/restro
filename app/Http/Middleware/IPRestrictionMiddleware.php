<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IPRestrictionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Example: Only allow IPs from a config or database
        $allowedIps = config('app.allowed_ips', []);
        
        if (!empty($allowedIps) && !in_array($request->ip(), $allowedIps)) {
            abort(403, 'Unauthorized IP address.');
        }

        return $next($request);
    }
}

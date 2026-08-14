<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BranchScopeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->branch_id && !$user->hasRole('super-admin')) {
            // For users assigned to a branch, we can use this in models/controllers
            // We'll set a request attribute so controllers know to scope queries
            $request->merge(['branch_id' => $user->branch_id]);
        }

        return $next($request);
    }
}

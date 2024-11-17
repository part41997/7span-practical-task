<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        /**Explode roles */
        $roels = explode('|', $role);

        // Check if the user is authenticated and has the required role
        if ($request->user() && in_array($request->user()->role->name, $roels)) {
            return $next($request);
        }

        // Return a 403 Forbidden response if the role does not match
        return response()->json(['message' => 'Forbidden'], 403);
    }
}

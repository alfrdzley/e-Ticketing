<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\Response;

class HandleAuthErrors
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request):Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (AuthenticationException $e) {
            // Handle authentication errors
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Authentication required.',
                    'error' => 'Unauthenticated'
                ], 401);
            }
            
            // Redirect to auth fallback page instead of login
            return redirect()->route('auth.fallback')->with('message', 'Please log in to access this resource.');
            
        } catch (AuthorizationException $e) {
            // Handle authorization errors (403)
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You do not have permission to access this resource.',
                    'error' => 'Forbidden'
                ], 403);
            }
            
            // Return custom 403 view
            return response()->view('errors.403', [], 403);
        }
    }
}

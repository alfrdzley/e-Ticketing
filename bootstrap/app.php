<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Exclude Midtrans notification from CSRF protection
        $middleware->validateCsrfTokens(except: [
            'payment/notification',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle authentication errors
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Authentication required.',
                    'error' => 'Unauthenticated',
                ], 401);
            }

            // Redirect to auth fallback page
            return redirect()->route('auth.fallback')
                ->with('message', 'Please log in to access this resource.');
        });

        // Handle authorization errors (403)
        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You do not have permission to access this resource.',
                    'error' => 'Forbidden',
                ], 403);
            }

            return response()->view('errors.403', ['exception' => $e], 403);
        });

        // Handle CSRF token mismatch (419)
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Session expired. Please refresh and try again.',
                    'error' => 'Session Expired',
                ], 419);
            }

            return response()->view('errors.419', ['exception' => $e], 419);
        });

        // Handle 404 errors
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'The requested resource was not found.',
                    'error' => 'Not Found',
                ], 404);
            }

            return response()->view('errors.404', ['exception' => $e], 404);
        });

        // Handle 500 errors
        $exceptions->render(function (\Throwable $e, $request) {
            // Only handle if it's a 500 error and not already handled
            if (! config('app.debug') && ! $request->expectsJson()) {
                $statusCode = 500;

                // Check if exception has status code method
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                    $statusCode = $e->getStatusCode();
                }

                if ($statusCode >= 500) {
                    return response()->view('errors.500', ['exception' => $e], $statusCode);
                }
            }

            return null; // Let Laravel handle other cases
        });
    })->create();

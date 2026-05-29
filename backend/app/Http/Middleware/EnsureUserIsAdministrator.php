<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensures that the authenticated user has administrator privileges.
 *
 * This middleware is applied to routes that manage profiles and user-profile
 * associations. It blocks authenticated users that do not have the
 * Administrador profile.
 */
class EnsureUserIsAdministrator
{
    /**
     * Handles an incoming request and validates administrator access.
     *
     * @param Request $request Current HTTP request.
     * @param Closure $next Next middleware/action in the request pipeline.
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdministrator()) {
            return response()->json([
                'message' => 'Acesso permitido apenas para administradores.',
            ], 403);
        }

        return $next($request);
    }
}

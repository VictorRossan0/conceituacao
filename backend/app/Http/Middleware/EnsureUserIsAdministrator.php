<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdministrator
{
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

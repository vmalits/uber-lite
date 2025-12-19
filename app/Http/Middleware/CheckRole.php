<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CheckRole
{
    /**
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if ($user === null) {
            return ApiResponse::error(message: 'Unauthenticated.', status: 401);
        }

        if ($user->role === null || $user->role->value !== $role) {
            return ApiResponse::error(message: 'Forbidden.', status: 403);
        }

        return $next($request);
    }
}

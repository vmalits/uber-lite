<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CheckProfileStep
{
    /**
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, string $step): Response
    {
        $user = $request->user();

        if ($user === null) {
            return ApiResponse::unauthorized();
        }

        if ($user->profile_step === null || $user->profile_step->value !== $step) {
            return ApiResponse::forbidden(message: 'Forbidden. Profile step not '.$step.'.');
        }

        return $next($request);
    }
}

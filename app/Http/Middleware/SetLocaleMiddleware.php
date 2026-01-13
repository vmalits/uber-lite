<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\LocaleNegotiator;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class SetLocaleMiddleware
{
    public function __construct(
        private LocaleNegotiator $negotiator,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $locale = $this->negotiator->negotiate(
            userLocale: $user?->locale,
            acceptLanguages: $request->getLanguages(),
        );

        app()->setLocale($locale);

        if ($user !== null && $user->locale === null) {
            $user->updateQuietly(['locale' => $locale]);
        }

        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('Content-Language', $locale);

        return $response;
    }
}

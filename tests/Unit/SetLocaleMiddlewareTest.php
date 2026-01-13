<?php

declare(strict_types=1);

use App\Http\Middleware\SetLocaleMiddleware;
use App\Models\User;
use App\Services\LocaleNegotiator;
use App\Services\LocaleService;
use Illuminate\Http\Request;

test('middleware uses user locale from database as highest priority', function () {
    $user = User::factory()->create(['locale' => 'ru']);
    $request = Request::create('/api/v1/example', 'GET');
    $request->setUserResolver(function () use ($user) {
        return $user;
    });

    $middleware = new SetLocaleMiddleware(app(LocaleNegotiator::class));

    $middleware->handle($request, function ($req) {
        expect(app()->getLocale())->toBe('ru');

        return response()->json([]);
    });

    $user->delete();
});

test('middleware falls back to accept-language when user has no locale', function () {
    $user = User::factory()->create(['locale' => null]);
    $request = Request::create('/api/v1/example', 'GET');
    $request->headers->set('Accept-Language', 'ru-RU,en;q=0.9');
    $request->setUserResolver(function () use ($user) {
        return $user;
    });

    $middleware = new SetLocaleMiddleware(app(LocaleNegotiator::class));

    $middleware->handle($request, function ($req) {
        expect(app()->getLocale())->toBe('ru');

        return response()->json([]);
    });

    $user->delete();
});

test('middleware falls back to default locale when no user locale and no accept-language', function () {
    $user = User::factory()->create(['locale' => null]);
    $request = Request::create('/api/v1/example', 'GET');
    $request->setUserResolver(function () use ($user) {
        return $user;
    });

    $middleware = new SetLocaleMiddleware(app(LocaleNegotiator::class));

    $middleware->handle($request, function ($req) {
        expect(app()->getLocale())->toBe(config('app.locale'));

        return response()->json([]);
    });

    $user->delete();
});

test('locale service works correctly', function () {
    $locale = app(LocaleService::class);

    expect($locale->current())->toBeString()
        ->and($locale->isValid('ru'))->toBeTrue()
        ->and($locale->isValid('de'))->toBeFalse()
        ->and($locale->supported())->toBeArray();
});

test('translation messages work', function () {
    app()->setLocale('ru');
    expect(trans('messages.auth.otp_sent'))->toBe('Код OTP успешно отправлен');

    app()->setLocale('en');
    expect(trans('messages.auth.otp_sent'))->toBe('OTP code sent successfully');

    app()->setLocale('ro');
    expect(trans('messages.auth.otp_sent'))->toBe('Codul OTP a fost trimis cu succes');
});

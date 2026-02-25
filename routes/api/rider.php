<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Ride\ShareRideController;
use App\Http\Controllers\Api\V1\Rider\AddFavoriteLocationController;
use App\Http\Controllers\Api\V1\Rider\AddFavoriteRouteController;
use App\Http\Controllers\Api\V1\Rider\ApplyPromoCodeController;
use App\Http\Controllers\Api\V1\Rider\ApplyReferralCodeController;
use App\Http\Controllers\Api\V1\Rider\CancelRideController;
use App\Http\Controllers\Api\V1\Rider\CreateRideController;
use App\Http\Controllers\Api\V1\Rider\DeleteFavoriteLocationController;
use App\Http\Controllers\Api\V1\Rider\DeleteFavoriteRouteController;
use App\Http\Controllers\Api\V1\Rider\GetActiveRideController;
use App\Http\Controllers\Api\V1\Rider\GetCreditsController;
use App\Http\Controllers\Api\V1\Rider\GetEstimateController;
use App\Http\Controllers\Api\V1\Rider\GetFareBreakdownController;
use App\Http\Controllers\Api\V1\Rider\GetFavoriteLocationController;
use App\Http\Controllers\Api\V1\Rider\GetFavoriteLocationsController;
use App\Http\Controllers\Api\V1\Rider\GetFavoriteRouteController;
use App\Http\Controllers\Api\V1\Rider\GetFavoriteRoutesController;
use App\Http\Controllers\Api\V1\Rider\GetRideController;
use App\Http\Controllers\Api\V1\Rider\GetRideHistoryController;
use App\Http\Controllers\Api\V1\Rider\GetRideStatsController;
use App\Http\Controllers\Api\V1\Rider\GetScheduledRidesController;
use App\Http\Controllers\Api\V1\Rider\ProfileController;
use App\Http\Controllers\Api\V1\Rider\RateRideController;
use App\Http\Controllers\Api\V1\Rider\RemovePromoCodeController;
use App\Http\Controllers\Api\V1\Rider\ScheduleRideController;
use App\Http\Controllers\Api\V1\Rider\SearchLocationsController;
use App\Http\Controllers\Api\V1\Rider\UpdateFavoriteRouteController;
use App\Http\Controllers\Api\V1\Rider\UpdateProfileController;
use App\Http\Controllers\Api\V1\Rider\UpdateRideNoteController;
use App\Http\Controllers\Api\V1\Rider\UpdateScheduledRideController;
use App\Http\Controllers\Api\V1\Rider\UploadAvatarController;
use Illuminate\Support\Facades\Route;

Route::prefix('rider')
    ->middleware([
        'auth:sanctum',
        'verified',
        'role:rider',
        'profile_step:completed',
        'set_locale',
    ])
    ->group(function (): void {
        Route::get('locations/search', [SearchLocationsController::class, '__invoke'])
            ->name('api.v1.rider.locations.search');
        Route::get('favorites', [GetFavoriteLocationsController::class, '__invoke'])
            ->name('api.v1.rider.favorites.index');
        Route::post('favorites', [AddFavoriteLocationController::class, '__invoke'])
            ->name('api.v1.rider.favorites.store');
        Route::get('routes/favorite', [GetFavoriteRoutesController::class, '__invoke'])
            ->name('api.v1.rider.routes.favorite.index');
        Route::post('routes/favorite', [AddFavoriteRouteController::class, '__invoke'])
            ->name('api.v1.rider.routes.favorite.store');
        Route::get('routes/favorite/{route}', [GetFavoriteRouteController::class, '__invoke'])
            ->name('api.v1.rider.routes.favorite.show');
        Route::put('routes/favorite/{route}', [UpdateFavoriteRouteController::class, '__invoke'])
            ->name('api.v1.rider.routes.favorite.update');
        Route::delete('routes/favorite/{route}', [DeleteFavoriteRouteController::class, '__invoke'])
            ->name('api.v1.rider.routes.favorite.destroy');
        Route::get('favorites/{favorite}', [GetFavoriteLocationController::class, '__invoke'])
            ->name('api.v1.rider.favorites.show');
        Route::delete('favorites/{favorite}', [DeleteFavoriteLocationController::class, '__invoke'])
            ->name('api.v1.rider.favorites.destroy');
        Route::get('profile', [ProfileController::class, '__invoke'])
            ->name('api.v1.rider.profile');
        Route::put('profile', [UpdateProfileController::class, '__invoke'])
            ->name('api.v1.rider.profile.update');
        Route::post('estimates', [GetEstimateController::class, '__invoke'])
            ->name('api.v1.rider.estimates');
        Route::post('rides', [CreateRideController::class, '__invoke'])
            ->name('api.v1.rider.rides');
        Route::get('rides/active', [GetActiveRideController::class, '__invoke'])
            ->name('api.v1.rider.rides.active');
        Route::get('rides/history', [GetRideHistoryController::class, '__invoke'])
            ->name('api.v1.rider.rides.history');
        Route::get('rides/scheduled', [GetScheduledRidesController::class, '__invoke'])
            ->name('api.v1.rider.rides.scheduled');
        Route::post('rides/scheduled', [ScheduleRideController::class, '__invoke'])
            ->name('api.v1.rider.rides.schedule');
        Route::put('rides/scheduled/{ride}', [UpdateScheduledRideController::class, '__invoke'])
            ->name('api.v1.rider.rides.scheduled.update');
        Route::get('rides/{ride}', [GetRideController::class, '__invoke'])
            ->name('api.v1.rider.rides.show');
        Route::get('rides/{ride}/fare-breakdown', [GetFareBreakdownController::class, '__invoke'])
            ->name('api.v1.rider.rides.fare-breakdown');
        Route::put('rides/{ride}/note', [UpdateRideNoteController::class, '__invoke'])
            ->name('api.v1.rider.rides.note');
        Route::post('rides/{ride}/cancel', [CancelRideController::class, '__invoke'])
            ->name('api.v1.rider.rides.cancel');
        Route::put('rides/{ride}/rating', [RateRideController::class, '__invoke'])
            ->name('api.v1.rider.rides.rating');
        Route::post('rides/{ride}/share', [ShareRideController::class, '__invoke'])
            ->name('api.v1.rider.rides.share');
        Route::get('stats', [GetRideStatsController::class, '__invoke'])
            ->name('api.v1.rider.stats');
        Route::get('credits', [GetCreditsController::class, '__invoke'])
            ->name('api.v1.rider.credits');
        Route::post('referrals/apply', [ApplyReferralCodeController::class, '__invoke'])
            ->name('api.v1.rider.referrals.apply');
        Route::post('rides/{ride}/promo-code', [ApplyPromoCodeController::class, '__invoke'])
            ->name('api.v1.rider.rides.promo-code.apply');
        Route::delete('rides/{ride}/promo-code', [RemovePromoCodeController::class, '__invoke'])
            ->name('api.v1.rider.rides.promo-code.remove');
        Route::post('avatar', [UploadAvatarController::class, '__invoke'])
            ->name('api.v1.rider.avatar');
    });

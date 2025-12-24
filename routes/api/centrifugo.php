<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Centrifugo\TokenController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'profile_step:completed'])->group(function (): void {
    Route::get('/ws/token', TokenController::class)->name('api.v1.ws.token');
});

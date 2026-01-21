<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Support\CreateTicketController;
use App\Http\Controllers\Api\V1\Support\GetTicketsController;
use App\Http\Controllers\Api\V1\Support\ShowTicketController;
use Illuminate\Support\Facades\Route;

Route::prefix('support')
    ->middleware(['auth:sanctum', 'set_locale'])
    ->group(function (): void {
        Route::get('tickets', [GetTicketsController::class, '__invoke'])
            ->name('api.v1.support.tickets.index');
        Route::get('tickets/{ticket}', [ShowTicketController::class, '__invoke'])
            ->name('api.v1.support.tickets.show')
            ->whereUlid('ticket');
        Route::post('tickets', [CreateTicketController::class, '__invoke'])
            ->name('api.v1.support.tickets.store');
    });

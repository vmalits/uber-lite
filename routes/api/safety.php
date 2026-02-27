<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Safety\AddEmergencyContactController;
use App\Http\Controllers\Api\V1\Safety\DeleteEmergencyContactController;
use App\Http\Controllers\Api\V1\Safety\GetEmergencyContactsController;
use App\Http\Controllers\Api\V1\Safety\SendSosController;
use Illuminate\Support\Facades\Route;

Route::prefix('safety')
    ->middleware([
        'auth:sanctum',
        'verified',
        'role:rider|driver',
        'profile_step:completed',
        'set_locale',
    ])
    ->group(function (): void {
        Route::get('contacts', [GetEmergencyContactsController::class, '__invoke'])
            ->name('api.v1.safety.contacts.index');
        Route::post('contacts', [AddEmergencyContactController::class, '__invoke'])
            ->name('api.v1.safety.contacts.store');
        Route::delete('contacts/{contact}', [DeleteEmergencyContactController::class, '__invoke'])
            ->name('api.v1.safety.contacts.destroy');
        Route::post('sos', [SendSosController::class, '__invoke'])
            ->name('api.v1.safety.sos');
    });

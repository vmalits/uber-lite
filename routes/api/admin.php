<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Admin\LoginController;

Route::prefix('admin')
    ->group(function (): void {
        Route::post('login', [LoginController::class, '__invoke'])->name('api.v1.admin.login');
    });

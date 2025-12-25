<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    require __DIR__.'/api/auth.php';
    require __DIR__.'/api/rider.php';
    require __DIR__.'/api/centrifugo.php';
    require __DIR__.'/api/driver.php';
});

<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Rider\WalletTopUpWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('webhooks')->group(function (): void {
    Route::post('stripe/wallet-top-up', [WalletTopUpWebhookController::class, '__invoke'])
        ->name('api.v1.webhooks.stripe.wallet-top-up');
});

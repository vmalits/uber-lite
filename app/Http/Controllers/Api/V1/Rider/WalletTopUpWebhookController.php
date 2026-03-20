<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessWalletTopUpWebhookJob;
use App\Services\Webhook\SignatureVerifier;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;

#[Group('Webhooks')]
#[Endpoint('Stripe Wallet Top-Up Webhook', 'Handle Stripe payment intent webhooks for wallet top-ups')]
final class WalletTopUpWebhookController extends Controller
{
    public function __construct(
        private readonly SignatureVerifier $signatureVerifier,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signatureHeader = $request->header('Stripe-Signature');

        if (! $this->signatureVerifier->verify($payload, $signatureHeader ?? '')) {
            Log::warning('Webhook: Invalid signature rejected');

            return ApiResponse::error('Invalid signature', 400);
        }

        /** @var array<string, mixed>|null $payloadArray */
        $payloadArray = json_decode($payload, true);

        if (! \is_array($payloadArray)) {
            Log::warning('Webhook: Invalid JSON payload');

            return ApiResponse::error('Invalid payload', 400);
        }

        $eventId = $payloadArray['id'] ?? null;
        $eventType = $payloadArray['type'] ?? null;

        if (! \is_string($eventId) || ! \is_string($eventType)) {
            Log::warning('Webhook: Missing event id or type');

            return ApiResponse::error('Missing event id or type', 400);
        }

        $data = $payloadArray['data'] ?? [];

        if (! \is_array($data)) {
            $data = [];
        }

        $dataObject = \is_array($data['object'] ?? null) ? $data['object'] : [];

        /** @var array<string, mixed> $dataObject */
        ProcessWalletTopUpWebhookJob::dispatch(
            eventId: $eventId,
            eventType: $eventType,
            data: $dataObject,
        );

        return ApiResponse::success(['received' => true]);
    }
}

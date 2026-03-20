<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\Rider\CompleteWalletTopUpAction;
use App\Models\ProcessedWebhook;
use App\Models\WalletTopUp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;
use Throwable;

final class ProcessWalletTopUpWebhookJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $timeout = 60;

    /**
     * @var array<int>
     */
    public array $backoff = [30, 60, 120];

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        public readonly string $eventId,
        public readonly string $eventType,
        public readonly array $data,
    ) {}

    public function handle(
        LoggerInterface $logger,
        CompleteWalletTopUpAction $completeAction,
    ): void {
        if (ProcessedWebhook::alreadyProcessed($this->eventId)) {
            $logger->info('Webhook already processed', ['event_id' => $this->eventId]);

            return;
        }

        $topUp = $this->resolveTopUp($logger);

        if ($topUp === null) {
            return;
        }

        match ($this->eventType) {
            'payment_intent.succeeded'      => $completeAction->handle($topUp),
            'payment_intent.payment_failed' => $this->failTopUp($topUp, $logger),
            default                         => null,
        };

        ProcessedWebhook::markAsProcessed($this->eventId, $this->eventType);
    }

    private function resolveTopUp(LoggerInterface $logger): ?WalletTopUp
    {
        $paymentIntentId = $this->data['id'] ?? null;

        if (! \is_string($paymentIntentId)) {
            $logger->warning('Webhook missing payment_intent_id', ['event_id' => $this->eventId]);

            return null;
        }

        $topUp = WalletTopUp::query()
            ->where('payment_intent_id', $paymentIntentId)
            ->first();

        if (! $topUp) {
            $logger->warning('WalletTopUp not found', [
                'event_id'          => $this->eventId,
                'payment_intent_id' => $paymentIntentId,
            ]);

            return null;
        }

        if (! $topUp->isPending()) {
            $logger->info('WalletTopUp not pending, skipping', [
                'event_id'  => $this->eventId,
                'top_up_id' => $topUp->id,
                'status'    => $topUp->status->value,
            ]);

            return null;
        }

        return $topUp;
    }

    private function failTopUp(WalletTopUp $topUp, LoggerInterface $logger): void
    {
        $error = $this->data['last_payment_error'] ?? null;
        $reason = (\is_array($error) && isset($error['message']) && \is_string($error['message']))
            ? $error['message']
            : 'Payment failed';

        $logger->info('Processing failed wallet top-up', [
            'top_up_id' => $topUp->id,
            'reason'    => $reason,
        ]);

        $topUp->markAsFailed($reason);
    }

    public function failed(?Throwable $exception, LoggerInterface $logger): void
    {
        $logger->error('ProcessWalletTopUpWebhookJob failed after all retries', [
            'event_id'   => $this->eventId,
            'event_type' => $this->eventType,
            'exception'  => $exception?->getMessage(),
            'trace'      => $exception?->getTraceAsString(),
        ]);
    }

    /**
     * @return array<int>
     */
    public function backoff(): array
    {
        return $this->backoff;
    }
}

<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Driver\DriverScheduleSyncService;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'driver:sync-schedules',
    description: 'Sync driver availability based on their configured schedules',
)]
final class SyncDriverSchedulesCommand extends Command
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly DriverScheduleSyncService $syncService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Syncing driver schedules...');

        try {
            $result = $this->syncService->syncAll();
        } catch (\Throwable $e) {
            $this->error('Failed to sync driver schedules: '.$e->getMessage());
            $this->logger->error('Driver schedule sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return self::FAILURE;
        }

        $this->info("Sync complete: {$result->wentOnline} went online, {$result->wentOffline} went offline");
        $this->line("  Candidates: {$result->candidatesOnline} online, {$result->candidatesOffline} offline");

        if ($result->wentOnline > 0 || $result->wentOffline > 0) {
            $this->logger->info('Driver schedule sync completed', [
                'went_online'  => $result->wentOnline,
                'went_offline' => $result->wentOffline,
            ]);
        }

        return self::SUCCESS;
    }
}

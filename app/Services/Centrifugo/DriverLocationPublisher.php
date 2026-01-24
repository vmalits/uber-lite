<?php

declare(strict_types=1);

namespace App\Services\Centrifugo;

use App\Data\Driver\DriverRealtimeLocationData;
use App\Services\Driver\DriverLocationConfig;
use denis660\Centrifugo\Centrifugo;
use Illuminate\Support\Facades\Log;
use Throwable;

final readonly class DriverLocationPublisher
{
    public function __construct(
        private Centrifugo $centrifugo,
        private DriverLocationConfig $config,
    ) {}

    public function publish(DriverRealtimeLocationData $data): void
    {
        if (! $this->config->publishAdminRegion()) {
            return;
        }

        $payload = [
            'event' => 'driver.location_updated',
            'data'  => [
                'driver_id' => $data->driver_id,
                'status'    => $data->status->value,
                'lat'       => $data->lat,
                'lng'       => $data->lng,
                'server_ts' => $data->ts,
            ],
        ];

        if ($data->lat === null || $data->lng === null) {
            return;
        }

        $this->publishSafely($this->regionChannel($data->lat, $data->lng), $payload);
    }

    /**
     * @param array{event: string, data: array<string, mixed>} $payload
     */
    private function publishSafely(string $channel, array $payload): void
    {
        try {
            $this->centrifugo->publish($channel, $payload, true);
        } catch (Throwable $exception) {
            Log::warning('Failed to publish driver location to Centrifugo.', [
                'channel'   => $channel,
                'driver_id' => $payload['data']['driver_id'] ?? null,
                'error'     => $exception->getMessage(),
            ]);
        }
    }

    private function regionChannel(float $lat, float $lng): string
    {
        $precision = $this->config->regionPrecision();
        $latKey = number_format(round($lat, $precision), $precision, '.', '');
        $lngKey = number_format(round($lng, $precision), $precision, '.', '');

        return "region:{$latKey}:{$lngKey}";
    }
}

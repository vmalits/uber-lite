<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ProcessedWebhook;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProcessedWebhook>
 */
final class ProcessedWebhookFactory extends Factory
{
    protected $model = ProcessedWebhook::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id'     => fake()->uuid(),
            'event_type'   => fake()->randomElement(['payment_intent.succeeded', 'payment_intent.canceled']),
            'processed_at' => now(),
        ];
    }
}

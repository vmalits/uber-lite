<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AnnouncementTarget;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Announcement>
 */
final class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'admin_id'     => User::factory(),
            'title'        => $this->faker->sentence(4),
            'body'         => $this->faker->paragraphs(2, true),
            'target'       => AnnouncementTarget::ALL,
            'is_active'    => true,
            'published_at' => now(),
            'expires_at'   => now()->addDays(30),
        ];
    }
}

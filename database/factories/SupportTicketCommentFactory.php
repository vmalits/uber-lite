<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SupportTicket;
use App\Models\SupportTicketComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupportTicketComment>
 */
final class SupportTicketCommentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => SupportTicket::factory(),
            'user_id'   => User::factory(),
            'message'   => $this->faker->paragraph(),
        ];
    }
}

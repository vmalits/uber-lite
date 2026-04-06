<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AnnouncementTarget;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

final class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@uber-lite.md')->first();

        if (! $admin) {
            return;
        }

        Announcement::query()->create([
            'admin_id' => $admin->id,
            'title'    => 'Welcome to Uber Lite!',
            'body'     => 'Thank you for joining our platform. We are excited to have you on board.
             Enjoy your first ride with a special discount using code WELCOME50!',
            'target'       => AnnouncementTarget::ALL,
            'is_active'    => true,
            'published_at' => now()->subDays(30),
            'expires_at'   => now()->addMonths(6),
        ]);

        Announcement::query()->create([
            'admin_id' => $admin->id,
            'title'    => 'New pricing zones available',
            'body'     => 'We have updated our pricing zones in Chișinău.
            Check the updated surge pricing in Centru and Aeroport areas. Plan your trips accordingly!',
            'target'       => AnnouncementTarget::RIDERS,
            'is_active'    => true,
            'published_at' => now()->subDays(7),
            'expires_at'   => now()->addMonths(2),
        ]);

        Announcement::query()->create([
            'admin_id' => $admin->id,
            'title'    => 'Weekly bonus program',
            'body'     => 'Complete 30 rides this week and earn a 500 MDL bonus!
            The program runs from Monday to Sunday. Good luck and drive safe!',
            'target'       => AnnouncementTarget::DRIVERS,
            'is_active'    => true,
            'published_at' => now()->subDays(2),
            'expires_at'   => now()->addDays(5),
        ]);
    }
}

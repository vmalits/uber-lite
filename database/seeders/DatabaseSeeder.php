<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AchievementSeeder::class,
            PromoCodeSeeder::class,
            AdminSeeder::class,
            DriverSeeder::class,
            RiderSeeder::class,
            RideSeeder::class,
            AnnouncementSeeder::class,
            SupportDataSeeder::class,
        ]);
    }
}

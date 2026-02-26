<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AchievementCategory;
use App\Models\Achievement;
use Illuminate\Database\Seeder;

final class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            // Driver achievements
            [
                'name'          => 'First Ride',
                'key'           => 'driver_first_ride',
                'description'   => 'Complete your first ride as a driver',
                'icon'          => 'trophy',
                'category'      => AchievementCategory::DRIVER,
                'target_value'  => 1,
                'points_reward' => 10,
            ],
            [
                'name'          => 'Getting Started',
                'key'           => 'driver_10_rides',
                'description'   => 'Complete 10 rides',
                'icon'          => 'star',
                'category'      => AchievementCategory::DRIVER,
                'target_value'  => 10,
                'points_reward' => 30,
            ],
            [
                'name'          => 'Pro Driver',
                'key'           => 'driver_50_rides',
                'description'   => 'Complete 50 rides',
                'icon'          => 'medal',
                'category'      => AchievementCategory::DRIVER,
                'target_value'  => 50,
                'points_reward' => 75,
            ],
            [
                'name'          => 'Veteran Driver',
                'key'           => 'driver_100_rides',
                'description'   => 'Complete 100 rides',
                'icon'          => 'crown',
                'category'      => AchievementCategory::DRIVER,
                'target_value'  => 100,
                'points_reward' => 150,
            ],
            [
                'name'          => 'Elite Driver',
                'key'           => 'driver_500_rides',
                'description'   => 'Complete 500 rides',
                'icon'          => 'diamond',
                'category'      => AchievementCategory::DRIVER,
                'target_value'  => 500,
                'points_reward' => 500,
            ],
            [
                'name'          => 'Five Star Driver',
                'key'           => 'driver_five_star',
                'description'   => 'Maintain a 4.9+ rating with 100+ completed rides',
                'icon'          => 'star-filled',
                'category'      => AchievementCategory::DRIVER,
                'target_value'  => 1,
                'points_reward' => 100,
                'metadata'      => ['min_rating' => 4.9, 'min_rides' => 100],
            ],

            // Rider achievements
            [
                'name'          => 'First Trip',
                'key'           => 'rider_first_ride',
                'description'   => 'Take your first ride',
                'icon'          => 'star',
                'category'      => AchievementCategory::RIDER,
                'target_value'  => 1,
                'points_reward' => 10,
            ],
            [
                'name'          => 'Regular Rider',
                'key'           => 'rider_10_rides',
                'description'   => 'Take 10 rides',
                'icon'          => 'medal',
                'category'      => AchievementCategory::RIDER,
                'target_value'  => 10,
                'points_reward' => 30,
            ],
            [
                'name'          => 'Loyal Customer',
                'key'           => 'rider_50_rides',
                'description'   => 'Take 50 rides',
                'icon'          => 'crown',
                'category'      => AchievementCategory::RIDER,
                'target_value'  => 50,
                'points_reward' => 75,
            ],
            [
                'name'          => 'Super Rider',
                'key'           => 'rider_100_rides',
                'description'   => 'Take 100 rides',
                'icon'          => 'diamond',
                'category'      => AchievementCategory::RIDER,
                'target_value'  => 100,
                'points_reward' => 150,
            ],
            [
                'name'          => 'Explorer',
                'key'           => 'rider_explorer',
                'description'   => 'Visit 10 different destinations',
                'icon'          => 'compass',
                'category'      => AchievementCategory::RIDER,
                'target_value'  => 10,
                'points_reward' => 40,
            ],
            [
                'name'          => 'Big Spender',
                'key'           => 'rider_big_spender',
                'description'   => 'Spend $1000 or more on rides',
                'icon'          => 'wallet',
                'category'      => AchievementCategory::RIDER,
                'target_value'  => 1000,
                'points_reward' => 100,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::updateOrCreate(
                ['key' => $achievement['key']],
                $achievement,
            );
        }
    }
}

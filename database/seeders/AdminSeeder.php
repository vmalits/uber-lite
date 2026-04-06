<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Locale;
use App\Enums\ProfileStep;
use App\Models\User;
use Illuminate\Database\Seeder;

final class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->verified()->create([
            'first_name'   => 'System',
            'last_name'    => 'Admin',
            'email'        => 'admin@uber-lite.md',
            'phone'        => '+37360000001',
            'profile_step' => ProfileStep::COMPLETED,
            'locale'       => Locale::EN,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DriverDocumentStatus;
use App\Enums\DriverDocumentType;
use App\Models\DriverDocument;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DriverDocumentFactory extends Factory
{
    protected $model = DriverDocument::class;

    public function definition(): array
    {
        return [
            'driver_id'        => User::factory(),
            'type'             => $this->faker->randomElement(DriverDocumentType::cases()),
            'file_path'        => 'documents/'.$this->faker->uuid().'/'.$this->faker->word().'.pdf',
            'original_name'    => $this->faker->word().'.pdf',
            'mime_type'        => 'application/pdf',
            'size'             => $this->faker->numberBetween(100000, 5000000),
            'status'           => DriverDocumentStatus::PENDING,
            'verified_by'      => null,
            'rejection_reason' => null,
            'verified_at'      => null,
            'expires_at'       => $this->faker->dateTimeBetween('+1 month', '+2 years'),
        ];
    }
}

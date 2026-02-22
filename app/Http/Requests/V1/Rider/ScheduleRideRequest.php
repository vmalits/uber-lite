<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use Illuminate\Foundation\Http\FormRequest;

final class ScheduleRideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'origin_address'      => ['required', 'string', 'max:255'],
            'origin_lat'          => ['required', 'numeric', 'between:-90,90'],
            'origin_lng'          => ['required', 'numeric', 'between:-180,180'],
            'destination_address' => ['required', 'string', 'max:255'],
            'destination_lat'     => ['required', 'numeric', 'between:-90,90'],
            'destination_lng'     => ['required', 'numeric', 'between:-180,180'],
            'scheduled_at'        => ['required', 'date', 'after:now'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'origin_address' => [
                'description' => 'The starting address of the ride.',
                'example'     => 'bd. Ștefan cel Mare și Sfânt, 1, Chișinău',
            ],
            'origin_lat' => [
                'description' => 'The latitude of the origin.',
                'example'     => 47.0105,
            ],
            'origin_lng' => [
                'description' => 'The longitude of the origin.',
                'example'     => 28.8638,
            ],
            'destination_address' => [
                'description' => 'The destination address of the ride.',
                'example'     => 'str. Mihai Eminescu, 50, Chișinău',
            ],
            'destination_lat' => [
                'description' => 'The latitude of the destination.',
                'example'     => 47.0225,
            ],
            'destination_lng' => [
                'description' => 'The longitude of the destination.',
                'example'     => 28.8353,
            ],
            'scheduled_at' => [
                'description' => 'The date and time when the ride is scheduled to start.',
                'example'     => now()->addHours(2)->toDateTimeString(),
            ],
        ];
    }
}

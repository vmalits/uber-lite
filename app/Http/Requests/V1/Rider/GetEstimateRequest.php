<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use App\Models\Ride;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

final class GetEstimateRequest extends FormRequest
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
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            /** @var User $user */
            $user = $this->user();

            $hasActiveRide = Ride::query()
                ->where('rider_id', $user->id)
                ->active()
                ->exists();

            if ($hasActiveRide) {
                $validator->errors()->add('ride', __('messages.ride.already_active'));
            }
        });
    }

    /**
     * @return array<string, array<string, float|string>>
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
        ];
    }
}

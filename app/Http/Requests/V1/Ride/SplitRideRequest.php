<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Ride;

use App\Data\Ride\Split\SplitRideData;
use Illuminate\Foundation\Http\FormRequest;

final class SplitRideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'participants'         => ['required', 'array', 'min:1'],
            'participants.*.name'  => ['required', 'string', 'max:255'],
            'participants.*.email' => ['nullable', 'email', 'max:255'],
            'participants.*.phone' => ['nullable', 'string', 'max:20'],
            'participants.*.share' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'note'                 => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'participants' => [
                'description' => 'List of participants to split the ride with.',
            ],
            'note' => [
                'description' => 'Optional note for the split.',
            ],
        ];
    }

    public function toData(): SplitRideData
    {
        return SplitRideData::from($this->validated());
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use App\Data\Rider\RateRideData;
use Illuminate\Foundation\Http\FormRequest;

class RateRideRequest extends FormRequest
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
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, array<string, int|string>>
     */
    public function bodyParameters(): array
    {
        return [
            'rating' => [
                'description' => 'Rating from 1 to 5.',
                'example'     => 5,
            ],
            'comment' => [
                'description' => 'Optional comment.',
                'example'     => 'Great ride!',
            ],
        ];
    }

    public function toRateRideData(): RateRideData
    {
        return new RateRideData(
            rating: $this->integer('rating'),
            comment: $this->string('comment')->toString(),
        );
    }
}

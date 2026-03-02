<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use App\Data\Rider\RateRideData;
use App\Data\Rider\TipData;
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
            'rating'      => ['required', 'integer', 'min:1', 'max:5'],
            'comment'     => ['nullable', 'string', 'max:1000'],
            'tip.amount'  => ['nullable', 'integer', 'min:0', 'max:100000'],
            'tip.comment' => ['nullable', 'string', 'max:500'],
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
            'tip.amount' => [
                'description' => 'Tip amount in cents.',
                'example'     => 500,
            ],
            'tip.comment' => [
                'description' => 'Optional tip comment.',
                'example'     => 'Thank you!',
            ],
        ];
    }

    public function toRateRideData(): RateRideData
    {
        $tip = null;
        if ($this->has('tip.amount')) {
            $tip = new TipData(
                amount: $this->integer('tip.amount'),
                comment: $this->string('tip.comment')->toString() ?: null,
            );
        }

        return new RateRideData(
            rating: $this->integer('rating'),
            comment: $this->string('comment')->toString() ?: null,
            tip: $tip,
        );
    }
}

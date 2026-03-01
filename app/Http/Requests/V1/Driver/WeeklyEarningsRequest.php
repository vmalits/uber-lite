<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Driver;

use Illuminate\Foundation\Http\FormRequest;

final class WeeklyEarningsRequest extends FormRequest
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
            'weeks' => ['nullable', 'integer', 'min:1', 'max:12'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function queryParameters(): array
    {
        return [
            'weeks' => [
                'description' => 'Number of weeks to include (1-12)',
                'example'     => 4,
                'type'        => 'integer',
                'required'    => false,
            ],
        ];
    }

    public function weeks(): int
    {
        /** @var array{weeks?: int|string} $validated */
        $validated = $this->validated();

        return isset($validated['weeks']) ? (int) $validated['weeks'] : 4;
    }
}

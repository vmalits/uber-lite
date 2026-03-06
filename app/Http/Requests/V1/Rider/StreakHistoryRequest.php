<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use Illuminate\Foundation\Http\FormRequest;

final class StreakHistoryRequest extends FormRequest
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
            'days' => ['nullable', 'integer', 'min:7', 'max:90'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function queryParameters(): array
    {
        return [
            'days' => [
                'description' => 'Number of days to include in history (7-90)',
                'example'     => 30,
                'type'        => 'integer',
                'required'    => false,
            ],
        ];
    }

    public function days(): int
    {
        /** @var array{days?: int|string} $validated */
        $validated = $this->validated();

        return isset($validated['days']) ? (int) $validated['days'] : 30;
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Driver;

use Illuminate\Foundation\Http\FormRequest;

final class MonthlyEarningsRequest extends FormRequest
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
            'months' => ['nullable', 'integer', 'min:1', 'max:12'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function queryParameters(): array
    {
        return [
            'months' => [
                'description' => 'Number of months to include (1-12)',
                'example'     => 3,
                'type'        => 'integer',
                'required'    => false,
            ],
        ];
    }

    public function months(): int
    {
        /** @var array{months?: int|string} $validated */
        $validated = $this->validated();

        return isset($validated['months']) ? (int) $validated['months'] : 3;
    }
}

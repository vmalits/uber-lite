<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use Illuminate\Foundation\Http\FormRequest;

final class GetReceiptsRequest extends FormRequest
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
            'from'     => ['nullable', 'date', 'before_or_equal:to'],
            'to'       => ['nullable', 'date', 'after_or_equal:from'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function queryParameters(): array
    {
        return [
            'from' => [
                'description' => 'Start date (Y-m-d). Defaults to 30 days ago.',
                'example'     => '2026-01-01',
                'type'        => 'string',
                'format'      => 'date',
                'required'    => false,
            ],
            'to' => [
                'description' => 'End date (Y-m-d). Defaults to today.',
                'example'     => '2026-01-31',
                'type'        => 'string',
                'format'      => 'date',
                'required'    => false,
            ],
            'per_page' => [
                'description' => 'Number of items per page (1-100).',
                'example'     => 15,
                'type'        => 'integer',
                'required'    => false,
            ],
        ];
    }

    public function from(): ?string
    {
        /** @var array{from?: string} $validated */
        $validated = $this->validated();

        return $validated['from'] ?? null;
    }

    public function to(): ?string
    {
        /** @var array{to?: string} $validated */
        $validated = $this->validated();

        return $validated['to'] ?? null;
    }

    public function perPage(): int
    {
        /** @var array{per_page?: int|string} $validated */
        $validated = $this->validated();

        return isset($validated['per_page']) ? (int) $validated['per_page'] : 15;
    }
}

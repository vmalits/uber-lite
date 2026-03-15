<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Driver;

use Illuminate\Foundation\Http\FormRequest;

final class GetDriverReviewsRequest extends FormRequest
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
            'sort'     => ['nullable', 'string', 'in:created_at,-created_at,rating,-rating'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function queryParameters(): array
    {
        return [
            'sort' => [
                'description' => 'Sort by field. Allowed: created_at, rating. Prefix with - for descending.',
                'example'     => '-created_at',
                'type'        => 'string',
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

    public function perPage(): int
    {
        /** @var array{per_page?: int|string} $validated */
        $validated = $this->validated();

        return isset($validated['per_page']) ? (int) $validated['per_page'] : 15;
    }
}

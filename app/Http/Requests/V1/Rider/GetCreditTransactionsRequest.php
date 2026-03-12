<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use App\Enums\CreditTransactionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class GetCreditTransactionsRequest extends FormRequest
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
            'per_page'    => ['nullable', 'integer', 'min:2', 'max:50'],
            'filter'      => ['nullable', 'array'],
            'filter.type' => ['nullable', 'string', new Enum(CreditTransactionType::class)],
            'filter.from' => ['nullable', 'date'],
            'filter.to'   => ['nullable', 'date', 'after_or_equal:filter.from'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function queryParameters(): array
    {
        return [
            'per_page' => [
                'description' => 'Number of items per page',
                'example'     => 15,
            ],
            'filter[type]' => [
                'description' => 'Filter by transaction type',
                'example'     => 'referral_bonus',
            ],
            'filter[from]' => [
                'description' => 'Filter from date',
                'example'     => '2026-01-01',
            ],
            'filter[to]' => [
                'description' => 'Filter to date',
                'example'     => '2026-03-11',
            ],
        ];
    }

    public function perPage(): int
    {
        return $this->integer('per_page', 15);
    }
}

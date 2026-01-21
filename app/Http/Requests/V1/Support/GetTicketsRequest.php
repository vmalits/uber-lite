<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Support;

use App\Enums\SupportTicketStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\In;

final class GetTicketsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<Enum|In|string>>
     */
    public function rules(): array
    {
        $sortFields = [
            'created_at', '-created_at',
            'updated_at', '-updated_at',
            'status', '-status',
        ];

        return [
            'per_page'       => ['nullable', 'integer', 'min:2', 'max:50'],
            'filter.status'  => ['nullable', 'string', new Enum(SupportTicketStatus::class)],
            'filter'         => ['nullable', 'array'],
            'filter.subject' => ['nullable', 'string', 'max:255'],
            'sort'           => [
                'nullable',
                'string',
                Rule::in($sortFields),
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function queryParameters(): array
    {
        return [
            'per_page' => [
                'description' => 'Number of items per page.',
                'example'     => 15,
            ],
            'filter[status]' => [
                'description' => 'Filter tickets by status (open, closed, pending).',
                'example'     => 'open',
            ],
            'filter[subject]' => [
                'description' => 'Filter tickets by subject (partial match).',
                'example'     => 'Payment',
            ],
            'sort' => [
                'description' => 'Sort field (prefix with - for descending). Supported: created_at, updated_at, status.',
                'example'     => '-created_at',
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use App\Enums\SupportTicketStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

final class GetTicketsRequest extends FormRequest
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
        $sortFields = [
            'created_at', '-created_at',
            'updated_at', '-updated_at',
            'subject', '-subject',
            'status', '-status',
        ];

        return [
            'per_page'       => ['nullable', 'integer', 'min:2', 'max:50'],
            'status'         => ['nullable', 'string', new Enum(SupportTicketStatus::class)],
            'filter'         => ['nullable', 'array'],
            'filter.user_id' => ['nullable', 'string', 'size:26'],
            'filter.subject' => ['nullable', 'string'],
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
                'description' => 'Number of items per page',
                'example'     => 20,
                'type'        => 'integer',
                'required'    => false,
            ],
            'status' => [
                'description' => 'Filter by ticket status',
                'example'     => 'open',
                'type'        => 'string',
                'required'    => false,
            ],
            'filter.user_id' => [
                'description' => 'Filter by user ID (ULID)',
                'example'     => '01HZY1K8QK8ZQ2V6J8F4K8Q2V6',
                'type'        => 'string',
                'required'    => false,
            ],
            'filter.subject' => [
                'description' => 'Filter by subject (partial match)',
                'example'     => 'Issue with ride',
                'type'        => 'string',
                'required'    => false,
            ],
            'sort' => [
                'description' => 'Sort field (prefix with - for descending)',
                'example'     => '-created_at',
                'type'        => 'string',
                'required'    => false,
            ],
        ];
    }
}

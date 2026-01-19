<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use App\Enums\UserStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\In;

final class GetDriversRequest extends FormRequest
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
            'last_login_at', '-last_login_at',
            'phone', '-phone',
            'email', '-email',
        ];

        return [
            'per_page'          => ['nullable', 'integer', 'min:2', 'max:50'],
            'status'            => ['nullable', 'string', new Enum(UserStatus::class)],
            'banned'            => ['nullable', 'boolean'],
            'filter'            => ['nullable', 'array'],
            'filter.phone'      => ['nullable', 'string'],
            'filter.email'      => ['nullable', 'string'],
            'filter.first_name' => ['nullable', 'string'],
            'filter.last_name'  => ['nullable', 'string'],
            'sort'              => [
                'nullable',
                'string',
                Rule::in($sortFields),
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'per_page' => [
                'description' => 'Number of items per page',
                'example'     => 15,
                'type'        => 'integer',
                'required'    => false,
            ],
            'status' => [
                'description' => 'Filter by user status',
                'example'     => 'active',
                'type'        => 'string',
                'required'    => false,
            ],
            'banned' => [
                'description' => 'Filter banned drivers (true/false)',
                'example'     => false,
                'type'        => 'boolean',
                'required'    => false,
            ],
            'filter.phone' => [
                'description' => 'Filter by phone number (partial match)',
                'example'     => '+3736',
                'type'        => 'string',
                'required'    => false,
            ],
            'filter.email' => [
                'description' => 'Filter by email (partial match)',
                'example'     => 'example',
                'type'        => 'string',
                'required'    => false,
            ],
            'filter.first_name' => [
                'description' => 'Filter by first name (partial match)',
                'example'     => 'John',
                'type'        => 'string',
                'required'    => false,
            ],
            'filter.last_name' => [
                'description' => 'Filter by last name (partial match)',
                'example'     => 'Doe',
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

<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use App\Enums\UserStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

final class UpdateUserStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<Rule|string|Enum>>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::enum(UserStatus::class)],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'status' => [
                'description' => 'New user status: active, inactive, or banned.',
                'example'     => 'inactive',
            ],
        ];
    }
}

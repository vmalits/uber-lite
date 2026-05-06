<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class AssignDriverRequest extends FormRequest
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
            'driver_id' => [
                'required',
                'string',
                Rule::exists('users', 'id')->where('role', UserRole::DRIVER->value),
            ],
        ];
    }
}

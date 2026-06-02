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

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'driver_id' => [
                'description' => 'The ULID of the driver to assign to the ride.',
                'example'     => '01HXYZ123456789',
                'type'        => 'string',
            ],
        ];
    }
}

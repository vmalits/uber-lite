<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use App\Data\Admin\AdminLoginData;
use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'phone'    => ['required', 'string', 'phone:MD', 'exists:users,phone'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required.',
            'phone.phone'    => 'Phone number is invalid.',
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'phone' => [
                'description' => 'Phone number of the admin',
                'example'     => '+3736000000',
                'type'        => 'string',
                'required'    => true,
            ],
            'password' => [
                'description' => 'Password of the admin',
                'example'     => 'admin12345',
                'type'        => 'string',
                'required'    => true,
            ],
        ];
    }

    public function toDto(): AdminLoginData
    {
        return new AdminLoginData(
            phone: $this->string('phone')->toString(),
            password: $this->string('password')->toString(),
        );
    }
}

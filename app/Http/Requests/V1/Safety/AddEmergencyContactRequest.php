<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Safety;

use App\Data\Safety\CreateEmergencyContactData;
use Illuminate\Foundation\Http\FormRequest;

final class AddEmergencyContactRequest extends FormRequest
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
            'name'       => ['required', 'string', 'max:100'],
            'phone'      => ['required', 'string', 'max:20'],
            'email'      => ['nullable', 'email', 'max:100'],
            'is_primary' => ['boolean'],
        ];
    }

    /**
     * @return array<string, array<string, string|int|bool>>
     */
    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'The name of the emergency contact',
                'example'     => 'John Doe',
            ],
            'phone' => [
                'description' => 'The phone number of the emergency contact',
                'example'     => '+37369123456',
            ],
            'email' => [
                'description' => 'The email of the emergency contact (optional)',
                'example'     => 'john@example.com',
            ],
            'is_primary' => [
                'description' => 'Whether this is the primary emergency contact',
                'example'     => true,
            ],
        ];
    }

    public function toData(): CreateEmergencyContactData
    {
        return CreateEmergencyContactData::from($this->validated());
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Profile;

use App\Data\Profile\UpdateProfileData;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name'  => ['sometimes', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function bodyParameters(): array
    {
        return [
            'first_name' => [
                'description' => 'First name of the user.',
                'example'     => 'John',
            ],
            'last_name' => [
                'description' => 'Last name of the user.',
                'example'     => 'Doe',
            ],
        ];
    }

    public function toDto(): UpdateProfileData
    {
        return new UpdateProfileData(
            firstName: $this->has('first_name') ? $this->string('first_name')->toString() : null,
            lastName: $this->has('last_name') ? $this->string('last_name')->toString() : null,
        );
    }
}

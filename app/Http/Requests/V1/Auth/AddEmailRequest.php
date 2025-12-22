<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Auth;

use App\Data\Auth\AddEmailData;
use Illuminate\Foundation\Http\FormRequest;

class AddEmailRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function bodyParameters(): array
    {
        return [
            'email' => [
                'description' => 'User\'s email address. Must be a valid, unique email.',
                'example'     => 'user@example.com',
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function toDto(): AddEmailData
    {
        return new AddEmailData(
            email: $this->string('email')->toString(),
        );
    }
}

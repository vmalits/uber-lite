<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AddEmailRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'phone:MD'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class OtpCodeRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'phone:MD'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

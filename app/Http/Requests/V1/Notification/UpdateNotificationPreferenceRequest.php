<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Notification;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateNotificationPreferenceRequest extends FormRequest
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
            'ride_updates'  => ['sometimes', 'required', 'boolean'],
            'promo'         => ['sometimes', 'required', 'boolean'],
            'ride_split'    => ['sometimes', 'required', 'boolean'],
            'achievement'   => ['sometimes', 'required', 'boolean'],
            'streak'        => ['sometimes', 'required', 'boolean'],
            'safety'        => ['sometimes', 'required', 'boolean'],
            'push_enabled'  => ['sometimes', 'required', 'boolean'],
            'email_enabled' => ['sometimes', 'required', 'boolean'],
        ];
    }
}

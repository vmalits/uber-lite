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

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'ride_updates' => [
                'description' => 'Enable or disable ride update notifications.',
                'example'     => true,
                'type'        => 'boolean',
            ],
            'promo' => [
                'description' => 'Enable or disable promotional notifications.',
                'example'     => false,
                'type'        => 'boolean',
            ],
            'ride_split' => [
                'description' => 'Enable or disable ride split notifications.',
                'example'     => true,
                'type'        => 'boolean',
            ],
            'achievement' => [
                'description' => 'Enable or disable achievement notifications.',
                'example'     => true,
                'type'        => 'boolean',
            ],
            'streak' => [
                'description' => 'Enable or disable streak notifications.',
                'example'     => true,
                'type'        => 'boolean',
            ],
            'safety' => [
                'description' => 'Enable or disable safety notifications.',
                'example'     => true,
                'type'        => 'boolean',
            ],
            'push_enabled' => [
                'description' => 'Enable or disable push notifications globally.',
                'example'     => true,
                'type'        => 'boolean',
            ],
            'email_enabled' => [
                'description' => 'Enable or disable email notifications globally.',
                'example'     => false,
                'type'        => 'boolean',
            ],
        ];
    }
}

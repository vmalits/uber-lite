<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Device;

use App\Enums\DevicePlatform;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CreateDeviceTokenRequest extends FormRequest
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
            'platform'    => ['required', Rule::enum(DevicePlatform::class)],
            'token'       => ['required', 'string', 'max:500'],
            'device_name' => ['nullable', 'string', 'max:100'],
            'app_version' => ['nullable', 'string', 'max:20'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'platform' => [
                'description' => 'Device platform: ios, android, or web.',
                'example'     => 'ios',
            ],
            'token' => [
                'description' => 'FCM or APNs device push token.',
                'example'     => 'fKz3Y2x1...A1b2C3d4',
            ],
            'device_name' => [
                'description' => 'Human-readable device name.',
                'example'     => 'iPhone 15 Pro',
            ],
            'app_version' => [
                'description' => 'Application version string.',
                'example'     => '1.2.0',
            ],
        ];
    }
}

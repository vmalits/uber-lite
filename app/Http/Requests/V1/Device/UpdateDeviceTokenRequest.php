<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Device;

use App\Enums\DevicePlatform;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateDeviceTokenRequest extends FormRequest
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
            'platform'    => ['sometimes', 'required', Rule::enum(DevicePlatform::class)],
            'device_name' => ['sometimes', 'nullable', 'string', 'max:100'],
            'app_version' => ['sometimes', 'nullable', 'string', 'max:20'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'platform' => [
                'description' => 'The device platform (ios or android).',
                'example'     => 'ios',
                'type'        => 'string',
            ],
            'device_name' => [
                'description' => 'The human-readable device name.',
                'example'     => 'iPhone 15 Pro',
                'type'        => 'string',
            ],
            'app_version' => [
                'description' => 'The application version string.',
                'example'     => '1.2.3',
                'type'        => 'string',
            ],
        ];
    }
}

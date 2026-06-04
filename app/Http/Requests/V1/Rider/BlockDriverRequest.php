<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use App\Data\Rider\BlockedDriverData;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class BlockDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string|mixed>>
     */
    public function rules(): array
    {
        return [
            'driver_id' => [
                'required',
                'ulid',
                Rule::exists('users', 'id')->where('role', UserRole::DRIVER->value),
                Rule::unique('blocked_drivers', 'driver_id')->where('rider_id', $this->user()?->id),
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'driver_id' => [
                'description' => 'The ID of the driver to block.',
                'example'     => '01HQXYZABC1234567890DEFGHI',
                'type'        => 'string',
            ],
        ];
    }

    public function toData(): BlockedDriverData
    {
        /** @var array{driver_id: string} $validated */
        $validated = $this->validated();

        return BlockedDriverData::from($validated);
    }
}

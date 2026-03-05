<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use App\Data\Rider\AddFavoriteDriverData;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class AddFavoriteDriverRequest extends FormRequest
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
                Rule::unique('favorite_drivers', 'driver_id')->where('user_id', $this->user()?->id),
            ],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function bodyParameters(): array
    {
        return [
            'driver_id' => [
                'description' => 'The ID of the driver to add to favorites',
                'example'     => '01HQXYZABC1234567890DEFGHI',
            ],
        ];
    }

    public function toData(): AddFavoriteDriverData
    {
        /** @var array{driver_id: string} $validated */
        $validated = $this->validated();

        return AddFavoriteDriverData::from($validated);
    }
}

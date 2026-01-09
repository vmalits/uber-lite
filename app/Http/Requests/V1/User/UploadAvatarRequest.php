<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\User;

use App\Data\User\UploadAvatarData;
use Illuminate\Foundation\Http\FormRequest;

final class UploadAvatarRequest extends FormRequest
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
            'avatar' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'avatar' => [
                'description' => 'Avatar image (jpg, png, webp, max 2MB)',
                'example'     => 'avatar.jpg',
                'type'        => 'file',
                'required'    => true,
            ],
        ];
    }

    public function toUploadAvatarData(): UploadAvatarData
    {
        return UploadAvatarData::fromRequest($this);
    }
}

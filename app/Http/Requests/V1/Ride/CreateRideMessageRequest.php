<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Ride;

use App\Data\Ride\CreateRideMessageData;
use Illuminate\Foundation\Http\FormRequest;

final class CreateRideMessageRequest extends FormRequest
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
            'message' => ['required', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function bodyParameters(): array
    {
        return [
            'message' => [
                'description' => 'Chat message content.',
                'example'     => 'I am waiting near the entrance.',
            ],
        ];
    }

    public function toData(): CreateRideMessageData
    {
        return CreateRideMessageData::from($this->validated());
    }
}

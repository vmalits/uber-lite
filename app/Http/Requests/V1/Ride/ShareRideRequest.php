<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Ride;

use App\Data\Ride\Share\ShareRideData;
use Illuminate\Foundation\Http\FormRequest;

final class ShareRideRequest extends FormRequest
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
            'contact_name'  => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:20'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'message'       => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function bodyParameters(): array
    {
        return [
            'contact_name' => [
                'description' => 'Name of the contact person to share the ride with.',
                'example'     => 'John Doe',
            ],
            'contact_phone' => [
                'description' => 'Phone number of the contact person.',
                'example'     => '+1234567890',
            ],
            'contact_email' => [
                'description' => 'Email address of the contact person (optional).',
                'example'     => 'john@example.com',
            ],
            'message' => [
                'description' => 'Custom message to include with the ride share notification.',
                'example'     => 'This is my current ride. Stay safe!',
            ],
        ];
    }

    public function toData(): ShareRideData
    {
        return ShareRideData::from($this->validated());
    }
}

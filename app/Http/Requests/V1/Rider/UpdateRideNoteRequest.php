<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateRideNoteRequest extends FormRequest
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
            'note' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function bodyParameters(): array
    {
        return [
            'note' => [
                'description' => 'Pickup/dropoff instructions for the driver.',
                'example'     => 'Please ring the doorbell twice. Blue house with white gate.',
            ],
        ];
    }

    public function getNote(): ?string
    {
        $note = trim($this->string('note')->toString());

        return $note !== '' ? $note : null;
    }
}

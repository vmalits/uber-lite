<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Support;

use App\Data\Support\CreateTicketData;
use Illuminate\Foundation\Http\FormRequest;

final class CreateTicketRequest extends FormRequest
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
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function bodyParameters(): array
    {
        return [
            'subject' => [
                'description' => 'The subject of the support ticket.',
                'example'     => 'Issue with payment',
            ],
            'message' => [
                'description' => 'The detailed message of the support ticket.',
                'example'     => 'I was charged twice for my last ride.',
            ],
        ];
    }

    public function toData(): CreateTicketData
    {
        return CreateTicketData::from($this->validated());
    }
}

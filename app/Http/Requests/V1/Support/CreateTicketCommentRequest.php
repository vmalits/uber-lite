<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Support;

use App\Data\Support\CreateTicketCommentData;
use Illuminate\Foundation\Http\FormRequest;

final class CreateTicketCommentRequest extends FormRequest
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
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function bodyParameters(): array
    {
        return [
            'message' => [
                'description' => 'Comment message for the support ticket.',
                'example'     => 'I have additional details about this issue.',
            ],
        ];
    }

    public function toData(): CreateTicketCommentData
    {
        return CreateTicketCommentData::from($this->validated());
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Support;

use App\Data\Support\UpdateTicketStatusData;
use App\Enums\SupportTicketStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class UpdateTicketStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<Enum|string>>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', new Enum(SupportTicketStatus::class)],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'status' => [
                'description' => 'New status for the support ticket.',
                'example'     => SupportTicketStatus::CLOSED->value,
            ],
        ];
    }

    public function toData(): UpdateTicketStatusData
    {
        return UpdateTicketStatusData::from($this->validated());
    }
}

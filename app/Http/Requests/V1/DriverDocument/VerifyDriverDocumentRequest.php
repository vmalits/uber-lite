<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\DriverDocument;

use App\Enums\DriverDocumentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class VerifyDriverDocumentRequest extends FormRequest
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
            'status'           => ['required', Rule::in([DriverDocumentStatus::APPROVED->value, DriverDocumentStatus::REJECTED->value])],
            'rejection_reason' => ['required_if:status,rejected', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function bodyParameters(): array
    {
        return [
            'status' => [
                'description' => 'Verification status (approved or rejected)',
                'example'     => 'approved',
            ],
            'rejection_reason' => [
                'description' => 'Reason for rejection (required when status is rejected)',
                'example'     => 'Document is expired',
            ],
        ];
    }
}

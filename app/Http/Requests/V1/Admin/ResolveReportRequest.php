<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use App\Enums\ReportStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ResolveReportRequest extends FormRequest
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
            'status'     => ['required', Rule::enum(ReportStatus::class)],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'status' => [
                'description' => 'New status for the report.',
                'example'     => 'resolved',
            ],
            'admin_note' => [
                'description' => 'Admin resolution note.',
                'example'     => 'Action taken: driver warned.',
            ],
        ];
    }
}

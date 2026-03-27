<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Report;

use App\Enums\ReportReason;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CreateReportRequest extends FormRequest
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
            'target_id'   => ['required', 'ulid', 'exists:users,id', 'different:reporter'],
            'reason'      => ['required', Rule::enum(ReportReason::class)],
            'description' => ['nullable', 'string', 'max:1000'],
            'ride_id'     => ['nullable', 'ulid', 'exists:rides,id'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'target_id' => [
                'description' => 'The ID of the user being reported.',
                'example'     => '01HQXYZABC1234567890DEFGHI',
            ],
            'reason' => [
                'description' => 'Reason for the report.',
                'example'     => 'unsafe_driving',
            ],
            'description' => [
                'description' => 'Detailed description of the incident.',
                'example'     => 'The driver was speeding and running red lights.',
            ],
            'ride_id' => [
                'description' => 'Optional ride ID related to the report.',
                'example'     => '01HQXYZABC1234567890DEFGHI',
            ],
        ];
    }
}

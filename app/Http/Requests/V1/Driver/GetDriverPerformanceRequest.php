<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Driver;

use App\Enums\TimePeriod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class GetDriverPerformanceRequest extends FormRequest
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
            'period' => ['nullable', 'string', Rule::in(array_column(TimePeriod::cases(), 'value'))],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function queryParameters(): array
    {
        return [
            'period' => [
                'description' => 'Time period for performance data (current_month, last_month, all_time).',
                'example'     => 'current_month',
                'type'        => 'string',
                'required'    => false,
            ],
        ];
    }

    public function period(): string
    {
        /** @var array{period?: string} $validated */
        $validated = $this->validated();

        return $validated['period'] ?? TimePeriod::CURRENT_MONTH->value;
    }
}

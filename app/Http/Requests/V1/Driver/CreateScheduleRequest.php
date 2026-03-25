<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Driver;

use Illuminate\Foundation\Http\FormRequest;

final class CreateScheduleRequest extends FormRequest
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
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'start_time'  => ['required', 'date_format:H:i'],
            'end_time'    => ['required', 'date_format:H:i', 'after:start_time'],
            'enabled'     => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, array<string, bool|float|int|string>>
     */
    public function bodyParameters(): array
    {
        return [
            'day_of_week' => [
                'description' => 'Day of week (0=Sunday, 6=Saturday)',
                'example'     => 1,
            ],
            'start_time' => [
                'description' => 'Shift start time (HH:mm format)',
                'example'     => '08:00',
            ],
            'end_time' => [
                'description' => 'Shift end time (HH:mm format)',
                'example'     => '17:00',
            ],
            'enabled' => [
                'description' => 'Whether the schedule is enabled',
                'example'     => true,
            ],
        ];
    }
}

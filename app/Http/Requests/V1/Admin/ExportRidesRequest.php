<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use App\Enums\RideStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class ExportRidesRequest extends FormRequest
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
            'date_from' => ['nullable', 'date', 'before_or_equal:date_to'],
            'date_to'   => ['nullable', 'date', 'after_or_equal:date_from'],
            'status'    => ['nullable', 'string', new Enum(RideStatus::class)],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function queryParameters(): array
    {
        return [
            'date_from' => [
                'description' => 'Filter rides from this date (Y-m-d format)',
                'example'     => '2024-01-01',
                'type'        => 'string',
                'required'    => false,
            ],
            'date_to' => [
                'description' => 'Filter rides to this date (Y-m-d format)',
                'example'     => '2024-12-31',
                'type'        => 'string',
                'required'    => false,
            ],
            'status' => [
                'description' => 'Filter by ride status',
                'example'     => 'completed',
                'type'        => 'string',
                'required'    => false,
            ],
        ];
    }
}

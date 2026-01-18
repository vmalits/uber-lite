<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use App\Enums\RideStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\In;

final class GetRidesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<Enum|In|string>>
     */
    public function rules(): array
    {
        $sortFields = [
            'created_at', '-created_at',
            'updated_at', '-updated_at',
            'price', '-price',
            'status', '-status',
        ];

        return [
            'per_page'         => ['nullable', 'integer', 'min:2', 'max:50'],
            'status'           => ['nullable', 'string', new Enum(RideStatus::class)],
            'filter'           => ['nullable', 'array'],
            'filter.rider_id'  => ['nullable', 'string'],
            'filter.driver_id' => ['nullable', 'string'],
            'sort'             => [
                'nullable',
                'string',
                Rule::in($sortFields),
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'per_page' => [
                'description' => 'Number of items per page',
                'example'     => 15,
                'type'        => 'integer',
                'required'    => false,
            ],
            'status' => [
                'description' => 'Filter by ride status',
                'example'     => 'completed',
                'type'        => 'string',
                'required'    => false,
            ],
            'filter.rider_id' => [
                'description' => 'Filter by rider ID',
                'example'     => '01h7x9b6w5e8v2k4n8m0p3q5r1',
                'type'        => 'string',
                'required'    => false,
            ],
            'filter.driver_id' => [
                'description' => 'Filter by driver ID',
                'example'     => '01h7x9b6w5e8v2k4n8m0p3q5r2',
                'type'        => 'string',
                'required'    => false,
            ],
            'sort' => [
                'description' => 'Sort field (prefix with - for descending)',
                'example'     => '-created_at',
                'type'        => 'string',
                'required'    => false,
            ],
        ];
    }
}

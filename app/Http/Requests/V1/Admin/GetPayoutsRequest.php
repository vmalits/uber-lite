<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use App\Data\Admin\GetPayoutsData;
use App\Enums\PayoutStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class GetPayoutsRequest extends FormRequest
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
            'per_page'  => ['nullable', 'integer', 'min:2', 'max:50'],
            'status'    => ['nullable', 'string', new Enum(PayoutStatus::class)],
            'from'      => ['nullable', 'date'],
            'to'        => ['nullable', 'date', 'after_or_equal:from'],
            'driver_id' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function queryParameters(): array
    {
        return [
            'per_page' => [
                'description' => 'Number of items per page',
                'example'     => 15,
            ],
            'status' => [
                'description' => 'Filter by payout status',
                'example'     => 'pending',
            ],
            'from' => [
                'description' => 'Filter from date',
                'example'     => '2026-01-01',
            ],
            'to' => [
                'description' => 'Filter to date',
                'example'     => '2026-03-11',
            ],
            'driver_id' => [
                'description' => 'Filter by driver ID',
                'example'     => '01jk9v6v9v6v9v6v9v6v9v6v9v',
            ],
        ];
    }

    public function toData(): GetPayoutsData
    {
        return new GetPayoutsData(
            perPage: $this->integer('per_page', 15),
            status: $this->query('status'),
            from: $this->query('from'),
            to: $this->query('to'),
            driverId: $this->query('driver_id'),
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Ride;

use App\Enums\RideSplitStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class RespondToSplitRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'split_id' => [
                'required',
                'string',
                Rule::exists('ride_splits', 'id'),
            ],
            'status' => [
                'required',
                'string',
                Rule::in([RideSplitStatus::ACCEPTED->value, RideSplitStatus::DECLINED->value]),
            ],
        ];
    }
}

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

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'split_id' => [
                'description' => 'The ULID of the ride split to respond to.',
                'example'     => '01HXYZ123456789',
                'type'        => 'string',
            ],
            'status' => [
                'description' => 'The response to the split request (accepted or declined).',
                'example'     => 'accepted',
                'type'        => 'string',
            ],
        ];
    }
}

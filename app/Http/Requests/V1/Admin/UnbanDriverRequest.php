<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use App\Data\Admin\UnbanDriverData;
use Illuminate\Foundation\Http\FormRequest;

final class UnbanDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'reason' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'reason.max' => 'The reason may not be longer than 500 characters.',
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'reason' => [
                'description' => 'Reason for unbanning the driver',
                'example'     => 'Ban lifted after review',
                'type'        => 'string',
                'required'    => false,
            ],
        ];
    }

    public function toUnbanDriverData(): UnbanDriverData
    {
        return new UnbanDriverData(
            unbannedBy: $this->user()?->id,
            reason: $this->string('reason')->value() ?: null,
        );
    }
}

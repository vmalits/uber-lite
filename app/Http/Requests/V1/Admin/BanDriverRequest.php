<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use App\Data\Admin\CreateBanData;
use App\Enums\BanSource;
use App\Enums\DriverBanType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rules\Enum;

final class BanDriverRequest extends FormRequest
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
                'required',
                'string',
                'max:500',
            ],

            'ban_type' => [
                'required',
                new Enum(DriverBanType::class),
            ],

            'ban_source' => [
                'required',
                new Enum(BanSource::class),
            ],

            'expires_at' => [
                'nullable',
                'date',
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'reason' => [
                'description' => 'Ban reason',
                'example'     => 'Violation of rules',
                'type'        => 'string',
                'required'    => true,
            ],
            'ban_type' => [
                'description' => 'Ban type (temporary|permanent)',
                'example'     => 'temporary',
                'type'        => 'string',
                'required'    => true,
            ],
            'ban_source' => [
                'description' => 'Ban source (admin|system|automated)',
                'example'     => 'admin',
                'type'        => 'string',
                'required'    => true,
            ],
            'expires_at' => [
                'description' => 'End date of temporary ban (Y-m-d H:i:s)',
                'example'     => '2026-01-10 12:00:00',
                'type'        => 'string',
                'required'    => false,
            ],
        ];
    }

    public function toDto(): CreateBanData
    {
        $expiresAt = $this->date('expires_at');

        return new CreateBanData(
            reason: $this->string('reason')->toString(),
            banType: DriverBanType::from($this->string('ban_type')->toString()),
            banSource: BanSource::from($this->string('ban_source')->toString()),
            expiresAt: $expiresAt ? Carbon::instance($expiresAt) : null,
        );
    }
}

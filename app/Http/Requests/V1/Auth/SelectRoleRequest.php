<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Auth;

use App\Data\Auth\SelectRoleData;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SelectRoleRequest extends FormRequest
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
            'role' => [
                'required',
                Rule::in([UserRole::RIDER->value, UserRole::DRIVER->value]),
            ],
        ];
    }

    public function withValidator(ValidatorContract $validator): void
    {
        $validator->after(function (ValidatorContract $validator): void {
            /** @var User $user */
            $user = $this->user();

            if ($user->phone_verified_at === null) {
                $validator->errors()->add('phone', 'Phone is not verified.');
            }

            if ($user->role !== null) {
                $validator->errors()->add('role', 'Role is already selected.');
            }
        });
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function bodyParameters(): array
    {
        return [
            'role' => [
                'description' => 'The user role. Allowed values: rider, driver',
                'example'     => 'rider',
            ],
        ];
    }

    public function toDto(): SelectRoleData
    {
        return new SelectRoleData(
            role: UserRole::from($this->string('role')->toString()),
        );
    }
}

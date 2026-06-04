<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use App\Models\Ride;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RebookRideRequest extends FormRequest
{
    public function authorize(): bool
    {
        $ride = $this->route('ride');

        return $ride instanceof Ride
            && $this->user() instanceof User
            && $ride->rider()->is($this->user())
            && $ride->status->isFinal();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            /** @var User $user */
            $user = $this->user();

            $hasActiveRide = Ride::query()
                ->where('rider_id', $user->id)
                ->active()
                ->exists();

            if ($hasActiveRide) {
                $validator->errors()->add('ride', __('messages.ride.already_active'));
            }
        });
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Vehicle;

use App\Data\Vehicle\CreateVehicleData;
use App\Enums\VehicleType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

final class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('plate_number')) {
            $plateNumber = $this->input('plate_number');

            if (\is_scalar($plateNumber)) {
                $this->merge([
                    'plate_number' => strtoupper(
                        str_replace([' ', '-'], '', (string) $plateNumber),
                    ),
                ]);
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'brand'        => ['required', 'string', 'max:255'],
            'model'        => ['required', 'string', 'max:255'],
            'year'         => ['required', 'integer', 'between:1990,'.now()->year],
            'color'        => ['required', 'string', 'max:255'],
            'plate_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('vehicles', 'plate_number'),
            ],
            'vehicle_type' => ['required', new Enum(VehicleType::class)],
            'seats'        => ['required', 'integer', 'between:1,20'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function bodyParameters(): array
    {
        return [
            'brand' => [
                'description' => 'The brand of the vehicle',
                'example'     => 'Toyota',
            ],
            'model' => [
                'description' => 'The model of the vehicle',
                'example'     => 'Camry',
            ],
            'year' => [
                'description' => 'The manufacturing year of the vehicle',
                'example'     => 2022,
            ],
            'color' => [
                'description' => 'The color of the vehicle',
                'example'     => 'Black',
            ],
            'plate_number' => [
                'description' => 'The license plate number of the vehicle',
                'example'     => 'AA1234BB',
            ],
            'vehicle_type' => [
                'description' => 'The type of the vehicle',
                'example'     => 'sedan',
            ],
            'seats' => [
                'description' => 'The number of seats in the vehicle',
                'example'     => 4,
            ],
        ];
    }

    public function toDto(): CreateVehicleData
    {
        return new CreateVehicleData(
            brand: $this->string('brand')->toString(),
            model: $this->string('model')->toString(),
            year: $this->integer('year'),
            color: $this->string('color')->toString(),
            plate_number: $this->string('plate_number')->toString(),
            vehicle_type: VehicleType::from($this->string('vehicle_type')->toString()),
            seats: $this->integer('seats'),
        );
    }
}

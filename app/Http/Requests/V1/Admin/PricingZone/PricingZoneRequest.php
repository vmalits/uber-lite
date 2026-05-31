<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin\PricingZone;

use App\Models\PricingZone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class PricingZoneRequest extends FormRequest
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
        /** @var PricingZone|null $zone */
        $zone = $this->route('zone');
        $zoneId = $zone?->id;

        return [
            'name'             => ['required', 'string', 'max:255'],
            'slug'             => ['required', 'string', 'max:255', Rule::unique('pricing_zones', 'slug')->ignore($zoneId)],
            'is_enabled'       => ['boolean'],
            'surge_multiplier' => ['required', 'numeric', 'min:1', 'max:5'],
            'reason'           => ['nullable', 'string', 'max:50'],
            'center_lat'       => ['required', 'numeric', 'between:-90,90'],
            'center_lng'       => ['required', 'numeric', 'between:-180,180'],
            'radius_meters'    => ['required', 'integer', 'min:100', 'max:50000'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function bodyParameters(): array
    {
        return [
            'name'             => ['description' => 'Zone name', 'example' => 'Centru, Chișinău'],
            'slug'             => ['description' => 'Unique zone slug', 'example' => 'centru-chisinau'],
            'is_enabled'       => ['description' => 'Whether the zone is active', 'example' => true],
            'surge_multiplier' => ['description' => 'Surge pricing multiplier (1.0 - 5.0)', 'example' => 1.5],
            'reason'           => ['description' => 'Reason for surge', 'example' => 'high_demand_area'],
            'center_lat'       => ['description' => 'Latitude of zone center', 'example' => 47.0268],
            'center_lng'       => ['description' => 'Longitude of zone center', 'example' => 28.8416],
            'radius_meters'    => ['description' => 'Radius in meters', 'example' => 1500],
        ];
    }
}

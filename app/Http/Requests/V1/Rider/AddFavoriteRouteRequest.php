<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use App\Data\Rider\FavoriteRouteData;
use Illuminate\Foundation\Http\FormRequest;

final class AddFavoriteRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name'                => ['required', 'string', 'max:255'],
            'origin_address'      => ['required', 'string', 'max:500'],
            'origin_lat'          => ['required', 'numeric', 'between:-90,90'],
            'origin_lng'          => ['required', 'numeric', 'between:-180,180'],
            'destination_address' => ['required', 'string', 'max:500'],
            'destination_lat'     => ['required', 'numeric', 'between:-90,90'],
            'destination_lng'     => ['required', 'numeric', 'between:-180,180'],
            'type'                => ['nullable', 'string', 'in:home,work,gym,other'],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Route name (e.g., Home-Work)',
                'example'     => 'Home to Work',
            ],
            'origin_address' => [
                'description' => 'Origin address',
                'example'     => '123 Main St, New York',
            ],
            'origin_lat' => [
                'description' => 'Origin latitude',
                'example'     => '40.7128',
            ],
            'origin_lng' => [
                'description' => 'Origin longitude',
                'example'     => '-74.0060',
            ],
            'destination_address' => [
                'description' => 'Destination address',
                'example'     => '456 Park Ave, New York',
            ],
            'destination_lat' => [
                'description' => 'Destination latitude',
                'example'     => '40.7589',
            ],
            'destination_lng' => [
                'description' => 'Destination longitude',
                'example'     => '-73.9851',
            ],
            'type' => [
                'description' => 'Route type (home, work, gym, other)',
                'example'     => 'work',
            ],
        ];
    }

    public function toData(): FavoriteRouteData
    {
        /** @var array{name: string, origin_address: string, origin_lat: float, origin_lng: float, destination_address: string, destination_lat: float, destination_lng: float, type?: string|null} $validated */
        $validated = $this->validated();

        return FavoriteRouteData::fromArray($validated);
    }
}

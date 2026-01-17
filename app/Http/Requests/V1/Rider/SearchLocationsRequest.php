<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use App\Data\Rider\SearchLocationsData;
use Illuminate\Foundation\Http\FormRequest;

final class SearchLocationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'query' => ['required', 'string', 'min:2', 'max:255'],
            'lat'   => ['sometimes', 'nullable', 'numeric', 'between:-90,90'],
            'lng'   => ['sometimes', 'nullable', 'numeric', 'between:-180,180'],
            'limit' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:10'],
        ];
    }

    /**
     * API documentation parameters
     *
     * @return array<string, array<string, mixed>>
     */
    public function queryParameters(): array
    {
        return [
            'query' => [
                'description' => 'Search query (address, place name, POI)',
                'example'     => 'bd. Ștefan cel Mare',
                'type'        => 'string',
            ],
            'lat' => [
                'description' => 'User current latitude (used for prioritizing nearby results)',
                'example'     => 47.0105,
                'type'        => 'number',
            ],
            'lng' => [
                'description' => 'User current longitude (used for prioritizing nearby results)',
                'example'     => 28.8638,
                'type'        => 'number',
            ],
            'limit' => [
                'description' => 'Maximum number of results to return',
                'example'     => 5,
                'type'        => 'integer',
            ],
        ];
    }

    /**
     * Convert request to DTO
     */
    public function toData(): SearchLocationsData
    {
        $validated = $this->validated();

        return SearchLocationsData::from([
            'query' => \is_string($validated['query'] ?? null) ? $validated['query'] : '',
            'limit' => isset($validated['limit']) && \is_int($validated['limit']) ? $validated['limit'] : 5,
            'lat'   => isset($validated['lat']) && is_numeric($validated['lat']) ? (float) $validated['lat'] : null,
            'lng'   => isset($validated['lng']) && is_numeric($validated['lng']) ? (float) $validated['lng'] : null,
        ]);
    }
}

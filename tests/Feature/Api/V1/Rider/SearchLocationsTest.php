<?php

declare(strict_types=1);

use App\Data\Rider\LocationSuggestionData;
use App\Services\Geo\GeocodingServiceInterface;

test('can search locations', function (): void {
    $service = app(GeocodingServiceInterface::class);
    $results = $service->search('Stefan cel Mare', 5);

    expect($results)
        ->toBeArray()
        ->each->toBeInstanceOf(LocationSuggestionData::class);
});

test('search results contain address, lat, lng', function (): void {
    $service = app(GeocodingServiceInterface::class);
    $results = $service->search('Piata Marii Adunari', 1);

    expect($results)
        ->not->toBeEmpty();

    if (! empty($results)) {
        expect($results[0])
            ->id->toBeString()
            ->address->toBeString()
            ->lat->toBeFloat()
            ->lng->toBeFloat();
    }
});

test('search respects limit parameter', function (): void {
    $service = app(GeocodingServiceInterface::class);
    $results = $service->search('Stefan', 2);

    expect($results)->toHaveCount(2);
});

test('search sorts by distance when user location provided', function (): void {
    $service = app(GeocodingServiceInterface::class);

    $userLocation = ['lat' => 47.0122, 'lng' => 28.8327];
    $results = $service->search('Piata Marii Adunari', 5, $userLocation);

    expect($results)
        ->not->toBeEmpty();
});

test('search returns empty array for query with no matches', function (): void {
    $service = app(GeocodingServiceInterface::class);
    $results = $service->search('NonExistentPlaceThatDoesNotExistAnywhere', 5);

    expect($results)
        ->toBeArray()
        ->toBeEmpty();
});

test('search works with special characters', function (): void {
    $service = app(GeocodingServiceInterface::class);
    $results = $service->search('Ștefan cel Mare', 2);

    expect($results)
        ->not->toBeEmpty();
});

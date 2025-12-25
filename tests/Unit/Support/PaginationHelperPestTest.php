<?php

declare(strict_types=1);

use App\Support\PaginationHelper;
use Illuminate\Http\Request;

it('uses default value when per_page is missing', function () {
    $request = Request::create('/');
    expect(PaginationHelper::perPage($request, 15, 5, 50))->toBe(15);
});

it('clamps value to min', function () {
    $request = Request::create('/', 'GET', ['per_page' => 2]);
    expect(PaginationHelper::perPage($request, 15, 5, 50))->toBe(5);
});

it('clamps value to max', function () {
    $request = Request::create('/', 'GET', ['per_page' => 100]);
    expect(PaginationHelper::perPage($request, 15, 5, 50))->toBe(50);
});

it('uses value when within bounds', function () {
    $request = Request::create('/', 'GET', ['per_page' => 20]);
    expect(PaginationHelper::perPage($request, 15, 5, 50))->toBe(20);
});

it('null default falls back to min', function () {
    $request = Request::create('/');
    expect(PaginationHelper::perPage($request, null, 5, 50))->toBe(5);
});

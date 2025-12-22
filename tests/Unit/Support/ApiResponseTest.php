<?php

declare(strict_types=1);

use App\Support\ApiResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Testing\TestResponse;
use Spatie\LaravelData\Data;

it('returns success with message only', function (): void {
    $response = TestResponse::fromBaseResponse(ApiResponse::success(message: 'OK'));

    $response->assertOk()
        ->assertJson([
            'message' => 'OK',
            'data'    => null,
        ]);
});

it('returns success with array data only', function (): void {
    $payload = ['foo' => 'bar'];
    $response = TestResponse::fromBaseResponse(ApiResponse::success($payload));

    $response->assertOk()
        ->assertJson([
            'data' => $payload,
        ]);
});

it('returns success with data, message, meta and custom headers', function (): void {
    $payload = ['x' => 1];
    $meta = ['page' => 2];
    $response = TestResponse::fromBaseResponse(
        ApiResponse::success($payload, 'Done', 200, $meta, ['X-Foo' => 'Bar']));

    $response->assertOk()
        ->assertHeader('X-Foo', 'Bar')
        ->assertJson([
            'message' => 'Done',
            'data'    => $payload,
            'meta'    => $meta,
        ]);
});

it('returns created with 201 status', function (): void {
    $response = TestResponse::fromBaseResponse(ApiResponse::created(['id' => 1], 'Created!'));

    $response->assertCreated()
        ->assertJson([
            'message' => 'Created!',
            'data'    => ['id' => 1],
        ]);
});

it('returns no content with 204 and empty body', function (): void {
    $response = TestResponse::fromBaseResponse(ApiResponse::noContent());

    $response->assertStatus(204)
        ->assertExactJson([]);
});

it('returns error with message only', function (): void {
    $response = TestResponse::fromBaseResponse(ApiResponse::error('Bad', 400));

    $response->assertStatus(400)
        ->assertJson([
            'message' => 'Bad',
        ])->assertJsonMissing(['errors']);
});

it('returns error with message and errors and extra', function (): void {
    $errors = ['field' => ['is invalid']];
    $extra = ['code' => 'E100'];
    $response = TestResponse::fromBaseResponse(ApiResponse::error('Bad', 418, $errors, $extra));

    $response->assertStatus(418)
        ->assertJson([
            'message' => 'Bad',
            'errors'  => $errors,
            'code'    => 'E100',
        ]);
});

it('returns validationError (422) with errors', function (): void {
    $errors = ['name' => ['required']];
    $response = TestResponse::fromBaseResponse(ApiResponse::validationError($errors));

    $response->assertUnprocessable()
        ->assertJson([
            'message' => 'The given data was invalid.',
            'errors'  => $errors,
        ]);
});

it('returns tooManyRequests with and without retry_after', function (): void {
    $r1 = TestResponse::fromBaseResponse(ApiResponse::tooManyRequests('Too many', null));
    $r1->assertStatus(429)
        ->assertJson([
            'message' => 'Too many',
        ])->assertJsonMissing(['retry_after']);

    $r2 = TestResponse::fromBaseResponse(ApiResponse::tooManyRequests('Too many', 60));
    $r2->assertStatus(429)
        ->assertJson([
            'message'     => 'Too many',
            'retry_after' => 60,
        ]);
});

it('returns unauthorized (401)', function (): void {
    $response = TestResponse::fromBaseResponse(ApiResponse::unauthorized('No entry'));
    $response->assertStatus(401)->assertJson(['message' => 'No entry']);

    $responseDefault = TestResponse::fromBaseResponse(ApiResponse::unauthorized());
    $responseDefault->assertStatus(401)->assertJson(['message' => 'Unauthenticated.']);
});

it('returns forbidden (403)', function (): void {
    $response = TestResponse::fromBaseResponse(ApiResponse::forbidden('Forbidden!'));
    $response->assertStatus(403)->assertJson(['message' => 'Forbidden!']);

    $responseDefault = TestResponse::fromBaseResponse(ApiResponse::forbidden());
    $responseDefault->assertStatus(403)->assertJson(['message' => 'Forbidden.']);
});

it('normalizes Data objects to array for data key', function (): void {
    // Simple inline Data class for testing

    $data = new class(1, 'x') extends Data
    {
        public function __construct(public int $a, public string $b) {}
    };
    $response = TestResponse::fromBaseResponse(ApiResponse::success($data));

    $response->assertOk()
        ->assertJson([
            'data' => ['a' => 1, 'b' => 'x'],
        ]);
});

it('normalizes LengthAwarePaginator into items and pagination', function (): void {
    // Arrayable item
    $arrayable = new class implements Arrayable
    {
        public function toArray(): array
        {
            return ['k' => 'v'];
        }
    };

    $items = [$arrayable, ['foo' => 'bar']];
    /** @var LengthAwarePaginator<int, mixed> $paginator */
    $paginator = new Paginator(
        items: $items,
        total: 10,
        perPage: 2,
        currentPage: 1,
        options: ['path' => '/test'],
    );

    $response = TestResponse::fromBaseResponse(ApiResponse::success($paginator));

    $response->assertOk()
        ->assertJsonPath('data.items.0.k', 'v')
        ->assertJsonPath('data.items.1.foo', 'bar')
        ->assertJsonPath('data.pagination.total', 10)
        ->assertJsonPath('data.pagination.per_page', 2)
        ->assertJsonPath('data.pagination.current_page', 1)
        ->assertJsonPath('data.pagination.last_page', 5);
});

it('normalizes Arrayable to array', function (): void {
    $arrayable = new class implements Arrayable
    {
        public function toArray(): array
        {
            return ['a' => 1];
        }
    };

    $response = TestResponse::fromBaseResponse(ApiResponse::success($arrayable));
    $response->assertOk()->assertJson(['data' => ['a' => 1]]);
});

it('passes through raw array data', function (): void {
    $response = TestResponse::fromBaseResponse(ApiResponse::success(['z' => 9]));
    $response->assertOk()->assertJson(['data' => ['z' => 9]]);
});

<?php

declare(strict_types=1);

namespace App\Support;

use App\Services\LocaleService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\Data;

final readonly class ApiResponse
{
    public function __construct(private LocaleService $localeService) {}

    /**
     * Generic success response.
     *
     * @param array<string, mixed>|Arrayable<string, mixed>|Data|LengthAwarePaginator<int, mixed>|null $data
     * @param array<string, mixed>|null $meta
     * @param array<string, string> $headers
     */
    public static function success(
        mixed $data = null,
        ?string $message = null,
        int $status = 200,
        ?array $meta = null,
        array $headers = [],
    ): JsonResponse {
        $payload = [
            'success' => true,
            'data'    => $data !== null ? self::normalizeData($data) : null,
        ];

        if ($message !== null) {
            $payload['message'] = $message;
        }

        if ($meta !== null) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status, $headers);
    }

    /**
     * 201 Created response with data.
     *
     * @param array<string,mixed>|Arrayable<string, mixed>|Data $data
     */
    public static function created(array|Arrayable|Data $data, ?string $message = null): JsonResponse
    {
        return self::success($data, message: $message, status: 201);
    }

    /**
     * 204 No Content.
     */
    public static function noContent(): JsonResponse
    {
        return response()->json([], 204);
    }

    /**
     * 401 Unauthorized.
     */
    public static function unauthorized(?string $message = null): JsonResponse
    {
        $instance = app(self::class);
        $message = $message ?? $instance->localeService->message('messages.errors.unauthorized');

        return self::error($message, 401);
    }

    /**
     * 403 Forbidden.
     */
    public static function forbidden(?string $message = null): JsonResponse
    {
        $instance = app(self::class);
        $message = $message ?? $instance->localeService->message('messages.errors.forbidden');

        return self::error($message, 403);
    }

    /**
     * Error response.
     *
     * @param array<string,mixed>|null $errors
     * @param array<string,mixed> $extra
     */
    public static function error(
        string $message,
        int $status = 400,
        ?array $errors = null,
        array $extra = [],
    ): JsonResponse {
        $payload = array_merge([
            'success' => false,
            'message' => $message,
        ], $extra);

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }

    /**
     * Validation error helper (422 Unprocessable Entity).
     *
     * @param array<string, array<int, string>|string> $errors
     */
    public static function validationError(array $errors): JsonResponse
    {
        $instance = app(self::class);
        $message = $instance->localeService->message('messages.errors.validation_failed');

        return self::error($message, 422, $errors);
    }

    /**
     * Too many requests helper (429) with optional retry_after seconds.
     */
    public static function tooManyRequests(?string $message = null, ?int $retryAfter = null): JsonResponse
    {
        $instance = app(self::class);
        $message = $message ?? $instance->localeService->message('messages.errors.too_many_requests');

        $extra = [];
        if ($retryAfter !== null) {
            $extra['retry_after'] = $retryAfter;
        }

        return self::error($message, 429, extra: $extra);
    }

    /**
     * Normalize various data types to array for JSON payloads.
     *
     * @param array<string, mixed>|Arrayable<string, mixed>|LengthAwarePaginator<int, mixed>|Data $data
     *
     * @return array<string, mixed>|array<int, mixed>
     */
    private static function normalizeData(mixed $data): array
    {
        if ($data instanceof Data) {
            return $data->toArray();
        }

        if ($data instanceof LengthAwarePaginator) {
            return [
                'items' => array_map(
                    static fn (mixed $item): array => $item instanceof Arrayable ? $item->toArray() : (array) $item,
                    $data->items(),
                ),
                'pagination' => [
                    'total'        => $data->total(),
                    'per_page'     => $data->perPage(),
                    'current_page' => $data->currentPage(),
                    'last_page'    => $data->lastPage(),
                ],
            ];
        }

        if ($data instanceof Arrayable) {
            return $data->toArray();
        }

        return $data;
    }
}

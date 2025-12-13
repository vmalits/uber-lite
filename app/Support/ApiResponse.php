<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\Data;

final class ApiResponse
{
    /**
     * Generic success response.
     *
     * @param array<string,mixed>|Arrayable<int|string,mixed>|Data|null $data
     * @param array<string,mixed>|null $meta
     * @param array<string,string> $headers
     */
    public static function success(
        array|Arrayable|Data|LengthAwarePaginator|null $data = null,
        ?string $message = null,
        int $status = 200,
        ?array $meta = null,
        array $headers = [],
    ): JsonResponse {
        $payload = [];

        if ($message !== null) {
            $payload['message'] = $message;
        }

        if ($data !== null) {
            $payload['data'] = self::normalizeData($data);
        }

        if ($meta !== null) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status, $headers);
    }

    /**
     * 201 Created response with data.
     *
     * @param array<string,mixed>|Arrayable<int|string,mixed>|Data $data
     */
    public static function created(array|Arrayable|Data $data): JsonResponse
    {
        return self::success($data, status: 201);
    }

    /**
     * 204 No Content.
     */
    public static function noContent(): JsonResponse
    {
        return response()->json([], 204);
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
    public static function validationError(
        array $errors,
        string $message = 'The given data was invalid.',
    ): JsonResponse {
        return self::error($message, 422, $errors);
    }

    /**
     * Too many requests helper (429) with optional retry_after seconds.
     */
    public static function tooManyRequests(
        string $message = 'Too many requests.',
        ?int $retryAfter = null,
    ): JsonResponse {
        $extra = [];
        if ($retryAfter !== null) {
            $extra['retry_after'] = $retryAfter;
        }

        return self::error($message, 429, extra: $extra);
    }

    /**
     * Normalize various data types to array for JSON payloads.
     *
     * @param array<string,mixed>|Arrayable<int|string,mixed>|LengthAwarePaginator<int,mixed>|Data $data
     *
     * @return array<string,mixed>|array<int,mixed>
     */
    private static function normalizeData(array|Arrayable|Data|LengthAwarePaginator $data): array
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

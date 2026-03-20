<?php

declare(strict_types=1);

namespace App\Services\Webhook;

use Illuminate\Support\Facades\Log;

final class SignatureVerifier
{
    public function __construct(
        private readonly string $webhookSecret,
    ) {}

    public function verify(string $payload, string $signatureHeader): bool
    {
        if (empty($signatureHeader) || empty($payload)) {
            Log::warning('Webhook: Empty signature or payload');

            return false;
        }

        $elements = $this->parseSignatureHeader($signatureHeader);

        if (! isset($elements['t'], $elements['v1'])) {
            Log::warning('Webhook: Missing timestamp or signature in header');

            return false;
        }

        $timestamp = (int) $elements['t'];
        $signature = $elements['v1'];

        if (! $this->isTimestampValid($timestamp)) {
            Log::warning('Webhook: Timestamp too old', ['timestamp' => $timestamp]);

            return false;
        }

        $expectedSignature = $this->computeSignature($timestamp, $payload);

        if (! hash_equals($expectedSignature, $signature)) {
            Log::warning('Webhook: Signature mismatch');

            return false;
        }

        return true;
    }

    /**
     * @return array<string, string>
     */
    private function parseSignatureHeader(string $header): array
    {
        $elements = [];
        $parts = explode(',', $header);

        foreach ($parts as $part) {
            $keyValue = explode('=', $part, 2);
            if (\count($keyValue) === 2) {
                $elements[trim($keyValue[0])] = trim($keyValue[1]);
            }
        }

        return $elements;
    }

    private function isTimestampValid(int $timestamp): bool
    {
        $fiveMinutesAgo = time() - 300;

        return $timestamp >= $fiveMinutesAgo;
    }

    private function computeSignature(int $timestamp, string $payload): string
    {
        $signedPayload = $timestamp.'.'.$payload;

        return hash_hmac('sha256', $signedPayload, $this->webhookSecret);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Webhook;

use App\Services\Webhook\SignatureVerifier;
use Tests\TestCase;

final class SignatureVerifierTest extends TestCase
{
    private const WEBHOOK_SECRET = 'whsec_test_secret';

    private SignatureVerifier $verifier;

    protected function setUp(): void
    {
        parent::setUp();
        $this->verifier = new SignatureVerifier(self::WEBHOOK_SECRET);
    }

    public function test_rejects_empty_signature(): void
    {
        $payload = '{"id":"evt_test","type":"payment_intent.succeeded"}';

        $result = $this->verifier->verify($payload, '');

        $this->assertFalse($result);
    }

    public function test_rejects_empty_payload(): void
    {
        $signatureHeader = 't=1234567890,v1=somesignature';

        $result = $this->verifier->verify('', $signatureHeader);

        $this->assertFalse($result);
    }

    public function test_rejects_missing_timestamp_in_header(): void
    {
        $payload = '{"id":"evt_test"}';
        $signatureHeader = 'v1=somesignature';

        $result = $this->verifier->verify($payload, $signatureHeader);

        $this->assertFalse($result);
    }

    public function test_rejects_missing_signature_in_header(): void
    {
        $payload = '{"id":"evt_test"}';
        $signatureHeader = 't=1234567890';

        $result = $this->verifier->verify($payload, $signatureHeader);

        $this->assertFalse($result);
    }

    public function test_rejects_old_timestamp(): void
    {
        $payload = '{"id":"evt_test"}';
        $oldTimestamp = time() - 600;
        $signedPayload = $oldTimestamp.'.'.$payload;
        $signature = hash_hmac('sha256', $signedPayload, self::WEBHOOK_SECRET);
        $signatureHeader = "t={$oldTimestamp},v1={$signature}";

        $result = $this->verifier->verify($payload, $signatureHeader);

        $this->assertFalse($result);
    }

    public function test_rejects_invalid_signature(): void
    {
        $payload = '{"id":"evt_test"}';
        $timestamp = time();
        $signatureHeader = "t={$timestamp},v1=invalidsignature";

        $result = $this->verifier->verify($payload, $signatureHeader);

        $this->assertFalse($result);
    }

    public function test_accepts_valid_signature(): void
    {
        $payload = '{"id":"evt_test","type":"payment_intent.succeeded"}';
        $timestamp = time();
        $signedPayload = $timestamp.'.'.$payload;
        $signature = hash_hmac('sha256', $signedPayload, self::WEBHOOK_SECRET);
        $signatureHeader = "t={$timestamp},v1={$signature}";

        $result = $this->verifier->verify($payload, $signatureHeader);

        $this->assertTrue($result);
    }

    public function test_accepts_signature_with_extra_elements(): void
    {
        $payload = '{"id":"evt_test"}';
        $timestamp = time();
        $signedPayload = $timestamp.'.'.$payload;
        $signature = hash_hmac('sha256', $signedPayload, self::WEBHOOK_SECRET);
        $signatureHeader = "t={$timestamp},v1={$signature},v0=extrasignature";

        $result = $this->verifier->verify($payload, $signatureHeader);

        $this->assertTrue($result);
    }

    public function test_timing_safe_comparison(): void
    {
        $payload = '{"id":"evt_test"}';
        $timestamp = time();
        $signedPayload = $timestamp.'.'.$payload;
        $correctSignature = hash_hmac('sha256', $signedPayload, self::WEBHOOK_SECRET);
        $wrongSignature = str_repeat('a', \strlen($correctSignature));

        $startCorrect = hrtime(true);
        $this->verifier->verify($payload, "t={$timestamp},v1={$correctSignature}");
        $timeCorrect = hrtime(true) - $startCorrect;

        $startWrong = hrtime(true);
        $this->verifier->verify($payload, "t={$timestamp},v1={$wrongSignature}");
        $timeWrong = hrtime(true) - $startWrong;

        $this->assertLessThan($timeWrong, $timeCorrect);
    }
}

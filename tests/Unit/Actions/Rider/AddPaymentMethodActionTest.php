<?php

declare(strict_types=1);

use App\Actions\Rider\AddPaymentMethodAction;
use App\Enums\PaymentMethodType;
use App\Enums\PaymentProvider;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Services\Payment\FakePaymentService;
use App\Services\Payment\PaymentServiceInterface;

beforeEach(function (): void {
    $this->app->scoped(PaymentServiceInterface::class, fn (): FakePaymentService => new FakePaymentService);
});

test('add payment method stores card with provider token', function (): void {
    /** @var User $user */
    $user = User::factory()->create();

    $action = app(AddPaymentMethodAction::class);

    $data = new App\Data\Rider\AddPaymentMethodData(
        type: PaymentMethodType::CARD,
        provider: PaymentProvider::STRIPE,
        token: 'pm_client_token',
        last_four: '4242',
        card_brand: 'visa',
        expiry_month: 12,
        expiry_year: 2027,
        holder_name: 'John Doe',
    );

    $method = $action->handle($user, $data);

    expect($method)->toBeInstanceOf(PaymentMethod::class)
        ->and($method->user_id)->toBe($user->id)
        ->and($method->last_four)->toBe('4242')
        ->and($method->card_brand)->toBe('visa')
        ->and($method->holder_name)->toBe('John Doe')
        ->and($method->provider_token)->toStartWith('fake_pm_')
        ->and($method->is_default)->toBeTrue();
});

test('first payment method becomes default', function (): void {
    /** @var User $user */
    $user = User::factory()->create();

    $action = app(AddPaymentMethodAction::class);

    $data = new App\Data\Rider\AddPaymentMethodData(
        type: PaymentMethodType::CARD,
        provider: PaymentProvider::STRIPE,
        token: 'pm_token',
        last_four: '1111',
        card_brand: 'visa',
    );

    $method = $action->handle($user, $data);

    expect($method->is_default)->toBeTrue();
});

test('second payment method is not default', function (): void {
    /** @var User $user */
    $user = User::factory()->create();

    PaymentMethod::factory()->create([
        'user_id'    => $user->id,
        'is_default' => true,
    ]);

    $action = app(AddPaymentMethodAction::class);

    $data = new App\Data\Rider\AddPaymentMethodData(
        type: PaymentMethodType::CARD,
        provider: PaymentProvider::STRIPE,
        token: 'pm_token_2',
        last_four: '2222',
        card_brand: 'mastercard',
    );

    $method = $action->handle($user, $data);

    expect($method->is_default)->toBeFalse();
});

test('apple pay payment method stores correctly', function (): void {
    /** @var User $user */
    $user = User::factory()->create();

    $action = app(AddPaymentMethodAction::class);

    $data = new App\Data\Rider\AddPaymentMethodData(
        type: PaymentMethodType::APPLE_PAY,
        provider: PaymentProvider::STRIPE,
        token: 'apple_pay_token',
        last_four: '0000',
        card_brand: 'apple_pay',
    );

    $method = $action->handle($user, $data);

    expect($method->type)->toBe(PaymentMethodType::APPLE_PAY)
        ->and($method->provider)->toBe(PaymentProvider::STRIPE);
});

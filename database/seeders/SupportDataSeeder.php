<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\CreditTransactionType;
use App\Enums\Currency;
use App\Enums\PayoutMethod;
use App\Enums\PayoutStatus;
use App\Enums\ReportReason;
use App\Enums\ReportStatus;
use App\Enums\SupportTicketStatus;
use App\Enums\WalletTopUpStatus;
use App\Models\CreditTransaction;
use App\Models\DriverPayout;
use App\Models\PaymentMethod;
use App\Models\Report;
use App\Models\Ride;
use App\Models\SupportTicket;
use App\Models\SupportTicketComment;
use App\Models\User;
use App\Models\WalletTopUp;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

final class SupportDataSeeder extends Seeder
{
    public function run(): void
    {
        /** @var User|null $admin */
        $admin = User::where('email', 'admin@uber-lite.md')->first();

        /** @var Collection<int, User> $riders */
        $riders = User::where('role', 'rider')->get();

        /** @var Collection<int, User> $drivers */
        $drivers = User::where('role', 'driver')->get();

        /** @var Collection<int, Ride> $rides */
        $rides = Ride::with(['rider', 'driver'])->get();

        $this->seedSupportTickets($admin, $riders);
        $this->seedReports($admin, $riders, $drivers, $rides);
        $this->seedCreditTransactions($riders);
        $this->seedWalletTopUps($riders);
        $this->seedDriverPayouts($drivers);
    }

    /**
     * @param Collection<int, User> $riders
     */
    private function seedSupportTickets(?User $admin, Collection $riders): void
    {
        $ticketsData = [
            ['subject' => 'Wrong charge on my last ride', 'status' => SupportTicketStatus::OPEN],
            ['subject' => 'Driver was rude and unprofessional', 'status' => SupportTicketStatus::PENDING],
            ['subject' => 'Left my phone in the car', 'status' => SupportTicketStatus::OPEN],
            ['subject' => 'App crashed during payment', 'status' => SupportTicketStatus::CLOSED],
            ['subject' => 'Promo code not working', 'status' => SupportTicketStatus::CLOSED],
        ];

        foreach ($ticketsData as $ticketData) {
            /** @var User $rider */
            $rider = $riders->random();
            $ticket = SupportTicket::query()->create([
                'user_id' => $rider->id,
                'subject' => $ticketData['subject'],
                'message' => fake()->paragraphs(2, true),
                'status'  => $ticketData['status'],
            ]);

            SupportTicketComment::query()->create([
                'ticket_id' => $ticket->id,
                'user_id'   => $ticket->user_id,
                'message'   => fake()->paragraph(),
            ]);

            if ($ticket->status !== SupportTicketStatus::OPEN && $admin !== null) {
                SupportTicketComment::query()->create([
                    'ticket_id' => $ticket->id,
                    'user_id'   => $admin->id,
                    'message'   => fake()->randomElement([
                        'We are looking into this issue. Please allow 24-48 hours for resolution.',
                        'Thank you for reaching out. We have escalated this to our team.',
                        'This has been resolved. Please check your account for updates.',
                    ]),
                ]);
            }
        }
    }

    /**
     * @param Collection<int, User> $riders
     * @param Collection<int, User> $drivers
     * @param Collection<int, Ride> $rides
     */
    private function seedReports(?User $admin, Collection $riders, Collection $drivers, Collection $rides): void
    {
        /** @var Ride|null $rideWithDriver */
        $rideWithDriver = $rides->whereNotNull('driver_id')->first();
        /** @var Ride|null $secondRide */
        $secondRide = $rides->whereNotNull('driver_id')->skip(1)->first();

        if ($rideWithDriver !== null) {
            Report::query()->create([
                'reporter_id' => $rideWithDriver->rider_id,
                'target_id'   => $rideWithDriver->driver_id,
                'ride_id'     => $rideWithDriver->id,
                'reason'      => ReportReason::UNSAFE_DRIVING,
                'description' => 'The driver was speeding and ran a red light at the intersection near Centru.',
                'status'      => ReportStatus::PENDING,
            ]);
        }

        if ($secondRide !== null && $admin !== null) {
            Report::query()->create([
                'reporter_id' => $secondRide->rider_id,
                'target_id'   => $secondRide->driver_id,
                'ride_id'     => $secondRide->id,
                'reason'      => ReportReason::INAPPROPRIATE_BEHAVIOR,
                'description' => 'Driver was talking on the phone the entire ride and was very rude when asked to stop.',
                'status'      => ReportStatus::RESOLVED,
                'admin_note'  => 'Driver warned. Repeat offense will result in a temporary ban.',
                'resolved_by' => $admin->id,
                'resolved_at' => now()->subDays(2),
            ]);
        }

        if ($riders->count() >= 2) {
            Report::query()->create([
                'reporter_id' => $riders->first()->id,
                'target_id'   => $riders->skip(1)->first()->id,
                'ride_id'     => null,
                'reason'      => ReportReason::OTHER,
                'description' => 'This user created a fake account and is harassing other riders.',
                'status'      => ReportStatus::PENDING,
            ]);
        }
    }

    /**
     * @param Collection<int, User> $riders
     */
    private function seedCreditTransactions(Collection $riders): void
    {
        /** @var Collection<int, User> $selectedRiders */
        $selectedRiders = $riders->random(min(8, $riders->count()));

        foreach ($selectedRiders as $rider) {
            $balance = $rider->credits_balance;

            CreditTransaction::query()->create([
                'user_id'       => $rider->id,
                'amount'        => 10000,
                'balance_after' => $balance + 10000,
                'type'          => CreditTransactionType::REFERRAL_BONUS,
                'description'   => 'Referral bonus for inviting a friend',
            ]);

            if (fake()->boolean(50)) {
                CreditTransaction::query()->create([
                    'user_id'       => $rider->id,
                    'amount'        => fake()->numberBetween(500, 3000),
                    'balance_after' => $balance + 10000 + fake()->numberBetween(500, 3000),
                    'type'          => CreditTransactionType::PROMO_SAVING,
                    'description'   => 'Promo code discount saved',
                ]);
            }
        }
    }

    /**
     * @param Collection<int, User> $riders
     */
    private function seedWalletTopUps(Collection $riders): void
    {
        /** @var Collection<int, User> $selectedRiders */
        $selectedRiders = $riders->random(min(3, $riders->count()));

        $statuses = [WalletTopUpStatus::COMPLETED, WalletTopUpStatus::PENDING, WalletTopUpStatus::CANCELLED];

        foreach ($selectedRiders as $index => $rider) {
            /** @var PaymentMethod|null $paymentMethod */
            $paymentMethod = $rider->paymentMethods()->first();

            $status = $statuses[$index % 3] ?? WalletTopUpStatus::COMPLETED;

            WalletTopUp::query()->create([
                'user_id'           => $rider->id,
                'payment_method_id' => $paymentMethod?->id,
                'amount'            => fake()->numberBetween(10000, 100000),
                'currency'          => Currency::MDL,
                'payment_intent_id' => 'pi_'.fake()->uuid(),
                'status'            => $status,
                'completed_at'      => $status === WalletTopUpStatus::COMPLETED ? now()->subDays(
                    fake()->numberBetween(1, 14)) : null,
                'failure_reason' => $status === WalletTopUpStatus::CANCELLED ? 'Cancelled by user' : null,
            ]);
        }
    }

    /**
     * @param Collection<int, User> $drivers
     */
    private function seedDriverPayouts(Collection $drivers): void
    {
        /** @var Collection<int, User> $selectedDrivers */
        $selectedDrivers = $drivers->random(min(4, $drivers->count()));

        $payoutConfigs = [
            ['status' => PayoutStatus::PENDING, 'method' => PayoutMethod::BANK_TRANSFER],
            ['status' => PayoutStatus::COMPLETED, 'method' => PayoutMethod::BANK_TRANSFER],
            ['status' => PayoutStatus::PROCESSING, 'method' => PayoutMethod::CRYPTO_WALLET],
            ['status' => PayoutStatus::FAILED, 'method' => PayoutMethod::BANK_TRANSFER],
        ];

        foreach ($selectedDrivers as $index => $driver) {
            $config = $payoutConfigs[$index % \count($payoutConfigs)];

            $data = [
                'driver_id'    => $driver->id,
                'amount'       => fake()->numberBetween(5000, 50000),
                'status'       => $config['status'],
                'method'       => $config['method'],
                'requested_at' => now()->subDays(fake()->numberBetween(1, 14)),
                'description'  => fake()->optional()->sentence(),
            ];

            if ($config['method'] === PayoutMethod::BANK_TRANSFER) {
                $data['bank_name'] = fake()->randomElement([
                    'Moldova Agroindbank', 'Victoriable', 'Moldindconbank', 'BCR Chișinău',
                ]);
                $data['bank_account_number'] = fake()->numerify('####################');
                $data['bank_routing_number'] = fake()->numerify('#########');
            } else {
                $data['crypto_wallet_address'] = '0x'.fake()->regexify('[a-fA-F0-9]{40}');
                $data['crypto_currency'] = fake()->randomElement(['BTC', 'ETH', 'USDT']);
            }

            if ($config['status'] === PayoutStatus::COMPLETED) {
                $data['approved_at'] = now()->subDays(fake()->numberBetween(2, 5));
                $data['processed_at'] = now()->subDays(fake()->numberBetween(1, 3));
                $data['completed_at'] = now()->subDays(fake()->numberBetween(0, 2));
            }

            if ($config['status'] === PayoutStatus::FAILED) {
                $data['failed_at'] = now()->subDays(1);
                $data['failure_reason'] = 'Bank transfer rejected by receiving bank';
            }

            DriverPayout::query()->create($data);
        }
    }
}

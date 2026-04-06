<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Currency;
use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Enums\RideStatus;
use App\Models\PaymentAttempt;
use App\Models\PromoCode;
use App\Models\Ride;
use App\Models\RideMessage;
use App\Models\RideRating;
use App\Models\RideSplit;
use App\Models\RideStop;
use App\Models\RideTip;
use App\Models\User;
use Database\Factories\RideFactory;
use Illuminate\Database\Seeder;

final class RideSeeder extends Seeder
{
    public function run(): void
    {
        $riders = User::where('role', 'rider')->get();
        $drivers = User::where('role', 'driver')->get();
        $promoCodes = PromoCode::all();

        if ($riders->isEmpty() || $drivers->isEmpty()) {
            return;
        }

        $completedRides = collect();

        for ($i = 0; $i < 35; $i++) {
            $rider = $riders->random();
            $driver = $drivers->random();

            $ride = Ride::factory()
                ->forRider($rider)
                ->withDriver($driver)
                ->completed()
                ->create();

            $completedRides->push($ride);

            if (fake()->boolean(70)) {
                RideRating::query()->create([
                    'ride_id'  => $ride->id,
                    'rider_id' => $ride->rider_id,
                    'rating'   => fake()->numberBetween(3, 5),
                    'comment'  => fake()->optional(0.5)->sentence(),
                ]);
            }

            if (fake()->boolean(30)) {
                RideTip::query()->create([
                    'ride_id'   => $ride->id,
                    'rider_id'  => $ride->rider_id,
                    'driver_id' => $ride->driver_id,
                    'amount'    => fake()->randomElement([1000, 2000, 3000, 5000]),
                    'comment'   => fake()->optional()->sentence(),
                ]);
            }

            PaymentAttempt::query()->create([
                'user_id'                 => $ride->rider_id,
                'ride_id'                 => $ride->id,
                'payment_method_id'       => $rider->paymentMethods()->first()?->id,
                'status'                  => PaymentStatus::COMPLETED,
                'amount'                  => $ride->price ?? fake()->numberBetween(5000, 30000),
                'credits_used'            => fake()->boolean(20) ? fake()->numberBetween(500, 2000) : 0,
                'card_amount'             => fake()->numberBetween(3000, 25000),
                'currency'                => Currency::MDL,
                'provider'                => PaymentProvider::STRIPE,
                'provider_transaction_id' => 'ch_'.fake()->uuid(),
                'completed_at'            => $ride->completed_at,
            ]);

            $messageCount = fake()->numberBetween(0, 5);
            for ($m = 0; $m < $messageCount; $m++) {
                RideMessage::query()->create([
                    'ride_id'   => $ride->id,
                    'sender_id' => fake()->boolean(50) ? $ride->rider_id : $ride->driver_id,
                    'message'   => fake()->sentence(),
                    'read_at'   => fake()->boolean(70) ? now() : null,
                ]);
            }
        }

        $stopRides = $completedRides->random(min(8, $completedRides->count()));
        foreach ($stopRides as $ride) {
            $stopCount = fake()->numberBetween(1, 2);
            for ($s = 1; $s <= $stopCount; $s++) {
                RideStop::query()->create([
                    'ride_id' => $ride->id,
                    'order'   => $s,
                    'address' => fake()->randomElement(RideFactory::CHISINAU_LOCATIONS)['address'],
                    'lat'     => fake()->latitude(46.95, 47.08),
                    'lng'     => fake()->longitude(28.75, 28.95),
                ]);
            }
        }

        $splitRides = $completedRides->random(min(5, $completedRides->count()));
        foreach ($splitRides as $ride) {
            RideSplit::query()->create([
                'ride_id'           => $ride->id,
                'participant_name'  => fake()->name(),
                'participant_email' => fake()->email(),
                'participant_phone' => fake()->optional()->phoneNumber(),
                'share'             => $ride->price ? $ride->price / 2 : fake()->numberBetween(500, 5000),
            ]);
        }

        if ($promoCodes->isNotEmpty()) {
            $promoRides = $completedRides->random(min(4, $completedRides->count()));
            foreach ($promoRides as $ride) {
                $promo = $promoCodes->where('is_active', true)->random();
                $discount = $promo->calculateDiscount($ride->price ?? 0);

                $ride->update([
                    'promo_code_id'   => $promo->id,
                    'discount_amount' => $discount,
                ]);

                $promo->increment('used_count');
            }
        }

        $statuses = [
            RideStatus::PENDING,
            RideStatus::ACCEPTED,
            RideStatus::ON_THE_WAY,
            RideStatus::ARRIVED,
            RideStatus::STARTED,
        ];

        foreach ($statuses as $status) {
            $rider = $riders->random();
            $driver = $status !== RideStatus::PENDING ? $drivers->random() : null;

            Ride::factory()
                ->forRider($rider)
                ->when($driver !== null, fn ($f) => $f->withDriver($driver))
                ->state(['status' => $status])
                ->create();
        }

        for ($i = 0; $i < 5; $i++) {
            Ride::factory()
                ->forRider($riders->random())
                ->cancelled()
                ->when(fake()->boolean(60), fn ($f) => $f->withDriver($drivers->random()))
                ->create();
        }

        for ($i = 0; $i < 5; $i++) {
            Ride::factory()
                ->forRider($riders->random())
                ->scheduled()
                ->create();
        }

        for ($i = 0; $i < 10; $i++) {
            $ride = Ride::factory()
                ->forRider($riders->random())
                ->withDriver($drivers->random())
                ->completed()
                ->create([
                    'created_at' => now()->subDays(fake()->numberBetween(31, 90)),
                ]);

            RideRating::query()->create([
                'ride_id'  => $ride->id,
                'rider_id' => $ride->rider_id,
                'rating'   => fake()->numberBetween(3, 5),
            ]);

            PaymentAttempt::query()->create([
                'user_id'                 => $ride->rider_id,
                'ride_id'                 => $ride->id,
                'status'                  => PaymentStatus::COMPLETED,
                'amount'                  => $ride->price ?? fake()->numberBetween(5000, 30000),
                'currency'                => Currency::MDL,
                'provider'                => PaymentProvider::STRIPE,
                'provider_transaction_id' => 'ch_'.fake()->uuid(),
                'completed_at'            => $ride->completed_at,
            ]);
        }
    }
}

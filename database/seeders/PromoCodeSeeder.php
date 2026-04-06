<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\DiscountType;
use App\Models\PromoCode;
use Illuminate\Database\Seeder;

final class PromoCodeSeeder extends Seeder
{
    public function run(): void
    {
        PromoCode::query()->create([
            'code'                 => 'WELCOME50',
            'title'                => 'Welcome Bonus',
            'description'          => '50 MDL off your first ride!',
            'discount_type'        => DiscountType::FIXED,
            'discount_value'       => 5000,
            'max_discount_amount'  => 5000,
            'min_order_amount'     => 2000,
            'usage_limit'          => 1000,
            'usage_limit_per_user' => 1,
            'used_count'           => 0,
            'starts_at'            => now()->subMonths(3),
            'expires_at'           => now()->addMonths(6),
            'is_active'            => true,
        ]);

        PromoCode::query()->create([
            'code'                 => 'SUMMER10',
            'title'                => 'Summer Discount',
            'description'          => '10% off all rides this summer',
            'discount_type'        => DiscountType::PERCENTAGE,
            'discount_value'       => 10,
            'max_discount_amount'  => 3000,
            'min_order_amount'     => 1000,
            'usage_limit'          => null,
            'usage_limit_per_user' => 3,
            'used_count'           => 47,
            'starts_at'            => now()->subMonth(),
            'expires_at'           => now()->addMonths(3),
            'is_active'            => true,
        ]);

        PromoCode::query()->create([
            'code'                 => 'NEWYEAR25',
            'title'                => 'New Year Special',
            'description'          => '25% off rides during New Year celebrations',
            'discount_type'        => DiscountType::PERCENTAGE,
            'discount_value'       => 25,
            'max_discount_amount'  => 5000,
            'min_order_amount'     => 2000,
            'usage_limit'          => 500,
            'usage_limit_per_user' => 2,
            'used_count'           => 0,
            'starts_at'            => now()->addMonths(8),
            'expires_at'           => now()->addMonths(9),
            'is_active'            => true,
        ]);

        PromoCode::query()->create([
            'code'                 => 'EXPIRED20',
            'title'                => 'Old Promotion',
            'description'          => 'This promo code has expired',
            'discount_type'        => DiscountType::FIXED,
            'discount_value'       => 2000,
            'max_discount_amount'  => 2000,
            'min_order_amount'     => 1000,
            'usage_limit'          => 200,
            'usage_limit_per_user' => 1,
            'used_count'           => 198,
            'starts_at'            => now()->subMonths(6),
            'expires_at'           => now()->subMonth(),
            'is_active'            => false,
        ]);
    }
}

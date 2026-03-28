<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Data\Admin\CreateAchievementData;
use App\Models\Achievement;

final readonly class CreateAchievementAction
{
    public function handle(CreateAchievementData $data): Achievement
    {
        /** @var Achievement $achievement */
        $achievement = Achievement::query()->create([
            'name'          => $data->name,
            'key'           => $data->key,
            'description'   => $data->description,
            'icon'          => $data->icon,
            'category'      => $data->category,
            'target_value'  => $data->target_value,
            'points_reward' => $data->points_reward,
            'metadata'      => $data->metadata,
            'is_active'     => $data->is_active,
        ]);

        return $achievement;
    }
}

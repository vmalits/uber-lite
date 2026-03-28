<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use App\Enums\AchievementCategory;
use App\Models\Achievement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class AchievementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Achievement|null $achievement */
        $achievement = $this->route('achievement');
        $achievementId = $achievement?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'key' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('achievements', 'key')->ignore($achievementId),
            ],
            'description'   => ['nullable', 'string'],
            'icon'          => ['nullable', 'string', 'max:255'],
            'category'      => ['required', Rule::enum(AchievementCategory::class)],
            'target_value'  => ['required', 'integer', 'min:1'],
            'points_reward' => ['required', 'integer', 'min:0'],
            'metadata'      => ['nullable', 'array'],
            'is_active'     => ['boolean'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Achievement display name.',
                'example'     => 'First Ride',
            ],
            'key' => [
                'description' => 'Unique identifier key (lowercase, underscores).',
                'example'     => 'first_ride',
            ],
            'description' => [
                'description' => 'Achievement description.',
                'example'     => 'Complete your first ride to earn this achievement.',
            ],
            'icon' => [
                'description' => 'Icon identifier or URL.',
                'example'     => 'trophy',
            ],
            'category' => [
                'description' => 'Achievement category: driver, rider, or common.',
                'example'     => 'common',
            ],
            'target_value' => [
                'description' => 'Target value to complete the achievement.',
                'example'     => 1,
            ],
            'points_reward' => [
                'description' => 'XP points awarded upon completion.',
                'example'     => 50,
            ],
            'metadata' => [
                'description' => 'Additional metadata for the achievement.',
                'example'     => ['bonus_credits' => 10],
            ],
            'is_active' => [
                'description' => 'Whether the achievement is active.',
                'example'     => true,
            ],
        ];
    }
}

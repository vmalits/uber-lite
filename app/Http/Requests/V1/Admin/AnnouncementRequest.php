<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use App\Enums\AnnouncementTarget;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class AnnouncementRequest extends FormRequest
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
        return [
            'title'        => ['required', 'string', 'max:255'],
            'body'         => ['required', 'string'],
            'target'       => ['required', Rule::enum(AnnouncementTarget::class)],
            'is_active'    => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'expires_at'   => ['nullable', 'date', 'after_or_equal:published_at'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'title' => [
                'description' => 'Announcement title.',
                'example'     => 'New Feature Launch',
            ],
            'body' => [
                'description' => 'Announcement body content.',
                'example'     => 'We are excited to announce a new ride scheduling feature!',
            ],
            'target' => [
                'description' => 'Target audience: all, riders, or drivers.',
                'example'     => 'all',
            ],
            'is_active' => [
                'description' => 'Whether the announcement is active.',
                'example'     => true,
            ],
            'published_at' => [
                'description' => 'When the announcement should be published.',
                'example'     => '2026-03-26 00:00:00',
            ],
            'expires_at' => [
                'description' => 'When the announcement should expire.',
                'example'     => '2026-04-26 23:59:59',
            ],
        ];
    }
}

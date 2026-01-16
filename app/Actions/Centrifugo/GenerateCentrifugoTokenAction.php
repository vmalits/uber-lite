<?php

declare(strict_types=1);

namespace App\Actions\Centrifugo;

use App\Models\User;
use denis660\Centrifugo\Centrifugo;

final readonly class GenerateCentrifugoTokenAction
{
    public function __construct(
        private Centrifugo $centrifugo,
    ) {}

    public function handle(User $user): string
    {
        return $this->centrifugo->generateConnectionToken($user->id);
    }
}

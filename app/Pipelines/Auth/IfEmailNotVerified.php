<?php

declare(strict_types=1);

namespace App\Pipelines\Auth;

use App\Enums\NextAction;
use App\Models\User;
use Closure;

final class IfEmailNotVerified
{
    /**
     * @param array{0: User, 1: NextAction} $payload
     *
     * @return array{0: User, 1: NextAction}
     */
    public function handle(array $payload, Closure $next): array|NextAction
    {
        [$user, $action] = $payload;

        if ($action === NextAction::DONE && $user->email !== null && $user->email_verified_at === null) {
            $action = NextAction::VERIFY_EMAIL;
        }

        /** @var array{0: User, 1: NextAction}|NextAction $result */
        $result = $next([$user, $action]);

        return $result;
    }
}

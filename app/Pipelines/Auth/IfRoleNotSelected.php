<?php

declare(strict_types=1);

namespace App\Pipelines\Auth;

use App\Enums\NextAction;
use App\Models\User;
use Closure;

final class IfRoleNotSelected
{
    /**
     * @param array{0: User, 1: NextAction} $payload
     *
     * @return array{0: User, 1: NextAction}
     */
    public function handle(array $payload, Closure $next): array|NextAction
    {
        [$user, $action] = $payload;

        if ($action === NextAction::DONE && $user->role === null) {
            $action = NextAction::SELECT_ROLE;
        }

        /** @var array{0: User, 1: NextAction}|NextAction $result */
        $result = $next([$user, $action]);

        return $result;
    }
}

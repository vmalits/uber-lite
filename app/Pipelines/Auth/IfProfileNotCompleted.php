<?php

declare(strict_types=1);

namespace App\Pipelines\Auth;

use App\Enums\NextAction;
use App\Enums\ProfileStep;
use App\Models\User;
use Closure;

final class IfProfileNotCompleted
{
    /**
     * @param array{0: User, 1: NextAction} $payload
     *
     * @return array{0: User, 1: NextAction}|NextAction
     */
    public function handle(array $payload, Closure $next): array|NextAction
    {
        [$user, $action] = $payload;

        if ($action === NextAction::DONE && $user->profile_step !== ProfileStep::COMPLETED) {
            $action = NextAction::COMPLETE_PROFILE;
        }

        /** @var array{0: User, 1: NextAction}|NextAction $result */
        $result = $next([$user, $action]);

        return $result;
    }
}

<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\NextAction;
use App\Models\User;
use App\Pipelines\Auth\IfEmailNotSet;
use App\Pipelines\Auth\IfEmailNotVerified;
use App\Pipelines\Auth\IfProfileNotCompleted;
use App\Pipelines\Auth\IfRoleNotSelected;
use Illuminate\Pipeline\Pipeline;

final readonly class ResolveNextAction
{
    public function __construct(private Pipeline $pipeline) {}

    public function handle(User $user): NextAction
    {
        $initial = NextAction::DONE;

        /** @var NextAction $result */
        $result = $this->pipeline
            ->send([$user, $initial])
            ->through([
                IfRoleNotSelected::class,
                IfEmailNotSet::class,
                IfEmailNotVerified::class,
                IfProfileNotCompleted::class,
            ])
            ->then(
                /**
                 * @param array{0: User, 1: NextAction|null} $payload
                 */
                function (array $payload): NextAction {
                    $next = $payload[1] ?? null;

                    return $next instanceof NextAction ? $next : NextAction::DONE;
                },
            );

        return $result;
    }
}

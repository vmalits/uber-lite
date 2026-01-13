<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\Locale;
use App\Http\Controllers\Controller;
use App\Http\Requests\SetLocaleRequest;
use App\Models\User;
use App\Services\LocaleService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromFile;

#[Group('Profile')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[ResponseFromFile('docs/examples/set_locale.json', status: 200)]
#[Response(status: 400, description: 'Invalid locale value.')]
#[Response(status: 422, description: 'Validation errors.')]
final class SetLocaleController extends Controller
{
    public function __construct(private readonly LocaleService $locale) {}

    public function __invoke(SetLocaleRequest $request): JsonResponse
    {
        /** @var string $localeValue */
        $localeValue = $request->validated('locale');
        $locale = Locale::fromValue($localeValue);

        if ($locale === null) {
            return ApiResponse::error(
                message: $this->locale->message('messages.errors.invalid_locale'),
            );
        }

        /** @var User $user */
        $user = $request->user();

        $user->update(['locale' => $locale->value]);

        app()->setLocale($locale->value);

        return ApiResponse::success(
            data: ['locale' => $locale->value],
            message: $this->locale->message('messages.success.locale_updated'),
        );
    }
}

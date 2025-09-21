<?php

declare(strict_types=1);

namespace Modules\Users\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\PasswordResetResponse as PasswordResetResponseContract;
use Modules\Core\Enums\FlashMessage;
use Symfony\Component\HttpFoundation\Response;

class SuccessfulPasswordResetLinkRequestResponse implements PasswordResetResponseContract
{
    public function toResponse($request): JsonResponse|Response|RedirectResponse
    {
        return $request->wantsJson()
            ? new JsonResponse(['message' => trans('passwords.sent')], Response::HTTP_OK)
            : back()->with(FlashMessage::Success->value, trans('passwords.sent'));
    }
}

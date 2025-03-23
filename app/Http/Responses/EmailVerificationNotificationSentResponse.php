<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Enums\FlashMessage;
use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\EmailVerificationNotificationSentResponse as EmailVerificationNotificationSentResponseContract;
use Symfony\Component\HttpFoundation\Response;

class EmailVerificationNotificationSentResponse implements EmailVerificationNotificationSentResponseContract
{
    public function toResponse($request): JsonResponse|Response
    {
        return $request->wantsJson()
            ? new JsonResponse('', Response::HTTP_ACCEPTED)
            : back()->with(
                FlashMessage::Success->value,
                'A new verification link has been sent to the email address you provided during registration.'
            );
    }
}

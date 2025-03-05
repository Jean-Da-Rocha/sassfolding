<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Enums\FlashMessageEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\ProfileInformationUpdatedResponse as ProfileInformationUpdatedResponseContract;
use Symfony\Component\HttpFoundation\Response;

class ProfileInformationUpdatedResponse implements ProfileInformationUpdatedResponseContract
{
    public function toResponse($request): JsonResponse|RedirectResponse|Response
    {
        return $request->wantsJson()
            ? new JsonResponse('', Response::HTTP_OK)
            : back()->with(FlashMessageEnum::Success->value, 'Profile Information Updated Successfully');
    }
}

<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use Modules\Core\Enums\FlashMessage;
use Modules\Users\Models\User;

final class DeleteUserAction
{
    public function execute(User $user): void
    {
        $username = $user->name;

        $user->delete();

        session()->flash(FlashMessage::Success->value, sprintf('User "%s" successfully deleted', $username));
    }
}

<?php

declare(strict_types=1);

namespace Modules\Users\Http\Controllers;

use Hybridly\Tables\Actions\BulkSelected;
use Hybridly\Tables\Actions\DataTransferObjects\BulkSelection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Modules\Core\Enums\FlashMessage;
use Modules\Core\Http\Controllers\Controller;
use Modules\Users\Models\User;

class DeactivateUserController extends Controller
{
    public function __invoke(BulkSelection $bulk): RedirectResponse
    {
        $query = User::query()->when(
            $bulk->hasSelection(),
            fn (Builder $builder) => $builder->tap(new BulkSelected($bulk))
        );

        $count = $query->update(['email_verified_at' => null]);

        return back()->with(FlashMessage::Success->value, sprintf('%d user(s) successfully deactivated', $count));
    }
}

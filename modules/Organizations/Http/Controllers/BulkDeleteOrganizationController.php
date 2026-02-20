<?php

declare(strict_types=1);

namespace Modules\Organizations\Http\Controllers;

use Hybridly\Tables\Actions\BulkSelected;
use Hybridly\Tables\Actions\DataTransferObjects\BulkSelection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Modules\Core\Enums\FlashMessage;
use Modules\Core\Http\Controllers\Controller;
use Modules\Organizations\Models\Organization;

class BulkDeleteOrganizationController extends Controller
{
    public function __invoke(BulkSelection $bulk): RedirectResponse
    {
        $query = Organization::query()->when(
            $bulk->hasSelection(),
            fn (Builder $builder) => $builder->tap(new BulkSelected($bulk))
        );

        $count = $query->count();
        $query->delete();

        return back()->with(FlashMessage::Success->value, sprintf('%d organization(s) successfully deleted', $count));
    }
}

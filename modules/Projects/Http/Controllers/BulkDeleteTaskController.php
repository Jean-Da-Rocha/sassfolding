<?php

declare(strict_types=1);

namespace Modules\Projects\Http\Controllers;

use Hybridly\Tables\Actions\BulkSelected;
use Hybridly\Tables\Actions\DataTransferObjects\BulkSelection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Modules\Core\Enums\FlashMessage;
use Modules\Core\Http\Controllers\Controller;
use Modules\Projects\Models\Task;

class BulkDeleteTaskController extends Controller
{
    public function __invoke(BulkSelection $bulk): RedirectResponse
    {
        $query = Task::query()->when(
            $bulk->hasSelection(),
            fn (Builder $builder) => $builder->tap(new BulkSelected($bulk))
        );

        $count = $query->count();
        $query->delete();

        return back()->with(FlashMessage::Success->value, sprintf('%d task(s) successfully deleted', $count));
    }
}

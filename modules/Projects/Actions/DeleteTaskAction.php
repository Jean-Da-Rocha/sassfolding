<?php

declare(strict_types=1);

namespace Modules\Projects\Actions;

use Modules\Core\Enums\FlashMessage;
use Modules\Projects\Models\Task;

final class DeleteTaskAction
{
    public function execute(Task $task): void
    {
        $task->delete();

        session()->flash(FlashMessage::Success->value, 'Task deleted successfully.');
    }
}

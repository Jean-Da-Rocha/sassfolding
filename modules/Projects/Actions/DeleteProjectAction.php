<?php

declare(strict_types=1);

namespace Modules\Projects\Actions;

use Modules\Core\Enums\FlashMessage;
use Modules\Projects\Models\Project;

final class DeleteProjectAction
{
    public function execute(Project $project): void
    {
        $project->delete();

        session()->flash(FlashMessage::Success->value, 'Project deleted successfully.');
    }
}

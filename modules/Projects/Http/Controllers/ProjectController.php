<?php

declare(strict_types=1);

namespace Modules\Projects\Http\Controllers;

use Hybridly\View\Factory as HybridlyView;
use Illuminate\Http\RedirectResponse;
use Modules\Core\Enums\FlashMessage;
use Modules\Core\Http\Controllers\Controller;
use Modules\Projects\Actions\DeleteProjectAction;
use Modules\Projects\Data\ProjectData;
use Modules\Projects\Enums\ProjectStatus;
use Modules\Projects\Models\Project;
use Modules\Projects\Tables\ProjectTable;

class ProjectController extends Controller
{
    public function index(): HybridlyView
    {
        return hybridly('projects::list-projects', [
            'projects' => ProjectTable::make(),
            'statusColors' => ProjectStatus::colorMap(),
            'statusLabels' => ProjectStatus::labelMap(),
        ]);
    }

    public function create(): HybridlyView
    {
        return hybridly('projects::create-project', [
            'organizations' => \Modules\Organizations\Models\Organization::query()
                ->orderBy('name')
                ->pluck('name', 'id'),
            'statuses' => ProjectStatus::labelMap(),
        ])->base('projects.index');
    }

    public function store(ProjectData $data): RedirectResponse
    {
        Project::create([
            ...$data->toArray(),
            'owner_id' => auth()->id(),
        ]);

        return redirect()->route('projects.index')
            ->with(FlashMessage::Success->value, 'Project created successfully.');
    }

    public function edit(Project $project): HybridlyView
    {
        return hybridly('projects::edit-project', [
            'organizations' => \Modules\Organizations\Models\Organization::query()
                ->orderBy('name')
                ->pluck('name', 'id'),
            'project' => ProjectData::from($project),
            'statuses' => ProjectStatus::labelMap(),
        ])->base('projects.index');
    }

    public function update(ProjectData $data, Project $project): RedirectResponse
    {
        $project->update($data->toArray());

        return redirect()->route('projects.index')
            ->with(FlashMessage::Success->value, 'Project updated successfully.');
    }

    public function destroy(Project $project, DeleteProjectAction $action): RedirectResponse
    {
        $action->execute($project);

        return redirect()->route('projects.index');
    }
}

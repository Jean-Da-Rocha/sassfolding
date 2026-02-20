<?php

declare(strict_types=1);

namespace Modules\Projects\Http\Controllers;

use Hybridly\View\Factory as HybridlyView;
use Illuminate\Http\RedirectResponse;
use Modules\Core\Enums\FlashMessage;
use Modules\Core\Http\Controllers\Controller;
use Modules\Projects\Actions\DeleteTaskAction;
use Modules\Projects\Data\TaskData;
use Modules\Projects\Enums\TaskPriority;
use Modules\Projects\Enums\TaskStatus;
use Modules\Projects\Models\Project;
use Modules\Projects\Models\Task;
use Modules\Projects\Tables\TaskTable;

class TaskController extends Controller
{
    public function index(): HybridlyView
    {
        return hybridly('projects::list-tasks', [
            'priorityColors' => TaskPriority::colorMap(),
            'priorityLabels' => TaskPriority::labelMap(),
            'statusColors' => TaskStatus::colorMap(),
            'statusLabels' => TaskStatus::labelMap(),
            'tasks' => TaskTable::make(),
        ]);
    }

    public function create(): HybridlyView
    {
        return hybridly('projects::create-task', [
            'priorities' => TaskPriority::labelMap(),
            'projects' => Project::query()->orderBy('name')->pluck('name', 'id'),
            'statuses' => TaskStatus::labelMap(),
        ])->base('tasks.index');
    }

    public function store(TaskData $data): RedirectResponse
    {
        Task::create($data->toArray());

        return redirect()->route('tasks.index')
            ->with(FlashMessage::Success->value, 'Task created successfully.');
    }

    public function edit(Task $task): HybridlyView
    {
        return hybridly('projects::edit-task', [
            'priorities' => TaskPriority::labelMap(),
            'projects' => Project::query()->orderBy('name')->pluck('name', 'id'),
            'statuses' => TaskStatus::labelMap(),
            'task' => TaskData::from($task),
        ])->base('tasks.index');
    }

    public function update(TaskData $data, Task $task): RedirectResponse
    {
        $task->update($data->toArray());

        return redirect()->route('tasks.index')
            ->with(FlashMessage::Success->value, 'Task updated successfully.');
    }

    public function destroy(Task $task, DeleteTaskAction $action): RedirectResponse
    {
        $action->execute($task);

        return redirect()->route('tasks.index');
    }
}

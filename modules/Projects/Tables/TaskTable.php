<?php

declare(strict_types=1);

namespace Modules\Projects\Tables;

use Hybridly\Refining\Filters\BooleanFilter;
use Hybridly\Refining\Filters\DateFilter;
use Hybridly\Refining\Filters\Operator;
use Hybridly\Refining\Filters\SelectFilter;
use Hybridly\Refining\Filters\TernaryFilter;
use Hybridly\Refining\Filters\TextFilter;
use Hybridly\Refining\Filters\TimeSuggestion;
use Hybridly\Refining\Group;
use Hybridly\Refining\Sorts\Sort;
use Hybridly\Tables\Actions\BulkAction;
use Hybridly\Tables\Actions\InlineAction;
use Hybridly\Tables\Columns\TextColumn;
use Hybridly\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Enums\FlashMessage;
use Modules\Datatables\Concerns\HasPerPageLimitation;
use Modules\Datatables\Data\ActionMetaData;
use Modules\Projects\Actions\DeleteTaskAction;
use Modules\Projects\Enums\TaskPriority;
use Modules\Projects\Enums\TaskStatus;
use Modules\Projects\Http\Controllers\BulkDeleteTaskController;
use Modules\Projects\Models\Project;
use Modules\Projects\Models\Task;

final class TaskTable extends Table
{
    use HasPerPageLimitation;

    protected string $model = Task::class;

    /** @return array<int, TextColumn> */
    protected function defineColumns(): array
    {
        return [
            TextColumn::make('id')->label('#'),
            TextColumn::make('title')->label('Title'),
            TextColumn::make('status')->label('Status'),
            TextColumn::make('priority')->label('Priority'),
            TextColumn::make('project_name')
                ->label('Project')
                ->transformValueUsing(fn (Task $task) => $task->project->name),
            TextColumn::make('is_pinned')
                ->label('Pinned')
                ->transformValueUsing(fn (Task $task) => $task->is_pinned ? 'Yes' : 'No'),
            TextColumn::make('due_at')
                ->label('Due')
                ->transformValueUsing(fn (Task $task) => $task->due_at?->format('Y-m-d') ?? '-'),
            TextColumn::make('estimated_hours')
                ->label('Est. Hours')
                ->transformValueUsing(fn (Task $task) => $task->estimated_hours !== null ? number_format((float) $task->estimated_hours, 1).'h' : '-'),
        ];
    }

    /** @return Builder<Task> */
    protected function defineQuery(): Builder
    {
        return Task::query()
            ->select(['id', 'title', 'status', 'priority', 'project_id', 'is_pinned', 'due_at', 'estimated_hours', 'completed_at', 'created_at'])
            ->with(['project:id,name']);
    }

    /** @return array<int, BulkAction|InlineAction> */
    protected function defineActions(): array
    {
        return [
            InlineAction::make('edit')
                ->action(fn (Task $task) => redirect()->route('tasks.edit', $task))
                ->metadata(new ActionMetaData(icon: 'i-lucide-square-pen')->toArray()),
            InlineAction::make('delete')
                ->action(fn (Task $task) => app(DeleteTaskAction::class)->execute($task))
                ->metadata(new ActionMetaData(color: FlashMessage::Error, confirm: true, icon: 'i-lucide-trash-2')->toArray()),
            BulkAction::make('delete')
                ->url(BulkDeleteTaskController::class)
                ->metadata(new ActionMetaData(color: FlashMessage::Error, confirm: true, icon: 'i-lucide-trash-2')->toArray()),
        ];
    }

    /** @return array<int, BooleanFilter|DateFilter|Group|SelectFilter|Sort|TernaryFilter> */
    protected function defineRefiners(): array
    {
        return [
            Sort::make('id'),
            Sort::make('title'),
            Sort::make('priority'),
            Sort::make('due_at'),
            Sort::make('estimated_hours'),
            Sort::make('created_at'),
            SelectFilter::make('status')
                ->label('Status')
                ->multiple()
                ->options(TaskStatus::labelMap()),
            SelectFilter::make('priority')
                ->label('Priority')
                ->multiple()
                ->options(TaskPriority::labelMap()),
            SelectFilter::make('project_id')
                ->label('Project')
                ->multiple()
                ->options($this->getProjectOptions()),
            TernaryFilter::make('completed_at')
                ->label('Completed')
                ->labels('Completed', 'Incomplete', 'All')
                ->queries(
                    true: fn (Builder $builder) => $builder->whereNotNull('completed_at'),
                    false: fn (Builder $builder) => $builder->whereNull('completed_at'),
                ),
            BooleanFilter::make('is_pinned')
                ->label('Pinned'),
            DateFilter::make('due_at')
                ->label('Due Date')
                ->suggest([
                    new TimeSuggestion('Today', now()),
                    new TimeSuggestion('Next 7 days', now()->addDays(7)),
                    new TimeSuggestion('Next 30 days', now()->addDays(30)),
                ])
                ->defaultOperator(Operator::BEFORE),
            Group::make([
                TextFilter::make(property: 'title', alias: 'search')->defaultOperator(Operator::CONTAINS),
                TextFilter::make(property: 'description', alias: 'search')->defaultOperator(Operator::CONTAINS),
            ])->booleanMode('or'),
        ];
    }

    /** @return array<string, string> */
    private function getProjectOptions(): array
    {
        /** @var array<string, string> */
        return Project::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }
}

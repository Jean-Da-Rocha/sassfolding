<?php

declare(strict_types=1);

namespace Modules\Projects\Tables;

use Hybridly\Refining\Filters\DateFilter;
use Hybridly\Refining\Filters\Operator;
use Hybridly\Refining\Filters\SelectFilter;
use Hybridly\Refining\Filters\TextFilter;
use Hybridly\Refining\Filters\TimeSuggestion;
use Hybridly\Refining\Filters\TrashedFilter;
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
use Modules\Organizations\Models\Organization;
use Modules\Projects\Actions\DeleteProjectAction;
use Modules\Projects\Enums\ProjectStatus;
use Modules\Projects\Http\Controllers\BulkDeleteProjectController;
use Modules\Projects\Models\Project;

final class ProjectTable extends Table
{
    use HasPerPageLimitation;

    protected string $model = Project::class;

    /** @return array<int, TextColumn> */
    protected function defineColumns(): array
    {
        return [
            TextColumn::make('id')->label('#'),
            TextColumn::make('name')->label('Name'),
            TextColumn::make('status')->label('Status'),
            TextColumn::make('organization_name')
                ->label('Organization')
                ->transformValueUsing(fn (Project $project) => $project->organization->name),
            TextColumn::make('owner_name')
                ->label('Owner')
                ->transformValueUsing(fn (Project $project) => $project->owner->name),
            TextColumn::make('created_at')
                ->label('Created')
                ->transformValueUsing(fn (Project $project) => $project->created_at?->format('Y-m-d H:i:s')),
        ];
    }

    /** @return Builder<Project> */
    protected function defineQuery(): Builder
    {
        return Project::query()
            ->select(['id', 'name', 'status', 'organization_id', 'owner_id', 'created_at'])
            ->with(['organization:id,name', 'owner:id,name']);
    }

    /** @return array<int, BulkAction|InlineAction> */
    protected function defineActions(): array
    {
        return [
            InlineAction::make('edit')
                ->action(fn (Project $project) => redirect()->route('projects.edit', $project))
                ->metadata(new ActionMetaData(icon: 'i-lucide-square-pen')->toArray()),
            InlineAction::make('delete')
                ->action(fn (Project $project) => app(DeleteProjectAction::class)->execute($project))
                ->metadata(new ActionMetaData(color: FlashMessage::Error, confirm: true, icon: 'i-lucide-trash-2')->toArray()),
            BulkAction::make('delete')
                ->url(BulkDeleteProjectController::class)
                ->metadata(new ActionMetaData(color: FlashMessage::Error, confirm: true, icon: 'i-lucide-trash-2')->toArray()),
        ];
    }

    /** @return array<int, DateFilter|Group|SelectFilter|Sort|TrashedFilter> */
    protected function defineRefiners(): array
    {
        return [
            Sort::make('id'),
            Sort::make('name'),
            Sort::make('created_at'),
            SelectFilter::make('status')
                ->label('Status')
                ->multiple()
                ->options(ProjectStatus::labelMap()),
            SelectFilter::make('organization_id')
                ->label('Organization')
                ->multiple()
                ->options($this->getOrganizationOptions()),
            TrashedFilter::make(),
            DateFilter::make('created_at')
                ->label('Created')
                ->suggest([
                    new TimeSuggestion('Today', now()),
                    new TimeSuggestion('Last 7 days', now()->subDays(7)),
                    new TimeSuggestion('Last 30 days', now()->subDays(30)),
                ])
                ->defaultOperator(Operator::AFTER),
            Group::make([
                TextFilter::make(property: 'name', alias: 'search')->defaultOperator(Operator::CONTAINS),
                TextFilter::make(property: 'description', alias: 'search')->defaultOperator(Operator::CONTAINS),
            ])->booleanMode('or'),
        ];
    }

    /** @return array<string, string> */
    private function getOrganizationOptions(): array
    {
        /** @var array<string, string> */
        return Organization::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }
}

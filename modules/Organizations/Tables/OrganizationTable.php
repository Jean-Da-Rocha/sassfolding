<?php

declare(strict_types=1);

namespace Modules\Organizations\Tables;

use Hybridly\Refining\Filters\DateFilter;
use Hybridly\Refining\Filters\Operator;
use Hybridly\Refining\Filters\SelectFilter;
use Hybridly\Refining\Filters\TernaryFilter;
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
use Modules\Organizations\Actions\DeleteOrganizationAction;
use Modules\Organizations\Enums\OrganizationRole;
use Modules\Organizations\Http\Controllers\BulkDeleteOrganizationController;
use Modules\Organizations\Models\Organization;

final class OrganizationTable extends Table
{
    use HasPerPageLimitation;

    protected string $model = Organization::class;

    /** @return array<int, TextColumn> */
    protected function defineColumns(): array
    {
        return [
            TextColumn::make('id')->label('#'),
            TextColumn::make('name')->label('Name'),
            TextColumn::make('slug')->label('Slug'),
            TextColumn::make('is_active')->label('Status'),
            TextColumn::make('created_at')
                ->label('Created')
                ->transformValueUsing(fn (Organization $organization) => $organization->created_at?->format('Y-m-d H:i:s')),
        ];
    }

    /** @return Builder<Organization> */
    protected function defineQuery(): Builder
    {
        return Organization::query()->select(['id', 'name', 'slug', 'is_active', 'created_at']);
    }

    /** @return array<int, BulkAction|InlineAction> */
    protected function defineActions(): array
    {
        return [
            InlineAction::make('edit')
                ->action(fn (Organization $organization) => redirect()->route('organizations.edit', $organization))
                ->metadata(new ActionMetaData(icon: 'i-lucide-square-pen')->toArray()),
            InlineAction::make('delete')
                ->action(fn (Organization $organization) => app(DeleteOrganizationAction::class)->execute($organization))
                ->metadata(new ActionMetaData(color: FlashMessage::Error, confirm: true, icon: 'i-lucide-trash-2')->toArray()),
            BulkAction::make('delete')
                ->url(BulkDeleteOrganizationController::class)
                ->metadata(new ActionMetaData(color: FlashMessage::Error, confirm: true, icon: 'i-lucide-trash-2')->toArray()),
        ];
    }

    /** @return array<int, DateFilter|Group|SelectFilter|Sort|TernaryFilter|TrashedFilter> */
    protected function defineRefiners(): array
    {
        return [
            Sort::make('id'),
            Sort::make('name'),
            Sort::make('created_at'),
            TernaryFilter::make('is_active')
                ->label('Status')
                ->labels('Active', 'Inactive', 'All')
                ->queries(
                    true: fn (Builder $builder) => $builder->where('is_active', true),
                    false: fn (Builder $builder) => $builder->where('is_active', false),
                ),
            TrashedFilter::make(),
            SelectFilter::make('member_role')
                ->label('Member Role')
                ->multiple()
                ->options(collect(OrganizationRole::cases())->mapWithKeys(
                    fn (OrganizationRole $role) => [$role->value => ucfirst($role->value)],
                )->all())
                ->query(fn (Builder $builder, array $selectedOptions) => $builder->whereHas(
                    'users',
                    fn (Builder $query) => $query->whereIn('organization_user.role', array_keys($selectedOptions)),
                )),
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
                TextFilter::make(property: 'slug', alias: 'search')->defaultOperator(Operator::CONTAINS),
            ])->booleanMode('or'),
        ];
    }
}

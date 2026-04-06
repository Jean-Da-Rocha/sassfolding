<?php

declare(strict_types=1);

namespace Modules\Users\Tables;

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
use Modules\Users\Actions\DeleteUserAction;
use Modules\Users\Http\Controllers\BulkDeleteUserController;
use Modules\Users\Http\Controllers\DeactivateUserController;
use Modules\Users\Models\User;

final class UserTable extends Table
{
    use HasPerPageLimitation;

    protected string $model = User::class;

    /** @return array<int, TextColumn> */
    protected function defineColumns(): array
    {
        return [
            TextColumn::make('id')->label('#'),
            TextColumn::make('name')->label('Name'),
            TextColumn::make('email')->label('Email'),
            TextColumn::make('email_verified_at')
                ->label('Verified')
                ->transformValueUsing(fn (User $user) => $user->email_verified_at?->format('Y-m-d H:i:s') ?? '-'),
            TextColumn::make('created_at')
                ->label('Created')
                ->transformValueUsing(fn (User $user) => $user->created_at?->format('Y-m-d H:i:s')),
            TextColumn::make('updated_at')
                ->label('Updated')
                ->transformValueUsing(fn (User $user) => $user->updated_at?->format('Y-m-d H:i:s')),
        ];
    }

    /**
     * @return Builder<User>
     */
    protected function defineQuery(): Builder
    {
        return User::query()->select(['id', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at']);
    }

    /** @return array<int, BulkAction|InlineAction> */
    protected function defineActions(): array
    {
        return [
            InlineAction::make('edit')
                ->action(fn (User $user) => redirect()->route('users.edit', $user))
                ->metadata(new ActionMetaData(icon: 'i-lucide-square-pen')->toArray()),
            InlineAction::make('delete')
                ->action(fn (User $user) => app(DeleteUserAction::class)->execute($user))
                ->metadata(new ActionMetaData(color: FlashMessage::Error, confirm: true, icon: 'i-lucide-trash-2')->toArray()),
            BulkAction::make('delete')
                ->url(BulkDeleteUserController::class)
                ->metadata(new ActionMetaData(color: FlashMessage::Error, confirm: true, icon: 'i-lucide-trash-2')->toArray()),
            BulkAction::make('deactivate')
                ->label('Deactivate')
                ->url(DeactivateUserController::class)
                ->metadata(new ActionMetaData(color: FlashMessage::Warning, confirm: true, icon: 'i-lucide-ban')->toArray()),
        ];
    }

    /** @return array<int, DateFilter|Group|SelectFilter|Sort|TernaryFilter> */
    protected function defineRefiners(): array
    {
        return [
            Sort::make('id'),
            Sort::make('name'),
            Sort::make('email'),
            Sort::make('email_verified_at'),
            Sort::make('created_at'),
            Sort::make('updated_at'),
            TernaryFilter::make('email_verified_at')
                ->label('Verification')
                ->labels('Verified', 'Unverified', 'All')
                ->queries(
                    true: fn (Builder $builder) => $builder->whereNotNull('email_verified_at'),
                    false: fn (Builder $builder) => $builder->whereNull('email_verified_at'),
                ),
            SelectFilter::make('email_domain')
                ->label('Domain')
                ->options($this->getEmailDomains())
                ->query(fn (Builder $builder, string $value) => $builder->where('email', 'like', "%@{$value}")),
            DateFilter::make('created_at')
                ->label('Created')
                ->suggest([
                    new TimeSuggestion('Today', now()),
                    new TimeSuggestion('Last 7 days', now()->subDays(7)),
                    new TimeSuggestion('Last 30 days', now()->subDays(30)),
                    new TimeSuggestion('Last 90 days', now()->subDays(90)),
                ])
                ->defaultOperator(Operator::AFTER),
            Group::make([
                TextFilter::make(property: 'email', alias: 'search')->defaultOperator(Operator::CONTAINS),
                TextFilter::make(property: 'name', alias: 'search')->defaultOperator(Operator::CONTAINS),
            ])->booleanMode('or'),
        ];
    }

    /** @return array<string, string> */
    private function getEmailDomains(): array
    {
        /** @var array<string, string> */
        return User::query()
            ->selectRaw("DISTINCT SUBSTRING_INDEX(email, '@', -1) as domain")
            ->orderBy('domain')
            ->pluck('domain', 'domain')
            ->all();
    }
}

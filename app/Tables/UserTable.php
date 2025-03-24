<?php

declare(strict_types=1);

namespace App\Tables;

use App\Models\User;
use App\Tables\Concerns\HasPerPageLimitation;
use Hybridly\Refining\Filters\Filter;
use Hybridly\Refining\Group;
use Hybridly\Refining\Sorts\Sort;
use Hybridly\Tables\Columns\TextColumn;
use Hybridly\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class UserTable extends Table
{
    use HasPerPageLimitation;

    protected string $model = User::class;

    /** @return array<array-key, TextColumn> */
    protected function defineColumns(): array
    {
        return [
            TextColumn::make('id')->label('#'),
            TextColumn::make('email')->label('Email'),
            TextColumn::make('email_verified_at')
                ->label('Email Verified At')
                ->transformValueUsing(fn (User $user) => $user->email_verified_at?->format('Y-m-d H:i:s')),
            TextColumn::make('name')->label('Name'),
            TextColumn::make('created_at')
                ->label('Creation Date')
                ->transformValueUsing(fn (User $user) => $user->created_at?->format('Y-m-d H:i:s')),
        ];
    }

    /**
     * @return Builder<User>
     */
    protected function defineQuery(): Builder
    {
        return User::query()->select(['id', 'email', 'email_verified_at', 'name', 'created_at']);
    }

    /** @return array<array-key, Group|Sort> */
    protected function defineRefiners(): array
    {
        return [
            Sort::make('id'),
            Sort::make('email'),
            Sort::make('email_verified_at'),
            Sort::make('name'),
            Sort::make('created_at'),
            Group::make([
                Filter::make(property: 'email', alias: 'search')->loose(),
                Filter::make(property: 'name', alias: 'search')->loose(),
            ])->booleanMode('or'),
        ];
    }
}

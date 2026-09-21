<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Module Boundaries Architecture Tests
|--------------------------------------------------------------------------
|
| These tests enforce the module dependency rules for this project:
|
| FOUNDATION MODULES (can be imported by all):
| - Core: Base classes, enums, middleware, UI components
| - Datatables: Reusable table infrastructure
|
| DOMAIN MODULES (expose Model/Data only):
| - Users: Model and Data only, Actions stay encapsulated
|
| FEATURE MODULES (isolated from each other):
| - Authentication
|
| Rules:
| 1. Core depends on nothing (except Laravel/vendor and Users\Data)
| 2. Datatables depends only on Core
| 3. Domain modules expose Model/Data, encapsulate Actions
| 4. Feature modules don't import from each other
|
| When a new feature module is added, list it in the two rules below so that it
| cannot be imported by Core, Datatables, or another feature module.
|
*/

arch('Core module should not depend on other modules except Users Data')
    ->expect('Modules\Core')
    ->not->toUse([
        'Modules\Users\Models',
        'Modules\Users\Actions',
        'Modules\Users\Http',
        'Modules\Users\Tables',
        'Modules\Users\Providers',
        'Modules\Authentication',
        'Modules\Datatables',
    ]);
// Note: Core is allowed to use Modules\Users\Data (UserData) for SharedData

arch('Datatables module should only depend on Core')
    ->expect('Modules\Datatables')
    ->not->toUse([
        'Modules\Users',
        'Modules\Authentication',
    ]);

arch('Users Actions should not be used outside Users module')
    ->expect('Modules\Users\Actions')
    ->toOnlyBeUsedIn('Modules\Users');

arch('Authentication module should not reach into other modules internals')
    ->expect('Modules\Authentication')
    ->not->toUse([
        'Modules\Users\Actions',
        'Modules\Users\Http',
        'Modules\Users\Tables',
    ]);

arch('controllers should extend base controller')
    ->expect('Modules\*\Http\Controllers')
    ->toExtend('Modules\Core\Http\Controllers\Controller');

arch('data objects should extend Spatie Data')
    ->expect('Modules\*\Data')
    ->toExtend('Spatie\LaravelData\Data');

arch('enums should be backed by string or int')
    ->expect('Modules\*\Enums')
    ->toBeEnums();

arch('models should extend Eloquent Model')
    ->expect('Modules\*\Models')
    ->toExtend('Illuminate\Database\Eloquent\Model');

arch('service providers should extend base ServiceProvider')
    ->expect('Modules\*\Providers')
    ->toExtend('Illuminate\Support\ServiceProvider');

arch('no debugging statements left in code')
    ->expect('Modules')
    ->not->toUse(['dd', 'dump', 'ray', 'var_dump', 'print_r']);

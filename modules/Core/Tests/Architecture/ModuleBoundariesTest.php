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
| - Users: Model and Data only (not Actions/Services)
|
| FEATURE MODULES (isolated from each other):
| - Authentication
| - Menus
|
| Rules:
| 1. Core depends on nothing (except Laravel/vendor)
| 2. Datatables depends only on Core
| 3. Users exposes Model/Data, encapsulates Actions
| 4. Feature modules don't import from each other
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
        'Modules\Menus',
        'Modules\Datatables',
    ]);
// Note: Core is allowed to use Modules\Users\Data (UserData) for SharedData

arch('Datatables module should only depend on Core')
    ->expect('Modules\Datatables')
    ->not->toUse([
        'Modules\Users',
        'Modules\Authentication',
        'Modules\Menus',
    ]);

arch('Users Actions should not be used outside Users module')
    ->expect('Modules\Users\Actions')
    ->toOnlyBeUsedIn('Modules\Users');

arch('Authentication module should not depend on feature modules')
    ->expect('Modules\Authentication')
    ->not->toUse([
        'Modules\Menus',
    ]);

arch('Menus module should not depend on feature modules')
    ->expect('Modules\Menus')
    ->not->toUse([
        'Modules\Authentication',
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

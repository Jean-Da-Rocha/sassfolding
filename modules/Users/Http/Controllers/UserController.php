<?php

declare(strict_types=1);

namespace Modules\Users\Http\Controllers;

use Hybridly\View\Factory as HybridlyView;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Core\Enums\FlashMessage;
use Modules\Core\Http\Controllers\Controller;
use Modules\Users\Actions\DeleteUserAction;
use Modules\Users\Actions\Fortify\CreateNewUserAction;
use Modules\Users\Actions\Fortify\UpdateUserProfileInformationAction;
use Modules\Users\Data\UserData;
use Modules\Users\Models\User;
use Modules\Users\Tables\UserTable;

class UserController extends Controller
{
    public function index(): HybridlyView
    {
        return hybridly('users::list-users', ['users' => UserTable::make()]);
    }

    public function create(): HybridlyView
    {
        return hybridly('users::create-user')->base('users.index');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request, CreateNewUserAction $action): RedirectResponse
    {
        $createdUser = $action->create($request->all());

        return back()->with(FlashMessage::Success->value, sprintf('User "%s" successfully created', $createdUser->name));
    }

    public function edit(User $user): HybridlyView
    {
        return hybridly('users::edit-user', ['user' => UserData::from($user)])->base('users.index');
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, User $user, UpdateUserProfileInformationAction $action): RedirectResponse
    {
        $action->update($user, $request->all());

        return back()->with(FlashMessage::Success->value, sprintf('User "%s" successfully updated', $user->name));
    }

    public function destroy(User $user, DeleteUserAction $action): RedirectResponse
    {
        $action->execute($user);

        return back();
    }
}

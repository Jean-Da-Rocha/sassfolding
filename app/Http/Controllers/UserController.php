<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUserAction;
use App\Actions\Fortify\UpdateUserProfileInformationAction;
use App\Data\UserData;
use App\Enums\FlashMessage;
use App\Models\User;
use App\Tables\UserTable;
use Hybridly\View\Factory as HybridlyView;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(): HybridlyView
    {
        return hybridly('users::list-users', ['users' => UserTable::make()]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): HybridlyView
    {
        return hybridly('users::create-user');
    }

    /**
     * Store a newly created user in storage.
     *
     * @throws ValidationException
     */
    public function store(Request $request, CreateNewUserAction $action): RedirectResponse
    {
        $createdUser = $action->create($request->all());

        return to_route(route: 'users.index', status: Response::HTTP_SEE_OTHER)->with(
            FlashMessage::Success->value,
            sprintf('User "%s" successfully created', $createdUser->name),
        );
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): HybridlyView
    {
        return hybridly('users::edit-user', ['user' => UserData::from($user)]);
    }

    /**
     * Update the specified user in storage.
     *
     * @throws ValidationException
     */
    public function update(Request $request, User $user, UpdateUserProfileInformationAction $action): RedirectResponse
    {
        $action->update($user, $request->all());

        return to_route(route: 'users.index', status: Response::HTTP_SEE_OTHER)->with(
            FlashMessage::Success->value,
            sprintf('User "%s" successfully updated', $user->name),
        );
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $username = $user->name;

        $user->delete();

        return to_route(route: 'users.index', status: Response::HTTP_SEE_OTHER)->with(
            FlashMessage::Success->value,
            sprintf('User "%s" successfully deleted', $username),
        );
    }
}

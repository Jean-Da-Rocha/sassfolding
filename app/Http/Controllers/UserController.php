<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Tables\UserTable;
use Hybridly\View\Factory as HybridlyView;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(): HybridlyView
    {
        return hybridly('users::user-listing', ['users' => UserTable::make()]);
    }
}

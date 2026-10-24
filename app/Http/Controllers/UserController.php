<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display the user accounts registry populated via Eloquent ORM.
     */
    public function index(): View
    {
        $users = User::query()->orderBy('id')->get();

        return view('users.index', [
            'title'      => 'User Accounts',
            'activePage' => 'users',
            'users'      => $users,
        ]);
    }
}

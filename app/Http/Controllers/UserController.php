<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUserRequest;
use App\Models\User;
use App\Services\AvatarService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class UserController extends Controller
{
    /**
     * Display the user accounts registry populated via Eloquent ORM.
     */
    public function index(): View
    {
        $users = User::query()->orderBy('id')->get();

        return view('users.index', [
            'title' => 'User Accounts',
            'activePage' => 'users',
            'users' => $users,
        ]);
    }

    /**
     * Display the form for creating a new staff user account.
     */
    public function create(): View
    {
        return view('users.create', [
            'title' => 'New User Account',
            'activePage' => 'users',
            'user' => new User(['role' => 'Staff Member']),
            'roles' => User::ROLES,
        ]);
    }

    /**
     * Validate input, prepare any uploaded avatar, and insert a new user account.
     */
    public function store(SaveUserRequest $request, AvatarService $avatarService): RedirectResponse
    {
        $validated = $request->validated();
        $avatarPath = null;

        if ($request->hasFile('avatar')) {
            $avatarPath = $avatarService->prepareAndStore($request->file('avatar'));
        }

        try {
            User::create([
                'username' => $validated['username'],
                'full_name' => $validated['full_name'],
                'role' => $validated['role'] ?? 'Staff Member',
                'avatar' => $avatarPath,
            ]);
        } catch (Throwable $e) {
            if ($avatarPath !== null) {
                $avatarService->delete($avatarPath);
            }

            throw $e;
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User account created successfully.');
    }

    /**
     * Display the form pre-filled with an existing staff user's data and avatar preview.
     */
    public function edit(User $user): View
    {
        return view('users.edit', [
            'title' => 'Edit User Account',
            'activePage' => 'users',
            'user' => $user,
            'roles' => User::ROLES,
        ]);
    }

    /**
     * Validate input, prepare any uploaded avatar, and update the existing user record.
     */
    public function update(SaveUserRequest $request, User $user, AvatarService $avatarService): RedirectResponse
    {
        $validated = $request->validated();
        $oldAvatar = $user->avatar;
        $newAvatarPath = null;
        $removeRequested = $request->boolean('remove_avatar');

        if ($request->hasFile('avatar')) {
            $newAvatarPath = $avatarService->prepareAndStore($request->file('avatar'));
        }

        $avatarToPersist = $newAvatarPath ?? ($removeRequested ? null : $oldAvatar);

        try {
            $user->update([
                'username' => $validated['username'],
                'full_name' => $validated['full_name'],
                'role' => $validated['role'] ?? $user->role ?? 'Staff Member',
                'avatar' => $avatarToPersist,
            ]);
        } catch (Throwable $e) {
            if ($newAvatarPath !== null) {
                $avatarService->delete($newAvatarPath);
            }

            throw $e;
        }

        if ($oldAvatar !== null && ($newAvatarPath !== null || $removeRequested)) {
            $avatarService->delete($oldAvatar);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User account updated successfully.');
    }
}

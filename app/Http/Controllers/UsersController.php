<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get();
        $queries = DB::getQueryLog();
        Log::channel('Users')->info("Users index queries: " . json_encode($queries));
        return View::make('users-list', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $queries = DB::getQueryLog();
        Log::channel('Users')->info("Users create queries: " . json_encode($queries));
        return View::make('user-form', ['roles' => $roles, 'permissions' => $permissions, 'user' => new User()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role'  => 'required',
            'permissions' => [
                'required_if:role,Employee',
                'array',
                'min:1',
            ],
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'), // Consider generating a random password or using a secure input
        ]);

        // Assign role to the user
        $user->assignRole($request->role);

        // Assign permissions to the user
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        $queries = DB::getQueryLog();
        Log::channel('Users')->info("Users store queries: " . json_encode($queries));
        // Redirect back to the users list view with a success message
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Code to show user details can be added here if needed
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $roles = Role::all();
        $permissions = Permission::all();

        $user = User::with('roles', 'permissions')->findOrFail($id);

        $queries = DB::getQueryLog();
        Log::channel('Users')->info("Users edit queries: " . json_encode($queries));

        return View::make('user-form', [
            'roles' => $roles,
            'permissions' => $permissions,
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'role'  => 'required',
            'permissions' => [
                'required_if:role,Employee',
                'array',
                'min:1',
            ],
        ]);

        $user = User::findOrFail($id);

        // Update user details
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Update role
        $user->syncRoles([$request->role]);

        // Update permissions
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        } else {
            $user->permissions()->detach();
        }
        $queries = DB::getQueryLog();
        Log::channel('Users')->info("Users update queries: " . json_encode($queries));
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Detach roles and delete the user
        $user->syncRoles([]);
        $user->delete();
        $queries = DB::getQueryLog();
        Log::channel('Users')->info("users destroy queries: " . json_encode($queries));
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}

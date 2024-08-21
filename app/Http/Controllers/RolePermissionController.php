<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $queries = DB::getQueryLog();
        Log::channel('Role-Permissions')->info("Role permission index queries: " . json_encode($queries));
        return View::make('role-permissions', ['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::with('permissions')->find($id);
        $permissions = Permission::get();
        $queries = DB::getQueryLog();
        Log::channel('Role-Permissions')->info("Role permission edit queries: " . json_encode($queries));
        return View::make('role_permission_form', ['role' => $role, 'permissions' => $permissions]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role'  => 'required',
            'permissions' => [
                'required',
                'array',
                'min:1',
            ],
        ]);

        $role = Role::findOrFail($id);

        // Update role details
        $role->name = $request->name;
        $role->save();

        // Update permissions
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->permissions()->detach();
        }

        $queries = DB::getQueryLog();
        Log::channel('Role-Permissions')->info("Role permission update queries: " . json_encode($queries));
        return redirect()->route('role-permissions.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

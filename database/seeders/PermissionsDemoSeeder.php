<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsDemoSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'create']);
        Permission::create(['name' => 'read']);
        Permission::create(['name' => 'edit']);
        Permission::create(['name' => 'delete']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'Employee']);
        $role1->givePermissionTo('create');
        $role1->givePermissionTo('read');

        $role2 = Role::create(['name' => 'Admin']);
        $role2->givePermissionTo('create');
        $role2->givePermissionTo('read');
        $role2->givePermissionTo('edit');

        $role3 = Role::create(['name' => 'Super-Admin']);
        $role3->givePermissionTo('create');
        $role3->givePermissionTo('read');
        $role3->givePermissionTo('edit');
        $role3->givePermissionTo('delete');
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Example User',
            'email' => 'test@example.com',
        ]);
        $user->assignRole($role1);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example Admin User',
            'email' => 'admin@example.com',
        ]);
        $user->assignRole($role2);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example Super-Admin User',
            'email' => 'superadmin@example.com',
        ]);
        $user->assignRole($role3);
    }
}

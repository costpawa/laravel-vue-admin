<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Admin role
        // AuthServiceProvider içindeki Gate::before rule; kodu ile tüm permissionlara otomatik erişim sağlar.
        $role1 = Role::create([
            'name'          => 'admin',
            'display_name'  => 'Admin',
            'color'         => 'EF4444FF', // tw-red-500
        ]);

        // All permissions
        $permissions = [
            // dashboard permissions
            'Show Dashboard',

            // users permissions
            'List Users',
            'Create User',
            'Show User',
            'Update User',
            'Delete User',

             // roles permissions
            'List Roles',
            'Create Role',
            'Show Role',
            'Update Role',
            'Delete Role',

             // permissions permissions
            'List Permissions',
            'Create Permission',
            'Show Permission',
            'Update Permission',
            'Delete Permission',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name'          => slugify($permission),
                'display_name'  => $permission,
            ]);
        }

        $user = User::where('id',1)->first();
        $user->assignRole($role1);

        // -------------------------------------------------------------------------------------------------------------

        // Editor role
        $role2 = Role::create([
            'name'          => 'editor',
            'display_name'  => 'Editor',
            'color'         => '10B981FF', // tw-green-500
        ]);

        // Editor permissions
        $permissions = [
            // dashboard permissions
            'Show Dashboard',

            // users permissions
            'List Users',
            'Create User',
            'Show User',
            'Update User',
            'Delete User',

            // roles permissions
           'List Roles',
           'Show Role',
        ];

        foreach ($permissions as $permission) {
            $role2->givePermissionTo(slugify($permission));
        }

        $user = User::where('id',2)->first();
        $user->assignRole($role2);

        // -------------------------------------------------------------------------------------------------------------

        // Member role
        $role3 = Role::create([
            'name'          => 'member',
            'display_name'  => 'Member',
            'color'         => '3B82F6FF', // tw-blue-500
        ]);

        // Member permissions
        $permissions = [
            // dashboard permissions
            'Show Dashboard',
            
            // users permissions
            'List Users',
            'Show User',
        ];

        foreach ($permissions as $permission) {
            $role3->givePermissionTo(slugify($permission));
        }

        $user = User::where('id',3)->first();
        $user->assignRole($role3);
    }
}

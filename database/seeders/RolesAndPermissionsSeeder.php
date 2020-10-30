<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $moderator_permissions = [
            Permission::create(['name' => 'see dashboard']),
            Permission::create(['name' => 'see categories list']),
            Permission::create(['name' => 'see one category']),
            Permission::create(['name' => 'create category']),
            Permission::create(['name' => 'edit category']),
            Permission::create(['name' => 'see products list']),
            Permission::create(['name' => 'see one product']),
            Permission::create(['name' => 'create product']),
            Permission::create(['name' => 'edit product']),
            Permission::create(['name' => 'update product main image']),
            Permission::create(['name' => 'remove product images']),
            Permission::create(['name' => 'see orders list']),
            Permission::create(['name' => 'see one order']),
            Permission::create(['name' => 'accept one order']),
            Permission::create(['name' => 'cancel one order']),
            Permission::create(['name' => 'see users list']),
            Permission::create(['name' => 'see one user']),
            Permission::create(['name' => 'change user role']),
        ];

        $admin_permissions = [
            Permission::create(['name' => 'delete category']),
            Permission::create(['name' => 'delete product']),
            Permission::create(['name' => 'delete order']),
            Permission::create(['name' => 'delete user']),
        ];

        $userRole = Role::create(['name' => 'user']);

        $moderator_role = Role::create(['name' => 'moderator']);
        $moderator_role->syncPermissions($moderator_permissions);

        $admin_role = Role::create(['name' => 'admin']);
        $admin_role->syncPermissions(array_merge($moderator_permissions, $admin_permissions));

    }
}
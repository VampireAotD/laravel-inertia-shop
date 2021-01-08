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
            Permission::create(['name' => 'view dashboard']),
            Permission::create(['name' => 'view categories list']),
            Permission::create(['name' => 'view category']),
            Permission::create(['name' => 'create category']),
            Permission::create(['name' => 'edit category']),
            Permission::create(['name' => 'view products list']),
            Permission::create(['name' => 'view product']),
            Permission::create(['name' => 'create product']),
            Permission::create(['name' => 'edit product']),
            Permission::create(['name' => 'update product main image']),
            Permission::create(['name' => 'remove product image']),
            Permission::create(['name' => 'view orders list']),
            Permission::create(['name' => 'view order']),
            Permission::create(['name' => 'accept order']),
            Permission::create(['name' => 'cancel order']),
            Permission::create(['name' => 'view users list']),
            Permission::create(['name' => 'view user'])
        ];

        $admin_permissions = [
            Permission::create(['name' => 'change user role']),
            Permission::create(['name' => 'delete category']),
            Permission::create(['name' => 'delete product']),
            Permission::create(['name' => 'delete order']),
            Permission::create(['name' => 'delete user']),
            Permission::create(['name' => 'create permission']),
            Permission::create(['name' => 'edit permission']),
            Permission::create(['name' => 'view permission']),
            Permission::create(['name' => 'delete permission']),
            Permission::create(['name' => 'create role']),
            Permission::create(['name' => 'edit role']),
            Permission::create(['name' => 'view role']),
            Permission::create(['name' => 'delete role']),
        ];

        Role::create(['name' => 'user']);

        $moderator_role = Role::create(['name' => 'moderator']);
        $moderator_role->syncPermissions($moderator_permissions);

        $admin_role = Role::create(['name' => 'admin']);
        $admin_role->syncPermissions(array_merge($moderator_permissions, $admin_permissions));

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // إنشاء الصلاحيات الأساسية
        $permissions = [
            'show_users',
            'edit_users',
            'delete_users',
            'admin_users'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // إنشاء دور المدير وإعطائه كل الصلاحيات
        $adminRole = Role::create(['name' => 'super-admin']);
        $adminRole->givePermissionTo($permissions);
    }
}
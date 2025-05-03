<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // إنشاء الأدوار
        $adminRole = Role::create(['name' => 'admin']);
        $employeeRole = Role::create(['name' => 'employee']);
        $customerRole = Role::create(['name' => 'customer']);

        // إنشاء الصلاحيات
        $permissions = [
            'manage_products',    // إدارة المنتجات
            'manage_customers',   // إدارة العملاء
            'add_credit',        // إضافة رصيد
            'make_purchase',      // إجراء عملية شراء
            'view_purchases',     // عرض المشتريات
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // إعطاء الصلاحيات للأدوار
        $adminRole->givePermissionTo(Permission::all());
        $employeeRole->givePermissionTo(['manage_products', 'manage_customers', 'add_credit']);
        $customerRole->givePermissionTo(['make_purchase', 'view_purchases']);
    }
} 
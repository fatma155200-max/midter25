<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // إنشاء دور المدير إذا لم يكن موجوداً
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // إنشاء المدير الأول
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin123'),
            'credit' => 0
        ]);

        // تعيين دور المدير
        $admin->assignRole('admin');
    }
}
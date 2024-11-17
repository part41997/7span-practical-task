<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** Check if the admin role exists
         * NOTE : If we have multiple roles and permissions then we will preferred spatie/laravel-permission 
        */
        $role = Role::whereName('admin')->firstOrFail();
        
        // Create the admin user
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'mobile_number' => '8989898989',
            'role_id' => $role->id ?? null,
            'password' => Hash::make('password'), // You can change the password here
        ]);
    }
}

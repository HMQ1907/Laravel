<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tạo dữ liệu cho user thứ nhất
        DB::table('users')->insert([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'verify_email' => Str::random(10),
            'is_active' => 1,
            'is_delete' => 0,
            'group_role' => 'user',
            'last_login_at' => now(),
            'last_login_ip' => '127.0.0.1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo dữ liệu cho user thứ hai
        DB::table('users')->insert([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'verify_email' => Str::random(10),
            'is_active' => 1,
            'is_delete' => 0,
            'group_role' => 'user',
            'last_login_at' => now(),
            'last_login_ip' => '127.0.0.1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Đỗ Tuấn Phong',
            'email' => 'Phongdo789@gmail.com',
            'email_verified_at' => now(),
            'password' => md5('123456'),
            'level' => 'Admin',
            'remember_token' => Str::random(10),
        ]);
        User::factory()->count(100)->create();
    }
}

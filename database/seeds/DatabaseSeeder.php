<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $check = User::where('user_id', 'super_admin')->first();

        if (!$check)
            DB::table('users')->insert([
                'name' => 'super_admin ',
                'role' => 'super_admin',
                'email' => 'super_admin@gmail.com',
                'user_id' => 'super_admin',
                'status' => 'active',
                'password' => Hash::make('123456'),
                'password_user' => '123456',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
}

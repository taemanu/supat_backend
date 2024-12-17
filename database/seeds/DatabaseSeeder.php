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


            DB::table('master_garage')->insert([
                'project_name' => 'โรงรถ - 01',
                'project_code' => 'Garage_001',
                'min_price' => 10000,
                'max_price' => 20000,
                'img' => 'https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcQtAj4Hoc-BaSRFLXLKxDuviqKntGqZ5GX95Ohx8mAl4duQ3_yE',
                'type_steel' => json_encode(['เหล็กธรรมดา','เหล็กกัลวาไนซ์']),
                'thickness_steel' => json_encode(['1.2','1.4','1.6','1.8','2.0']),
                'type_sheet' => json_encode(['พี.ยู.โฟม','เมทัลชีท','กระเบื้อง']),
                'note' => 'ขนาดโรงรถ 4x6 ม. พื้นที่ใช้สอย 24 ตรม.',
            ]);
    }
}

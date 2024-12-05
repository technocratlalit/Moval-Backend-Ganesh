<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_super_admin')->insert([
            'id' => 1,
            'name' => 'Super Admin mv',
            'email' => 'technocratlalit@gmail.com',
            'password' => Hash::make('12345678'),
            'address' => 'Super Admin Address',
            'mobile_no' => '6547823596',
            'otp_sent' => '9348',
            'expiry_time' => '2022-10-16 00:24:48',
            'status' => '1',
            'created_at' => '2022-10-15 19:51:42',
            'updated_at' => '2023-09-30 17:07:39',
        ]);

        DB::table('tbl_ms_super_admin')->insert([
            'id' => 1,
            'name' => 'Super Admin mv',
            'email' => 'technocratlalit@gmail.com',
            'password' => Hash::make('12345678'),
            'address' => 'Super Admin Address',
            'mobile_no' => '6547823596',
            'otp_sent' => '9348',
            'expiry_time' => '2022-10-16 00:24:48',
            'status' => '1',
            'created_at' => '2022-10-15 19:51:42',
            'updated_at' => '2023-09-30 17:07:39',
        ]);
    }
}

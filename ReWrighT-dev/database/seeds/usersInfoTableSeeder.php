<?php

use App\user_info;
use Illuminate\Database\Seeder;

class usersInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * note: employee and student id must be in another table
     * @return void
     */
    public function run()
    {
        $table = 'users_info';
        DB::table($table)->delete();
        DB::table($table)->insert([
        	"user_id"=>1,
        	"profile"=>'1_profile_1.jpg',
        	"banner"=>'1_banner_1.jpg',
        	"first_name"=>'Gabriel Luis',
        	"middle_name"=>'Gatmaitan',
        	"last_name"=>'Borjal',
        	"suffix_name"=>NULL,
        	"sex"=>'MALE',
        	"perm_address"=>'blk.4 lt1 phase 1c, Rambutan st cor Santol drv Palmera Homes Q.C',
        	"tempo_address"=>'10066 Mt. Data st Umali subd, Brgy. Batong Malake, Los Banos, Laguna',
        	"office_address"=>NULL,
        ]);
    }
}

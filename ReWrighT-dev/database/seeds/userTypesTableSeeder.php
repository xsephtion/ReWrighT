<?php

use App\user_types;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class userTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = 'user_types';
        DB::table($table)->delete();
        user_types::create([
    		'type'=>0,
    		'description'=>'Student'
		]);
    	user_types::create([
    		'type'=>1,
    		'description'=>'Professor'
		]);
    }
}

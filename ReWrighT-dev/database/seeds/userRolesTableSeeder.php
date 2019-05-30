<?php

use App\user_roles;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class userRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = 'user_roles';
        DB::table($table)->delete();
        user_roles::create([
    		'type'=>0,
    		'description'=>'Project Manager'
		]);
		user_roles::create([
    		'type'=>1,
    		'description'=>'Team Leader'
		]);
		user_roles::create([
    		'type'=>2,
    		'description'=>'Developer'
		]);
        user_roles::create([
    		'type'=>3,
    		'description'=>'Professor'
		]);

    }
}

<?php
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class usersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //admin
    	$table = 'users';
        DB::table($table)->delete();
        $id = DB::table($table)->insertGetId([
            'username'=>'gborjal01',
        	'email'=> 'gborjal01@gmail.com',
        	'password'=> Hash::make('59626288'),
            'activation_code' => '59626288',
            'user_types'=> 0
        ]);//admin
        DB::table('users_info')->insert([
            "user_id"=>$id,
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

        $id2 = DB::table('projects')->insertGetId([
            'owner_id' => 1,
            'text' => 'Gabriel Luis Borjal',
            'size' => 10,
            'active' => True,
        ]);
        DB::table('developers')->insert([
            'project_id'    => $id2,
            'user_id'       => $id,
            'role'          => 0,
        ]);

        App\discussion::create([
            'project_id'    => $id2,
            'user_id'       => $id,
            'title'         => "Class not found error after renaming migration, despite dump-autoload and clearing cache",
            'text'          => "<[!text::trial this is just a  trial!]> <[!img!]> <[!caption::just another caption.!]>",
            'image'         => "58cb3b0924ef84.69628613.jpg",
            'priority'      => 5,
            'active'        => True,

        ]);
        App\discussion_notif::create([
            'discussion_id' => 1,
            'user_id'       => 1,
            'seen'          => False,
            'read'          => False
        ]);
        
        App\discussion_comment::create([
            'discussion_id' => 1,
            'user_id'       => $id,
            'text'          => 'found the answer. thanks.',
            'image'         => NULL,
            'upvote'        => NULL,
            'downvote'      => NULL,
        ]);
        

        //physician
        $table = 'users';
        DB::table($table)->delete();
        $id = DB::table($table)->insertGetId([
            'username'=>'hborjal91',
            'email'=> 'hborjal91@gmail.com',
            'password'=> Hash::make('sy16dl3l'),
            'activation_code' => 'sy16dl3l',
            'user_types'=> 1
        ]);//admin
        DB::table('users_info')->insert([
            "user_id"=>$id,
            "profile"=>'1_profile_1.jpg',
            "banner"=>'1_banner_1.jpg',
            "first_name"=>'Helena Louise',
            "middle_name"=>'Gatmaitan',
            "last_name"=>'Borjal',
            "suffix_name"=>NULL,
            "sex"=>'MALE',
            "perm_address"=>'blk.4 lt1 phase 1c, Rambutan st cor Santol drv Palmera Homes Q.C',
            "tempo_address"=>'10066 Mt. Data st Umali subd, Brgy. Batong Malake, Los Banos, Laguna',
            "office_address"=>NULL,
        ]);

        $id2 = DB::table('projects')->insertGetId([
            'owner_id' => 1,
            'text' => 'Helena Louise Borjal',
            'size' => 10,
            'active' => True,
        ]);
        DB::table('developers')->insert([
            'project_id'    => $id2,
            'user_id'       => $id,
            'role'          => 0,
        ]);

        App\discussion::create([
            'project_id'    => $id2,
            'user_id'       => $id,
            'title'         => "Class not found error after renaming migration, despite dump-autoload and clearing cache",
            'text'          => "<[!text::trial this is just a  trial!]> <[!img!]> <[!caption::just another caption.!]>",
            'image'         => "58cb3b0924ef84.69628613.jpg",
            'priority'      => 5,
            'active'        => True,

        ]);
        App\discussion_notif::create([
            'discussion_id' => 2,
            'user_id'       => $id,
            'seen'          => False,
            'read'          => False
        ]);
        
        App\discussion_comment::create([
            'discussion_id' => 2,
            'user_id'       => $id,
            'text'          => 'found the answer. thanks.',
            'image'         => NULL,
            'upvote'        => NULL,
            'downvote'      => NULL,
        ]);
    }
}

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
        
    	$table = 'users';
        DB::table($table)->delete();

        //admin
        $admin_id = DB::table($table)->insertGetId([
            'username'=>'gborjal01',
        	'email'=> 'gborjal01@gmail.com',
        	'password'=> Hash::make('59626288'),
            'activation_code' => '59626288',
            'user_types'=> 0
        ]);
        DB::table('users_info')->insert([
            "user_id"=>$admin_id,
            "profile"=>'1_profile_1.jpg',
            "banner"=>'1_banner_1.jpg',
            "first_name"=>'Gabriel Luis',
            "middle_name"=>'Gatmaitan',
            "last_name"=>'Borjal',
            "suffix_name"=>NULL,
            "sex"=>'MALE',
            "perm_address"=>'blk.4 lt1 phase 1c, Rambutan st cor Santol drv Palmera Homes Q.C',
            "tempo_address"=>'10066 Mt. Data st Umali subd, Brgy. Batong Malake, Los Banos, Laguna',
            "office_address"=>NULL
        ]);

        $admin_project_id = DB::table('projects')->insertGetId([
            'owner_id' => 1,
            'text' => 'Admin',
            'size' => 1000,
            'active' => True
        ]);
        DB::table('developers')->insert([
            'project_id'    => $admin_project_id,
            'user_id'       => $admin_id,
            'role'          => 0
        ]);

        $discussion_id = DB::table('discussions_board')->insertGetId([
            'project_id'    => $admin_project_id,
            'user_id'       => $admin_id,
            'title'         => "Patient group not found error after renaming group, despite recreating",
            'text'          => "<[!text::trial this is just a  trial!]> <[!img!]> <[!caption::just another caption.!]>",
            'image'         => "58cb3b0924ef84.69628613.jpg",
            'priority'      => 1,
            'active'        => True

        ]);
        App\discussion_notif::create([
            'discussion_id' => $discussion_id,
            'user_id'       => $admin_id,
            'seen'          => False,
            'read'          => False
        ]);
        
        App\discussion_comment::create([
            'discussion_id' => $discussion_id,
            'user_id'       => $admin_id,
            'text'          => 'found the answer. thanks.',
            'image'         => NULL,
            'upvote'        => NULL,
            'downvote'      => NULL
        ]);
        

        //physician
        $physician_id = DB::table('users')->insertGetId([
            'username'=>'hborjal91',
            'email'=> 'hborjal91@gmail.com',
            'password'=> Hash::make('sy16dl3l'),
            'activation_code' => 'sy16dl3l',
            'user_types'=> 1
        ]);//physician
        DB::table('users_info')->insert([
            "user_id"=>$physician_id,
            "profile"=>'1_profile_1.jpg',
            "banner"=>'1_banner_1.jpg',
            "first_name"=>'Helena Louise',
            "middle_name"=>'Gatmaitan',
            "last_name"=>'Borjal',
            "suffix_name"=>NULL,
            "sex"=>'FEMALE',
            "perm_address"=>'blk.4 lt1 phase 1c, Rambutan st cor Santol drv Palmera Homes Q.C',
            "tempo_address"=>'10066 Mt. Data st Umali subd, Brgy. Batong Malake, Los Banos, Laguna',
            "office_address"=>NULL
        ]);
        
        
        $physician_project_id = DB::table('projects')->insertGetId([
            'owner_id' => $physician_id,
            'text' => 'Helena Louise Borjal',
            'size' => 10,
            'active' => True
        ]);
        //PT joins admin's group
        DB::table('developers')->insert([
            'project_id'    => $admin_project_id,
            'user_id'       => $physician_id,
            'role'          => 1
        ]);
        DB::table('developers')->insert([
            'project_id'    => $physician_project_id,
            'user_id'       => $physician_id,
            'role'          => 1
        ]);

        $discussion_id = DB::table('discussions_board')->insertGetId([
            'project_id'    => $physician_project_id,
            'user_id'       => $physician_id,
            'title'         => "Tendernes in the carpus",
            'text'          => "<[!text::Wrist tenderness!]> <[!img!]> <[!caption::just another caption.!]>",
            'image'         => "52cb3b0924ef84.69628613.jpg",
            'priority'      => 5,
            'active'        => True

        ]);
        App\discussion_notif::create([
            'discussion_id' => $discussion_id,
            'user_id'       => $physician_id,
            'seen'          => False,
            'read'          => False
        ]);
        /*App\discussion_notif::create([
            'discussion_id' => $discussion_id,
            'user_id'       => 3,
            'seen'          => False,
            'read'          => False
        ]);*/
        
        App\discussion_comment::create([
            'discussion_id' => $discussion_id,
            'user_id'       => $physician_id,
            'text'          => 'Do the recommended exercise for 10 days.',
            'image'         => NULL,
            'upvote'        => NULL,
            'downvote'      => NULL
        ]);

        //patient
        $patient_id = DB::table('users')->insertGetId([
            'username'=>'lmborjal',
            'email'=> 'lmborjal@yahoo.com',
            'password'=> Hash::make('123456'),
            'activation_code' => '123456',
            'user_types'=> 2
        ]);
        DB::table('users_info')->insert([
            "user_id"=>$patient_id,
            "profile"=>'1_profile_1.jpg',
            "banner"=>'1_banner_1.jpg',
            "first_name"=>'Luis',
            "middle_name"=>'Minsalan',
            "last_name"=>'Borjal',
            "suffix_name"=>NULL,
            "sex"=>'MALE',
            "perm_address"=>'blk.4 lt1 phase 1c, Rambutan st cor Santol drv Palmera Homes Q.C',
            "tempo_address"=>NULL,
            "office_address"=>NULL
        ]);

        DB::table('developers')->insert([
            'project_id'    => $physician_project_id,
            'user_id'       => $patient_id,
            'role'          => 2
        ]);

        /*App\discussion_notif::create([
            'discussion_id' => $discussion_id,
            'user_id'       => $patient_id,
            'seen'          => False,
            'read'          => False
        ]);*/
        
        App\discussion_comment::create([
            'discussion_id' => $discussion_id,
            'user_id'       => $patient_id,
            'text'          => 'Thanks for the recommendation.',
            'image'         => NULL,
            'upvote'        => NULL,
            'downvote'      => NULL
        ]);
        $tag_id = DB::table('tag_info')->insertGetId([
            'desc'=>'Wrist',
            'active'=> True
        ]);

        $id3 = DB::table('tasks_board')->insertGetId([
            'project_id'    => $physician_project_id,
            'user_id'       => $physician_id,
            'title'         => "Tendernes in the carpus",
            'text'          => 'Do the recommended exercise for 10 days.',
            'exer_data'     => '5e1b03ceada9a9.20154455.json.lz',
            'active'        => True
        ]);

        App\tags::create([
            'tasks_id'      => $id3,
            'tag_info_id'   => $tag_id,
            'active'        => True
        ]);
        App\assigned_to::create([
            'task_id'       => $id3,
            'user_id'       => $patient_id,
            'status'        => False,
            'active'        => True
        ]);
        
    }
}

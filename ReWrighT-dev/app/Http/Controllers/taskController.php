<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

use App\User;
use App\user_info;
use App\project;
use App\developers;
use App\discussion;
use App\discussion_comment;
use App\discussion_notif;
use App\discussion_vote;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class taskController extends Controller
{
    /**
     *  Get discussions
     *
     *  @return array
     */
    public function getTasks(Request $request){
        if ( $request->ajax() ) {
            $project_id = $request->get('project');
            $all = $request->get('all');
            
            if($all == "true"){
                $query = task_notif::select('task_notifs.id as notif_id','task_notifs.user_id as notif_user','task_notifs.seen as seen','task_notifs.read as read','tasks_board.project_id','tasks_board.id as task_id','tasks_board.user_id as creator_user','tasks_board.title as title','tasks_board.text as text','tasks_board.image as image','tasks_board.priority as priority','tasks_board.active as active','tasks_board.updated_at','tasks_board.created_at')
                        ->where('tasks_board.project_id','=',$project_id)
                        ->leftjoin('tasks_board','task_notifs.task_id','=','tasks_board.id')
                        ->orderBy('tasks_board.updated_at','tasks_board.created_at')
                        ->get();
            }else{
                $query = task_notif::select('task_notifs.id as notif_id','task_notifs.user_id as notif_user','task_notifs.seen as seen','task_notifs.read as read','tasks_board.project_id','tasks_board.id as task_id','tasks_board.user_id as creator_user','tasks_board.title as title','tasks_board.text as text','tasks_board.image as image','tasks_board.priority as priority','tasks_board.active as active','tasks_board.updated_at','tasks_board.created_at')
                        ->leftjoin('tasks_board','task_notifs.discussion_id','=','tasks_board.id')
                        ->where('task_notifs.user_id','=',Auth::user()->id)
                        ->where('task_notifs.seen','=',false)
                        ->orderBy('tasks_board.updated_at','tasks_board.created_at')
                        ->get();
            }

            $set=array();
            foreach($query as $task){
                $user = User::find(intval($task->creator_user))->userInformation; //get info of creator
                $res = [ 
                        'task_id' => $task->task_id,
                        'notif_id'      => $task->notif_id,
                        'profile'       => $user->profile,
                        'first_name'    => $user->first_name,
                        'last_name'     => $user->last_name,
                        'disc_id'       => $task->id,
                        'disc_title'    => substr($task->title,0,50),
                        'disc_text'     => substr($task->text,0,80),
                        'updated_at'    => $task->updated_at,
                        'read'          => $task->read,
                        'seen'          => $task->seen,
                        ];
                array_push($set,$res);
                if($discussion->notif_id == NULL){
                        DB::table('task_notifs')->insert(
                                ['task_id'=>$discussion->discussion_id,'user_id'=>Auth::user()->id,'seen'=>true]
                            );
                    }else{
                        DB::table('task_notifs')
                            ->where('id','=',$discussion->notif_id)
                            ->update(['seen'=>true]);
                }
            }
            
            $response = [
                'status'        => 'success',
                'discussions'   => $set
            ];
            return response()
                ->json($response)
                ->setCallback($request->input('callback'));
        }
        return redirect()->route('dashboard');
    }
    /**
     *  join Projects
     *
     *  @return array
     */
    public function openTask(Request $request,$id){
        
        return view('task');
        /*$projects = Auth::user()->projects;
        foreach ($projects as $project) {
            if($id == $project->project_id){
                $response = [
                'success'       => true,
                'message'       => "already in the project."
            ];
            return response()
                    ->json($response);
                    break;
            }
        }
        developer::create([
            'project_id'    => $id,
            'user_id'       => Auth::user()->id,
            'role'          => Auth::user()->user_type
            ]);
        */
    }
    /**
     *  join Projects
     *
     *  @return array
     */
    public function openTasks(Request $request){
        
        return redirect()->route('dashboard');
        /*$projects = Auth::user()->projects;
        foreach ($projects as $project) {
            if($id == $project->project_id){
                $response = [
                'success'       => true,
                'message'       => "already in the project."
            ];
            return response()
                    ->json($response);
                    break;
            }
        }
        developer::create([
            'project_id'    => $id,
            'user_id'       => Auth::user()->id,
            'role'          => Auth::user()->user_type
            ]);
        */
    }
}

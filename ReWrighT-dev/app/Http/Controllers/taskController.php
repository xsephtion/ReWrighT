<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use Validator;
use Storage;
use Auth;
use DB;

use App\User;
use App\user_info;
use App\project;
use App\developer;
use App\discussion;
use App\discussion_comment;
use App\discussion_notif;
use App\discussion_vote;
use App\task;
use App\task_exer_data;
use App\exer_data;
use App\assigned_to;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class taskController extends Controller
{
    /**
     *  Get Task
     *
     *  @return array
     */
    public function getTask(Request $request){
        if ( $request->ajax() ) {
            $err = array();
            $task_id = intval($request->get('id'));
            $task_exer_data = task_exer_data::where('id','=',$task_id)->first();
            $assign_to = assigned_to::where([
                                                ['id','=',$task_exer_data->task_assignment_id],
                                            ])
                                    ->first();
            
            $task = task::where([
                                    ['id','=',$assign_to->task_id]
                                ])
                            ->first();

            if(is_null($task)){
                $response = [
                        'status'  => 'fail',
                        'task'   => null,
                        'error'   => ['Not Found.']
                    ];
            }else{
                $task_info = [
                    'task_id'           => $task->id,
                    'task_title'        => substr($task->title,0,50),
                    'task_text'         => $task->text,
                    'task_images'       => $task->image,
                    'created_at'        => $task->created_at,
                    'updated_at'        => $task->updated_at
                ];
            }
            $response = [
                        'status'  => 'success',
                        'task'   => $task_info,
                        'error'   => $err
                    ];

            return response()
                ->json($response)
                ->setCallback($request->input('callback'));
        }
    }
    /**
     *  Get Tasks
     *
     *  @return array
     */
    public function getTasks(Request $request){
        //if ( $request->ajax() ) {
            $err = array();
            $set=array();
            $project_id = $request->get('project');
            $all = $request->get('all');
            
            if(Auth::user()->user_types === 1){
                if($all == "true"){
                    $tasks = task::where('tasks_board.user_id','=',Auth::user()->id)    //created by user
                                    ->where('tasks_board.active','=','1')
                                    ->get();
                    
                    if(sizeof($tasks) == 0){
                        $response['status'] = 'fail';
                        $response['message']    = 'No tasks yet.';
                    }else{
                        foreach($tasks as $task){
                                                      //per task
                            
                            $creator_dtls = User::find(intval($task->user_id))->userInformation; //get info of creator
                            $creator_info =[
                                        'profile'           => $creator_dtls->profile,
                                        'first_name'        => $creator_dtls->first_name,
                                        'last_name'         => $creator_dtls->last_name,
                                    ];
                            
                            $task_info = [
                                        'task_id'           => $task->id,
                                        'task_title'        => substr($task->title,0,50),
                                        'task_text'         => substr($task->text,0,80),
                                        'created_at'        => $task->created_at,
                                        'updated_at'        => $task->updated_at,
                                        'start'             => $task->st,
                                        'en'                => $task->en,
                                        'done'              => $task->status
                                    ];
                            //dd($task_info);
                            $assignedTo = assigned_to::where('task_id','=',$task_info['task_id'])
                                                    ->where('user_id','<>',Auth::user()->id)
                                                    ->get();
                            $prev_patient_id = null;
                            foreach($assignedTo as $assigned){

                                $res = [
                                    'creator_info'=>$creator_info,
                                    'task_info'=>$task_info,
                                    'patient_info'=>null,
                                    'exers_info'=>null
                                ];
                                $patient_dtls = DB::table('users_info')
                                                    ->select('profile','first_name','last_name')
                                                    ->where('user_id','=',$assigned->user_id)
                                                    ->first();
                                
                                if(!is_null($patient_dtls)){
                                    $res['patient_info'] = [
                                            'id'              => intval($assigned->user_id),
                                            'profile'         => $patient_dtls->profile,
                                            'first_name'      => $patient_dtls->first_name,
                                            'last_name'       => $patient_dtls->last_name,
                                            ];
                                }else{  //patient user no record in users_info
                                    array_push($err, [
                                                        "status"    => "No User Details",
                                                        "info"   => intval($assigned->user_id)
                                                    ]);
                                    continue;
                                }

                                if(is_null($prev_patient_id) || $prev_patient_id != $assigned->user_id){
                                    $assigned_to_patient_ids = assigned_to::select('id')
                                                                    ->where('task_id','=',$task->id)
                                                                    ->where('user_id','=', $res['patient_info']['id'])
                                                                    ->get();                                


                                    $temp = [
                                            //'notif_id'          => $assigned->id,
                                            'task_datas'        => task_exer_data::whereIn('task_assignment_id',$assigned_to_patient_ids)
                                                                        ->orderBy('task_assignment_id','freq_order')
                                                                        ->get(),
                                            ];

                                    //array_push($res['exers_info'],$temp);
                                    $res['exers_info'] = $temp;
                                    
                                }else{
                                    continue;
                                }
                                
                                array_push($set,$res);
                                $prev_patient_id = $assigned->user_id;
                            }
                        }
                    }
                }else{
                    $patient_id = $request->get('patient_id');
                    
                    $tasks = task::select('tasks_board.id as id',
                                            'project_id',
                                            'tasks_board.user_id as user_id',
                                            'title',
                                            'text')
                                    ->where('tasks_board.user_id','=',Auth::user()->id)    //searched by creator user
                                    ->where('tasks_board.active','=','1')
                                    ->where('task_assignment.user_id','=',intval($patient_id))
                                    ->leftjoin('task_assignment','tasks_board.id','=','task_assignment.task_id')
                                    ->get();
                    
                    if(sizeof($tasks) == 0){
                        $response['status'] = 'fail';
                        $response['message']    = 'No tasks yet.';
                    }else{
                        $prev_task_id = null;
                        foreach($tasks as $task){
                            if(is_null($prev_task_id) || $prev_task_id != $task->id){
                                $creator_dtls = User::find(intval($task->user_id))->userInformation; //get info of creator
                                $creator_info =[
                                            'profile'           => $creator_dtls->profile,
                                            'first_name'        => $creator_dtls->first_name,
                                            'last_name'         => $creator_dtls->last_name,
                                        ];
                                
                                $task_info = [
                                            'task_id'           => $task->id,
                                            'task_title'        => substr($task->title,0,50),
                                            'task_text'         => substr($task->text,0,80),
                                            'created_at'        => $task->created_at,
                                            'updated_at'        => $task->updated_at,
                                            'start'             => $task->st,
                                            'en'                => $task->en,
                                            'done'              => $task->status
                                        ];
                                //dd($task_info);
                                $assignedTo = assigned_to::where('task_id','=',$task_info['task_id'])
                                                        ->where('user_id','=',$patient_id)
                                                        ->get();
                                //
                                foreach($assignedTo as $assigned){

                                    $res = [
                                        'creator_info'=>$creator_info,
                                        'task_info'=>$task_info,
                                        'patient_info'=>null,
                                        'exers_info'=>null
                                    ];
                                    $patient_dtls = DB::table('users_info')
                                                        ->select('profile','first_name','last_name')
                                                        ->where('user_id','=',$assigned->user_id)
                                                        ->first();
                                    
                                    if(!is_null($patient_dtls)){
                                        $res['patient_info'] = [
                                                'id'              => intval($assigned->user_id),
                                                'profile'         => $patient_dtls->profile,
                                                'first_name'      => $patient_dtls->first_name,
                                                'last_name'       => $patient_dtls->last_name,
                                                ];
                                    }else{  //patient user no record in users_info
                                        array_push($err, [
                                                            "status"    => "No User Details",
                                                            "info"   => intval($assigned->user_id)
                                                        ]);
                                        continue;
                                    }
                                    $assigned_to_patient_ids = assigned_to::select('id')
                                                                    ->where('task_id','=',$task->id)
                                                                    ->where('user_id','=', $res['patient_info']['id'])
                                                                    ->get();
                                    $temp = [
                                            //'notif_id'          => $assigned->id,
                                            'task_datas'        => task_exer_data::whereIn('task_assignment_id',$assigned_to_patient_ids)
                                                                        ->orderBy('task_assignment_id','freq_order')
                                                                        ->get(),
                                            ];
                                    
                                    $res['exers_info'] = $temp;
                                }
                                
                                
                            }else{
                                continue;
                            }
                            array_push($set,$res);
                            $prev_task_id = $task->id;
                        }
                    }
                }
                
            }else{
                if($all == "true"){
                
                    
                    $tasks = task::select('tasks_board.id as id',
                                            'project_id',
                                            'tasks_board.user_id as user_id',
                                            'title',
                                            'text')
                                    //->where('tasks_board.user_id','=',Auth::user()->projects()->first()->project_id)    //project id
                                    ->where('tasks_board.active','=','1')
                                    ->where('task_assignment.user_id','=',Auth::user()->id)
                                    ->leftjoin('task_assignment','tasks_board.id','=','task_assignment.task_id')
                                    ->get();
                                    //dd(Auth::user()->projects()->first()->project_id);
                    if(sizeof($tasks) == 0){
                        $response['status'] = 'fail';
                        $response['message']    = 'No tasks yet.';
                    }else{
                        $prev_task_id = null;
                        foreach($tasks as $task){
                            if(is_null($prev_task_id) || $prev_task_id != $task->id){
                                $creator_dtls = User::find(intval($task->user_id))->userInformation; //get info of creator
                                $creator_info =[
                                            'profile'           => $creator_dtls->profile,
                                            'first_name'        => $creator_dtls->first_name,
                                            'last_name'         => $creator_dtls->last_name,
                                        ];
                                
                                $task_info = [
                                            'task_id'           => $task->id,
                                            'task_title'        => substr($task->title,0,50),
                                            'task_text'         => substr($task->text,0,80),
                                            'created_at'        => $task->created_at,
                                            'updated_at'        => $task->updated_at,
                                            'start'             => $task->st,
                                            'en'                => $task->en,
                                            'done'              => $task->status
                                        ];
                                //dd($task_info);
                                $assignedTo = assigned_to::where('task_id','=',$task_info['task_id'])
                                                        ->where('user_id','=',Auth::user()->id)
                                                        ->get();
                                //
                                foreach($assignedTo as $assigned){

                                    $res = [
                                        'creator_info'=>$creator_info,
                                        'task_info'=>$task_info,
                                        'patient_info'=>null,
                                        'exers_info'=>null
                                    ];
                                    $patient_dtls = DB::table('users_info')
                                                        ->select('profile','first_name','last_name')
                                                        ->where('user_id','=',$assigned->user_id)
                                                        ->first();
                                    
                                    if(!is_null($patient_dtls)){
                                        $res['patient_info'] = [
                                                'id'              => intval($assigned->user_id),
                                                'profile'         => $patient_dtls->profile,
                                                'first_name'      => $patient_dtls->first_name,
                                                'last_name'       => $patient_dtls->last_name,
                                                ];
                                    }else{  //patient user no record in users_info
                                        array_push($err, [
                                                            "status"    => "No User Details",
                                                            "info"   => intval($assigned->user_id)
                                                        ]);
                                        continue;
                                    }
                                    $assigned_to_patient_ids = assigned_to::select('id')
                                                                    ->where('task_id','=',$task->id)
                                                                    ->where('user_id','=', $res['patient_info']['id'])
                                                                    ->get();
                                    $temp = [
                                            //'notif_id'          => $assigned->id,
                                            'task_datas'        => task_exer_data::whereIn('task_assignment_id',$assigned_to_patient_ids)
                                                                        ->orderBy('task_assignment_id','freq_order')
                                                                        ->get(),
                                            ];
                                    
                                    $res['exers_info'] = $temp;
                                }
                                
                                
                            }else{
                                continue;
                            }
                            array_push($set,$res);
                            $prev_task_id = $task->id;
                        }
                    }
                }
            }
            //dd($set);
            $response = [
                        'status'  => 'success',
                        'tasks'   => $set,
                        'error'   => $err
                    ];
            return response()
                ->json($response)
                ->setCallback($request->input('callback'));
            
        //}
        //return redirect()->route('dashboard');
    }
    /**
     *  open vanilla leap lab
     *
     *  @return array
     */
    public function vanillaLab(Request $request){
        //authenticate before proceeding
        return view('leapvanilla',[]); 
        
    }
    /**
     *  open task
     *
     *  @return array
     */
    public function openTask(Request $request,$id){
        //authenticate before proceeding
        $task_exer_data = task_exer_data::find($id);
        if(!is_null($task_exer_data)){

            return view('leaptask',['pageid' => $id,'exer_id'=>$task_exer_data->exer_data_id,'p_exer_id'=>$task_exer_data->patient_exer_data_id,'resScore'=>$task_exer_data->resultScore,'adjustedResScore'=>$task_exer_data->adjustedResultScore]); 
        }else{
            return redirect()->route('dashboard');
        }
        
    }
    /**
     *  preview exerData
     *
     *  @return array
     */
    public function reviewExercise(Request $request,$id){
        //authenticate before 
        //$task_exer_data = task_exer_data::find($id);
        return view('leappreview',['pageid' => $id]);//,'exer_id'=>$task_exer_data->exer_data_id]);        
    }
    /**
     *  open tasks
     *
     *  @return array
     */
    public function openTasks(Request $request){
        //authenticate before proceeding
        return redirect()->route('dashboard');
        
    }
    /**
     *  retrieve result data
     *  $id = exer_id
     *  @return json.lz contents
     */
    public function getResultData(Request $request){
        //$exer_datas_id = explode('.json.lz',$id)[0];
        if ( $request->ajax() ) {
        
            $response = [
                'success'       => "",
                'message'       => []
            ];
            $input = $request->only([
                '_token',
                'id'
                ]);
            $exer_data = task_exer_data::select('similarityData')
                                        ->where('id','=',intval($input['id']))
                                        ->first();
            //dd($exer_data->similarityData);
            //$exer_data = exer_data::find($exer_datas->id);
            if(is_null($exer_data->similarityData) || !Storage::disk('local')->has('trainingData/'.$exer_data['similarityData'])){
                $response = [
                    'success'   => False,
                    'message'   => "[]"
                ];
                
                return response()
                    ->json($response);
                
            }
            $response = [
                'success'   => True,
                'message'   => Storage::disk('local')->get('trainingData/'.$exer_data['similarityData'])
            ];
            return response()
                    ->json($response);
        }
        
    }
    /**
     *  retrieve vanilla exer_data 
     *  $id = exer_id
     *  @return json.lz contents
     */
    public function getExerData(Request $request,$id){
        $exer_datas_id = explode('.json.lz',$id)[0];

        //$exer_datas = exer_data::find($exer_datas_id)->first();

        $exer_data = exer_data::where('id','=',intval($exer_datas_id))
                                ->first();
        //dd($exer_data);
        if(!Storage::disk('local')->has('trainingData/'.$exer_data['file'])){
            $response = [
                'success'   => False,
                'message'   => "Recording unavailable"
            ];
            
            return response()
                ->json($response);
            
        }

        return Storage::disk('local')->get('trainingData/'.$exer_data['file']);
    }
    /**
     *  retrieve exer_data of task (from physician)
     *  $id = exer_id
     *  @return json.lz contents
     */
    public function getExerDataTask(Request $request,$id){
        $exer_datas_id = explode('.json.lz',$id)[0];
        $exer_datas = task_exer_data::select('*')
                                        ->where('id','=',intval($exer_datas_id))
                                        ->first();
        if(!is_null($exer_datas)){
            $task_id = $exer_datas->task_id;
            $query = task::select('tasks_board.user_id as creator_user','tasks_board.id as task_id','tasks_board.active as active','tasks_board.updated_at','tasks_board.created_at')
                            ->where([   
                                        ['tasks_board.id','=',$task_id],
                                        ['tasks_board.active','=',1]
                                    ])
                            ->first();
            
            if(Auth::user()->user_types === 1){
                if($query['creator_user'] !== Auth::user()->id){
                    $response = [
                        'success'   => False,
                        'message'   => "Access is denied. Not the creator."
                    ];
                    return response()
                        ->json($response);
                }
                
            }else if(Auth::user()->user_types === 2){
                $check = assigned_to::select('task_id','user_id','active')
                                    ->where([
                                                ['task_id','=',$task_id],
                                                ['user_id','=',Auth::user()->id]
                                            ])
                                    ->first();
                //var_dump($check);
                if(is_null($check)){
                     $response = [
                        'success'   => False,
                        'message'   => "Access is denied. Not assigned to you."
                    ];
                    return response()
                        ->json($response);
                }
            }
            //$exer_datas->exerDataInformation->file;
            $exer_data = exer_data::where('id','-',intval($exer_datas['exer_data_id']))
                                    ->first();
            if(!Storage::disk('local')->has('trainingData/'.$exer_data['file'])){
                $response = [
                    'success'   => False,
                    'message'   => "Recording unavailable"
                ];
                
                return response()
                    ->json($response);
                
            }

            return Storage::disk('local')->get('trainingData/'.$exer_data['file']);
                
           
        }else{
            $response = [
                        'success'   => False,
                        'message'   => "Task does not exist."
                    ];
            return response()
                ->json($response);
        }
    }
    /**
     *  retrieve exer_data of task (from patient)
     *  $id = exer_id
     *  @return json.lz contents
     */
    public function getPatientExerDataTask(Request $request,$id){
        $exer_datas_id = explode('.json.lz',$id)[0];
        $exer_datas = task_exer_data::select('*')
                                        ->where('id','=',intval($exer_datas_id))
                                        ->first();
        //dd($exer_datas);
        $task = assigned_to::where('id','=',intval($exer_datas->task_assignment_id))
                                ->first();
        //dd($task);
        if(!is_null($task)){
            
            //dd($task);
            $task_id = $task->task_id;
            $query = task::select('tasks_board.user_id as creator_user','tasks_board.id as task_id','tasks_board.active as active','tasks_board.updated_at','tasks_board.created_at')
                            ->where([   
                                        ['tasks_board.id','=',$task_id],
                                        ['tasks_board.active','=',1]
                                    ])
                            ->first();
            if(!is_null($query)){
                if(Auth::user()->user_types === 1){
                    if($query['creator_user'] !== Auth::user()->id){
                        $response = [
                            'success'   => False,
                            'message'   => "Access is denied. Not the creator."
                        ];
                        return response()
                            ->json($response);
                    }
                    
                }else if(Auth::user()->user_types === 2){
                    $check = assigned_to::select('task_id','user_id','active')
                                        ->where([
                                                    ['task_id','=',$task_id],
                                                    ['user_id','=',Auth::user()->id]
                                                ])
                                        ->first();
                    //var_dump($check);
                    if(is_null($check)){
                         $response = [
                            'success'   => False,
                            'message'   => "Access is denied. Not assigned to you."
                        ];
                        return response()
                            ->json($response);
                    }
                }
                //$exer_datas->exerDataInformation->file;
                $exer_data = exer_data::where('id','=',intval($exer_datas['patient_exer_data_id']))
                                        ->first();
                //dd($exer_data);
                if(!is_null($exer_data)){
                    if(!Storage::disk('local')->has('trainingData/'.$exer_data['file'])){
                        $response = [
                            'success'   => False,
                            'message'   => "Recording unavailable"
                        ];
                        
                        return response()
                            ->json($response);
                        
                    }else{
                        return Storage::disk('local')->get('trainingData/'.$exer_data['file']);
                    }
                }else{
                    $response = [
                        'success'   => False,
                        'message'   => "Recording unavailable"
                    ];
                    
                    return response()
                        ->json($response);
                }  
            }else{
                $response = [
                        'success'   => False,
                        'message'   => "Task does not exist."
                    ];
                    return response()
                        ->json($response);
            }
        }else{
            $response = [
                    'success'   => False,
                    'message'   => "Task not available."
                ];
                return response()
                    ->json($response);
        }
    }
    /**
     *  retrieve training_data of task
     *
     *  @return json.lz contents
     */
    public function getTrainingDataTask(Request $request,$id){
        $id = explode('.json.lz',$id)[0];
        $query = assigned_to::select('task_id','user_id','id','training_data as data')
                        ->where([   
                                    ['id','=',$id]
                                ])
                        ->get();
        if(sizeof($query) == 1){
            $query = $query[0];
            if(Auth::user()->user_types === 1){
                $check = task::select('tasks_board.user_id as task_user_creator','tasks_board.project_id','tasks_board.id as task_id','tasks_board.user_id as creator_user','tasks_board.exer_data as data','tasks_board.active as active','tasks_board.updated_at','tasks_board.created_at')
                        ->where([   
                                    ['tasks_board.id','=',$query['task_id']]
                                ])
                        ->get();
                //might want to check sizeof($check) returning 1 record
                if($check[0]['task_user_creator'] !== Auth::user()->id){
                    $response = [
                        'success'   => False,
                        'message'   => "Access is denied. Not the creator."
                    ];
                    return response()
                        ->json($response);
                }
                
            }else if(Auth::user()->user_types === 2){
                $check = assigned_to::select('task_id','user_id','active')
                                    ->where([
                                                ['task_id','=',$task_id],
                                                ['user_id','=',Auth::user()->id]
                                            ])
                                    ->get();
                if(sizeof($check) !== 1){
                     $response = [
                        'success'   => False,
                        'message'   => "Access is denied. Not assigned to you."
                    ];
                    return response()
                        ->json($response);
                }
            }
            if(is_null($query['data'])) $query['data'] = 'null';        //remember to disallow 'null' from hashed names
            if(!Storage::disk('local')->has('trainingData/'.$query['data'])){
                    $response = [
                        'success'   => False,
                        'message'   => "Recording unavailable"
                    ];
                    return response()
                        ->json($response);
                }

                return Storage::disk('local')->get('trainingData/'.$query['data']);
            
        }else{
            $response = [
                    'success'   => False,
                    'message'   => "Task does not exist."
                ];
                return response()
                    ->json($response);
        }
    }
    /**
     *  post task on board
     *
     *  @return array
     */
    public function postTask(Request $request){
        if ( $request->ajax() ) {
        
            $response = [
                'success'       => "",
                'message'       => []
            ];
            $input = $request->only([
                '_token',
                'project_id',
                'title',
                'text',
                'frequency',
                'leapData',
                'patientData'
                ]);
            $rules = array(
                'project_id'    => 'required',
                'title'         => 'required|string|min:10',
                'text'          => 'required|string|min:10'
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                $response['success'] = False;
                $response['message'] = $validator->errors();
                return response()
                    ->json($response)
                    ->setCallback($request->input('callback'));                   
            }else{
                //if leapData is empty, return error
                $input+=['user_id'=>Auth::user()->id];
                $input+=['active'=>true];
                $newTask = new task();//task::create($input);
                $newTask->project_id = $input['project_id'];
                $newTask->user_id = Auth::user()->id;
                $newTask->title = $input['title'];
                $newTask->text = $input['text'];
                $newTask->frequency = $input['frequency'];
                $newTask->active = 1;
                $newTask->save();
                
                //assume all patients recieves the new task for now
                $user_ids = array();
                if(Auth::user()->user_types == 0){
                $user_ids = developer::select('user_id')
                                        ->where('user_id','<>',Auth::user()->id)
                                        ->where('project_id','=',$newTask['project_id'])
                                        ->get();
                $leapData = explode(',',$input['leapData']); //expect an array of exer_data_id                                        
                foreach($user_ids as $user_id){                    
                    for($i=0;$i<$newTask->frequency;$i++){
                        $assignedTo = assigned_to::insertGetId([
                            'task_id'           => $newTask['id'],
                            'user_id'           => $user_id['user_id'],
                            'training_data'     => Null,
                            'status'            => False,
                            'active'            => True 
                        ]);
                        //var_dump($assignedTo);
                //}
                //$leapData = explode(',',$input['leapData']); //expect an array of exer_data_id
                        $freq_order = 1;
                        foreach ($leapData as $exer) {
                            $newTaskExerData = task_exer_data::create([
                                                                    'freq_order'            => $freq_order++,
                                                                    'task_assignment_id'    => $assignedTo,
                                                                    'exer_data_id'          => $exer,              
                                                                    'active'                => True
                                                                ]);
                           
                        }
                        $freq_order = 1;
                        //$leapData = $request->file('leapData');    //should expect 1 file
                    }
                }
                }else if(Auth::user()->user_types == 1){
                    $user_ids = explode(',',$input['patientData']);
                    $leapData = explode(',',$input['leapData']); //expect an array of exer_data_id                                        
                    foreach($user_ids as $user_id){                    
                        for($i=0;$i<$newTask->frequency;$i++){
                            $assignedTo = assigned_to::insertGetId([
                                'task_id'           => $newTask['id'],
                                'user_id'           => $user_id,
                                'training_data'     => Null,
                                'status'            => False,
                                'active'            => True 
                            ]);
                            //var_dump($assignedTo);
                    //}
                    //$leapData = explode(',',$input['leapData']); //expect an array of exer_data_id
                            $freq_order = 1;
                            foreach ($leapData as $exer) {
                                $newTaskExerData = task_exer_data::create([
                                                                        'freq_order'            => $freq_order++,
                                                                        'task_assignment_id'    => $assignedTo,
                                                                        'exer_data_id'          => $exer,              
                                                                        'active'                => True
                                                                    ]);
                               
                            }
                            $freq_order = 1;
                            //$leapData = $request->file('leapData');    //should expect 1 file
                        }
                    }
                }
                
                                                                        //add feature:: exclusive for team / project/ profs
                
                /*if($request->hasFile('leapData')){  

                    $db_leap_files = "";
                    $invalidFile = array();
                    
                    $leapRules = [
                        'leapData'   => 'file'
                    ];
                    
                    if ($leapData->isValid()) {
                        $img = ['leapData'=>$leapData];
                        $validator = Validator::make($img, $leapRules);
                        if ($validator->fails()) {
                            array_push($invalidFile,$cntr);
                        }else{
                            
                            $leapName = uniqid('',True) . '.json.' . $leapData->getClientOriginalExtension();
                            Storage::put('trainingData/'.$leapName,file_get_contents($leapData->getRealPath()));
                            $db_leap_files.= $leapName;// . ",";
                        }
                    }else{
                        array_push($invalidFile,$cntr);
                    }
                   
                    //$db_leap_files = rtrim($db_leap_files, ',');
                    $newTask->exer_data = $db_leap_files;
                    $newTaskExerData->active = True;
                    $newTask->save();
                    
                        if(!empty($invalidFile)){
                            $response['success'] = False;
                            array_push($response['message'],"Leap Data failed to upload. Try to reupload.");
                        }
                }*/

                $images = $request->file('image');
                if($request->hasFile('image')){
                    
                    $db_images = "";
                    $cntr = 0;
                    $invalidImg = array();
                    
                    $imgRules = [
                        'image'   => 'image|mimes:jpg,jpeg,bmp,png,svg,gif'
                    ];
                    foreach ($images as $image) {
                        if ($image->isValid()) {
                            $img = ['image'=>$image];
                            $validator = Validator::make($img, $imgRules);
                            if ($validator->fails()) {
                                array_push($invalidImg,$cntr);
                            }else{
                                $imageName = uniqid('',True) . '.' . $image->getClientOriginalExtension();
                                Storage::put($imageName,file_get_contents($image->getRealPath()));
                                $db_images.= $imageName . ",";
                            }
                        }else{
                            array_push($invalidImg,$cntr);
                        }
                        $cntr++;
                    }
                    $db_images = rtrim($db_images, ',');
                    $newTask->image = $db_images;
                    $newTask->save();
                        if(!empty($invalidImg)){
                            $response['success'] = False;
                            array_push($response['message'],"Some images failed to upload. Edit to reupload.");
                        }
                }
                $response['success'] = True;
                array_push($response['message'],'Posted in '. project::find($newTask["project_id"])->text);

                return response()
                    ->json($response);
            
            }

        }
        return redirect()->route('dashboard');
    }
    /**
     *  post task on board (patient)
     *
     *  @return array
     */
    public function postTaskPatient(Request $request){
        if ( $request->ajax() ) {
        
            $response = [
                'success'       => "",
                'message'       => []
            ];
            $input = $request->only([
                '_token',
                'id',
                'leapData',
                'resultScore'
                ]);
            
            $rules = array(
                'id'         => 'required|integer',
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                $response['success'] = False;
                $response['message'] = $validator->errors();
                return response()
                    ->json($response)
                    ->setCallback($request->input('callback'));                   
            }else{
                
                $newTask = new exer_data();//task::create($input);
                $newTask->user_id = Auth::user()->id;
                $newTask->desc = "Patient exer data for task exer data id: " . $input['id'];
                //$newTask->resultScore = $input['resultScore'];
                $newTask->active = true;
                $newTask->save();
                
                $leapData = $request->file('leapData');    //should expect 1 file
                if($request->hasFile('leapData')){  

                    $db_leap_files = "";
                    $invalidFile = array();
                    
                    $leapRules = [
                        'leapData'   => 'file'
                    ];
                    
                    if ($leapData->isValid()) {
                        $img = ['leapData'=>$leapData];
                        $validator = Validator::make($img, $leapRules);
                        if ($validator->fails()) {
                            array_push($invalidFile,$cntr);
                        }else{
                            
                            $leapName = uniqid('',True) . '.json.lz';
                            Storage::put('trainingData/'.$leapName,file_get_contents($leapData->getRealPath()));
                            $db_leap_files.= $leapName;// . ",";
                        }
                        $task_exer_data = task_exer_data::find(intval($input['id']));
                        $task_exer_data->patient_exer_data_id = $newTask->id;
                        $task_exer_data->resultScore = $input['resultScore'];
                        $task_exer_data->save();

                        
                        $exer_data = exer_data::find(intval($task_exer_data->patient_exer_data_id));
                        $exer_data->file = $leapName;
                        $exer_data->save();
                    }else{
                        array_push($invalidFile,$cntr);
                    }
                   
                    //$db_leap_files = rtrim($db_leap_files, ',');
                    
                    
                    if(!empty($invalidFile)){
                        $response['success'] = False;
                        array_push($response['message'],"Leap Data failed to upload. Try to reupload.");
                    }
                    

                }else{
                    echo "Invalid leapData";
                }//find task in tasks_board update a column
                
                
                $response['success'] = True;
                array_push($response['message'],'Successfully uploaded exercise: '. $newTask->desc);

                return response()
                    ->json($response);
                
            }

        }
        //return redirect()->route('dashboard');
    }
    /**
     *  adjust score in task_exer_data table
     *
     *  @return array
     */
    public function postAdjustedScore(Request $request){
        if ( $request->ajax() && Auth::user()->user_types == 1) {
        
            $response = [
                'success'       => "",
                'message'       => []
            ];
            $input = $request->only([
                '_token',
                'id',
                'adjustedScore'
                ]);
            
            $rules = array(
                'id'         => 'required|integer',
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                $response['success'] = False;
                $response['message'] = $validator->errors();
                return response()
                    ->json($response)
                    ->setCallback($request->input('callback'));                   
            }else{
                
                $query = task_exer_data::find(intval($input['id']));
                $query->adjustedResultScore = $input['adjustedScore'];
                $query->save();
                
                
                $response['success'] = True;
                array_push($response['message'],'Successfully Adjusted Score in exercise id: '. $query->id);

                return response()
                    ->json($response);
                
            }

        }
        //return redirect()->route('dashboard');
    }
    /**
     *  post task on board (patient)
     *
     *  @return array
     */
    public function postTaskPatientResult(Request $request){
        if ( $request->ajax() ) {
        
            $response = [
                'success'       => "",
                'message'       => []
            ];
            $input = $request->only([
                '_token',
                'id',
                'similarityResult',
                'resultScore'
                ]);
            
            $rules = array(
                'id'         => 'required|integer',
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                $response['success'] = False;
                $response['message'] = $validator->errors();
                return response()
                    ->json($response)
                    ->setCallback($request->input('callback'));                   
            }else{
                $resultData = $request->file('similarityResult');    //should expect 1 file
                
                if($request->hasFile('similarityResult')){  

                    $db_leap_files = "";
                    $invalidFile = array();
                    
                    $resultRules = [
                        'similarityResult'   => 'file'
                    ];
                    
                    if ($resultData->isValid()) {
                        $img = ['similarityResult'=>$resultData];
                        $validator = Validator::make($img, $resultRules);
                        if ($validator->fails()) {
                            array_push($invalidFile,$cntr);
                        }else{
                            
                            $resultName = uniqid('',True) . '.json';
                            Storage::put('trainingData/'.$resultName,file_get_contents($resultData->getRealPath()));
                            $db_leap_files.= $resultName;// . ",";
                        }
                        $task_exer_data = task_exer_data::find(intval($input['id']));
                        $task_exer_data->similarityData = $resultName;
                        $task_exer_data->resultScore = $input['resultScore'];
                        $task_exer_data->save();
                    }else{
                        array_push($invalidFile,$cntr);
                    }
                    if(!empty($invalidFile)){
                        $response['success'] = False;
                        array_push($response['message'],"Result Data failed to upload. Try to reupload.");
                    }
                    

                }else{
                    echo "Invalid resultData";
                }
                
                
                $response['success'] = True;
                array_push($response['message'],'Successfully uploaded result: '. $input['id']);

                return response()
                    ->json($response);
                
            }

        }
        //return redirect()->route('dashboard');
    }
    /**
     *  post discussion on board
     *
     *  @return array
     */
    public function postExerciseData(Request $request){
        if ( $request->ajax() ) {
        
            $response = [
                'success'       => "",
                'message'       => []
            ];
            $input = $request->only([
                '_token',
                'title',
                'leapData'
                ]);
            
            $rules = array(
                'title'         => 'required|string|min:10',
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                $response['success'] = False;
                $response['message'] = $validator->errors();
                return response()
                    ->json($response)
                    ->setCallback($request->input('callback'));                   
            }else{
                
                $newTask = new exer_data();//task::create($input);
                $newTask->user_id = Auth::user()->id;
                $newTask->desc = $input['title'];
                $newTask->active = true;
                $newTask->save();
                
                $leapData = $request->file('leapData');    //should expect 1 file
                
                if($request->hasFile('leapData')){  

                    $db_leap_files = "";
                    $invalidFile = array();
                    
                    $leapRules = [
                        'leapData'   => 'file'
                    ];
                    
                    if ($leapData->isValid()) {
                        $img = ['leapData'=>$leapData];
                        $validator = Validator::make($img, $leapRules);
                        if ($validator->fails()) {
                            array_push($invalidFile,$cntr);
                        }else{
                            
                            $leapName = uniqid('',True) . '.json.' . $leapData->getClientOriginalExtension();
                            Storage::put('trainingData/'.$leapName,file_get_contents($leapData->getRealPath()));
                            $db_leap_files.= $leapName;// . ",";
                        }
                    }else{
                        array_push($invalidFile,$cntr);
                    }
                   
                    //$db_leap_files = rtrim($db_leap_files, ',');
                    $newTask->file = $db_leap_files;
                    $newTask->save();
                    
                        if(!empty($invalidFile)){
                            $response['success'] = False;
                            array_push($response['message'],"Leap Data failed to upload. Try to reupload.");
                        }
                }

                
                $response['success'] = True;
                array_push($response['message'],'Successfully uploaded exercise: '. $newTask->desc);

                return response()
                    ->json($response);
            
            }

        }
        return redirect()->route('dashboard');
    }
    /**
     *  post exercise_data
     *
     *  @return array
     */
    public function postExerciseDataList(Request $request){
        if ( $request->ajax() ) {
        
            $response = [
                'success'       => "",
                'message'       => []
            ];
            $input = $request->only([
                '_token',
                ]);
            if(Auth::user()->user_types == 0 || Auth::user()->user_types == 1){ // admin or physician only
                $query = exer_data::select('id','desc')
                                    ->where('user_id','=', Auth::user()->id)
                                    ->get();
                if(count($query) == 0){
                    $response['success'] = False;
                    array_push($response['message'],"No exerdata.");
                }else{
                    $response['success'] = True;
                    foreach ($query as $exerdata) {
                        array_push($response['message'],$exerdata);
                    }                    
                    return response()
                        ->json($response);
                }
            }else{
                $response['success'] = False;
                array_push($response['message'],"Access denied.");
                return response()
                        ->json($response);
            }
        
        }
        return redirect()->route('dashboard');
    }
}

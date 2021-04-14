<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Image;
use Storage;
use Validator;
use Auth;
use Hash;
use DB;

use App\User;
use App\user_info;
use App\project;
use App\developer;
use App\discussion;
use App\discussion_comment;
use App\discussion_notif;
use App\discussion_vote;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class adminController extends Controller
{
    /**
     * Create a new user.
     *
     * @return error
     * @return view 
     */
    public function getActivationCode(Request $request)
    {   
        if(Auth::user()->user_types === 0 || Auth::user()->user_types === 1){

            if($request->ajax()){
                $response = [
                    'status'       => "",
                    'message'      => []
                ];
                
                $input = $request->only([
                '_token',
                'email',
                ]);
                $rules = array(
                    'email'     => 'required|email|max:255', // make sure the email is an actual email
                );
                $validator = Validator::make($input, $rules);
                if ($validator->fails()) {//change to /admin/dashboard
                    $response['status'] = 'validatorFail';
                    $response['message'] = $validator->errors();
                    
                    return response()
                        ->json($response)
                        ->setCallback($request->input('callback'));
                } else {
                    
                    try{
                        $query = User::select('id', 'activation_code')
                                    ->where('email','=',$input['email'])
                                    ->first();
                        if(!is_null($query)){
                            if($query->count() == 0){
                                $response['status'] = 'fail';
                                $response['message'] = 'Email doesn\'t exist.';
                                return response()
                                    ->json($response);
                            }else{
                                $response['status'] = 'success';
                                $response['message'] = $query['activation_code'];

                                return response()
                                   ->json($response);
                            }
                        }else{
                            $response['status'] = 'fail';
                                $response['message'] = 'Email doesn\'t exists.';
                                return response()
                                    ->json($response);
                        }
                    } catch (PDOException $e) {
                        $response['status'] = 'fail';
                        $response['message'] = 'PDOException. Kindly report this.';
                        
                        return response()
                            ->json($response);
                    }           
                }
            }
        }
        return redirect()->route('dashboardAdmin');
    }
    /**
     * Search patient group.
     *
     * @return error
     * @return view 
     */
    public function getPatientGroup(Request $request)
    {   
        if(Auth::user()->user_types === 0 || Auth::user()->user_types === 1){

            if($request->ajax()){
                $response = [
                    'status'       => "",
                    'message'      => []
                ];
                
                $input = $request->only([
                '_token',
                'search',
                ]);
                $validator_email = Validator::make($input, ['search'     => 'required|email|max:255']);
                $validator_group_id = Validator::make($input, ['search'     => 'required|integer']);
                $validator_owner_name = Validator::make($input, ['search'     => 'required|string|max:255']);

                if (!$validator_email->fails()) {
                    try{
                        $query = User::select('id')
                                    ->where('email','=',$input['search'])
                                    ->get();
                        if(!is_null($query)){
                            if($query->count() == 0){
                                $response['status'] = 'fail';
                                $response['message'] = array('Email doesn\'t exist.');
                                return response()
                                    ->json($response);
                            }else{
                                $response['status'] = 'success';
                                $response['message'] = array();
                                foreach($query as $owner){
                                    array_push($response['message'],project::where('owner_id','=',$owner['id'])
                                                                ->get());
                                }
                                return response()
                                   ->json($response);
                            }
                        }else{
                            $response['status'] = 'fail';
                            $response['message'] = array('Email doesn\'t exist.');
                                return response()
                                    ->json($response);
                        }
                    } catch (PDOException $e) {
                        $response['status'] = 'fail';
                        $response['message'] = array('PDOException. Kindly report this.');
                        
                        return response()
                            ->json($response);
                    }
                }else if(!$validator_group_id->fails()){
                    try{
                        $query = project::where('id','=',$input['search'])
                                            ->get();
                        if(!is_null($query)){
                            if($query->count() == 0){
                                $response['status'] = 'fail';
                                $response['message'] = array('Group ID doesn\'t exist.');
                                return response()
                                    ->json($response);
                            }else{
                                $response['status'] = 'success';
                                $response['message'] = array();
                                foreach($query as $owner){
                                    array_push($response['message'],$query);
                                }
                                return response()
                                   ->json($response);
                            }
                        }else{
                            $response['status'] = 'fail';
                                $response['message'] = array('Group ID doesn\'t exist.');
                                return response()
                                    ->json($response);
                        }
                    } catch (PDOException $e) {
                        $response['status'] = 'fail';
                        $response['message'] = array('PDOException. Kindly report this.');
                        
                        return response()
                            ->json($response);
                    }
                }else if(!$validator_owner_name->fails()){
                    try{
                        $searchStr = strtoupper('%' . str_replace(' ', '%',$input['search']) . '%');
                        $query = DB::table('users_info')
                                    ->select('user_id as id')
                                    ->where(DB::raw('UPPER(CONCAT(first_name, " ",middle_name," ", last_name))'), "LIKE", $searchStr )
                                    ->get();
                        if(!is_null($query)){
                            if(count($query)== 0){
                                $response['status'] = 'fail';
                                $response['message'] = array('Physician doesn\'t exists.');
                                return response()
                                    ->json($response);
                            }else{
                                $response['status'] = 'success';
                                $response['message'] = array();
                                foreach($query as $owner){
                                    array_push($response['message'],project::where('owner_id','=',$owner->id)
                                                                            ->get());
                                }
                                return response()
                                   ->json($response);
                            }
                        }else{
                            $response['status'] = 'fail';
                            $response['message'] = array('Physician doesn\'t exists.');
                                return response()
                                    ->json($response);
                        }
                    } catch (PDOException $e) {
                        $response['status'] = 'fail';
                        $response['message'] = 'PDOException. Kindly report this.';
                        
                        return response()
                            ->json($response);
                    }
                } else { // all validation failed
                    $response['status'] = 'validatorFail';
                    $response['message'] = array($validator_email->errors());
                    array_push($response['message'], $validator_group_id->errors());
                    array_push($response['message'], $validator_owner_name->errors());
                    
                    return response()
                        ->json($response)
                        ->setCallback($request->input('callback'));
                }
                
            }
        }
        return redirect()->route('dashboardAdmin');

    }
    /* Search patient group.
     *
     * @return error
     * @return view 
     */
    public function postUpdatePGCount(Request $request)
    {   
        if(Auth::user()->user_types === 0 || Auth::user()->user_types === 1){

            if($request->ajax()){
                $response = [
                    'status'       => "",
                    'message'      => []
                ];
                
                $input = $request->only([
                '_token',
                'id',
                'type'
                ]);
                $validator = Validator::make($input, [  'id'     => 'required|integer',
                                                        'type'   => 'required|integer'
                                                    ]);
                
                if ($validator->fails()) {
                    $response['status'] = 'validatorFail';
                    $response['message'] = array($validator->errors());
                    
                    return response()
                        ->json($response)
                        ->setCallback($request->input('callback'));
                    
                } else { // all validation failed
                    try{
                        $query = project::where('id','=',$input['id'])
                                        ->first();
                        if(!is_null($query)){
                            if($query->count() == 0){
                                $response['status'] = 'fail';
                                $response['message'] = array('Patient group doesn\'t exist.');
                                return response()
                                    ->json($response);
                            }else{
                                $response['status'] = 'success';
                                $response['message'] = array();
                                if($input['type'] == 1){
                                    DB::table('projects')
                                        ->where('id','=',$query->id)
                                        ->update(['size'=>$query->size+1]);
                                    
                                }else if($input['type'] == 0){
                                    $proj = project::select('size')
                                                        ->where('id','=',$input['id'])
                                                        ->first();
                                    if($proj->size >= 1){
                                        DB::table('projects')
                                            ->where('id','=',$query->id)
                                            ->update(['size'=>$query->size-1]);
                                    }else{
                                        $response['status'] = 'fail';
                                        $response['message'] = array('Patient group size too low.');
                                        return response()
                                            ->json($response);
                                    }
                                }else{
                                    $response['status'] = 'fail';
                                    $response['message'] = array('Input Error.');
                                    return response()
                                        ->json($response);
                                }
                                $response['message'] = array(project::select('id','size')
                                                                        ->where('id','=',$query->id)
                                                                        ->first());
                                return response()
                                   ->json($response);
                            }
                        }else{
                            $response['status'] = 'fail';
                            $response['message'] = array('Email doesn\'t exist.');
                                return response()
                                    ->json($response);
                        }
                    } catch (PDOException $e) {
                        $response['status'] = 'fail';
                        $response['message'] = array('PDOException. Kindly report this.');
                        
                        return response()
                            ->json($response);
                    }
                }
                
            }
        }
        return redirect()->route('dashboardAdmin');

    }
    /**
     * Create a new user.
     *
     *  note: might want to change function name down the line
     *
     * @return error
     * @return view 
     */
    public function registerByAdmin(Request $request)
    {  
        if(adminController::isAdmin() || Auth::user()->user_types === 1){
            if($request->ajax()){
                $response = [
                    'status'       => "",
                    'message'      => []
                ];
                //dd($response);
                $input = $request->only([
                '_token',
                'email',
                'user_types'
                ]);
                if(Auth::user()->user_types === 1) $input['user_types'] = 2;    // only admin can choose what kind of user to make
                //dd($input);
                $rules = array(
                    'email'     => 'required|email|max:255', // make sure the email is an actual email
                    'user_types' => 'required|integer'
                );
                $validator = Validator::make($input, $rules);
                if ($validator->fails()) {//change to /admin/dashboard
                    $response['status'] = 'validatorFail';
                    $response['message'] = $validator->errors();
                    
                    return response()
                        ->json($response)
                        ->setCallback($request->input('callback'));
                } else {
                    unset($input['_token']);
                    $pword = adminController::unique_code(8);
                    
                    $input['password']= Hash::make($pword);
                    $input['username'] = explode("@", $input['email'])[0];
                    $input['activation_code'] = $pword;
                    try{
                        if($input['user_types'] == 1){
                            $user = DB::table('users')
                                        -> insertGetId($input);
                                        
                            $proj_inp = [
                                         'owner_id' => $user,
                                         'text' => $input['username'],
                                         'size' => 10,
                                         'active' => true
                                        ];
                            
                            $proj_id = project::create($proj_inp);
                           
                            $dev_inp = new developer;
                            $dev_inp->project_id = Auth::user()->id;
                            $dev_inp->user_id = $user;
                            $dev_inp->role = $input['user_types'];
                            $dev_inp->save();

                            DB::table('developers')->insert([
                                    'project_id'    => $proj_id->id,
                                    'user_id'       => $user,
                                    'role'          => 1
                                ]);
                            

                        }else if($input['user_types'] == 2){
                            $creator_project = DB::table('projects')
                                                ->select('id','owner_id','size')
                                                ->where('owner_id','=',Auth::user()->id)
                                                ->first();
                            $creator_group_limit_cnt = DB::table('developers')
                                                            ->where('project_id','=',$creator_project->id)
                                                            ->count();

                            if($creator_group_limit_cnt < $creator_project->size){
                                $new_patient_id = DB::table('users')
                                                    -> insertGetId($input);
                                DB::table('developers')->insert([
                                    'project_id'    => $creator_project->id,
                                    'user_id'       => $new_patient_id,
                                    'role'          => 2
                                ]);
                            }else{
                                $response['status'] = 'fail';
                                $response['message'] = 'Physician group size exceeded allocation. Contact Admin.';
                                return response()
                                    ->json($response);
                            }
                        }else{
                            //logout and redirect
                        }

                        $response['status'] = 'success';
                        $response['message'] = $pword;

                        return response()
                           ->json($response);
                    }catch (QueryException $e) {
                        $errDuplicateEntry = 1062;
                        if($e->errorInfo[1] == 1062){
                            $response['status'] = 'fail';
                            $response['message'] = 'Email already exists.';
                            return response()
                                ->json($response);
                        }else{
                            $response['status'] = 'fail';
                            $response['message'] = $e->errorInfo[1];
                            return response()
                                ->json($response);
                        }

                    } catch (PDOException $e) {
                        $response['status'] = 'fail';
                        $response['message'] = 'PDOException. Kindly report this.';
                        
                        return response()
                            ->json($response);
                    }           
                   
                }
            } 
        }else{
            //logout and redirect
        }
        return redirect()->route('dashboardAdmin');
    }

    /**
     *  return unique code
     *
     *  source: https://codebriefly.com/unique-alphanumeric-string-php/
     *  @return /code
     */
    private function isAdmin()
    {
        if (Auth::user()->user_types === 0){
            return true;
        }else{
            return false;
        }
      return false;
    }
    /**
     *  check if user is an admin
     *
     *  
     *  @return 
     */
    private function unique_code($limit)
    {
      return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
}
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
        if(adminController::isAdmin()){
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
        	                    $response['message'] = 'Email doesn\'t exists.';
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
     * Create a new user.
     *
     * @return error
     * @return view 
     */
    public function registerByAdmin(Request $request)
    {   
        if(adminController::isAdmin()){
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
                        $user = DB::table('users')
                                    -> insertGetId($input);
                                    
                        $proj_inp = [
                                     'owner_id' => $user,
                                     'text' => $input['username'],
                                     'size' => 10,
                                     'active' => true
                                    ];
                        project::create($proj_inp);

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
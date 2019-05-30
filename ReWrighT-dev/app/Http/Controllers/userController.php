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

class UserController extends Controller
{
     /**
     * Redirect to route Path after login
     *
     * @var string
     */
    protected $redirectTo = 'dashboard';
    /*
	 * Create a UserController Instance
	 *
	 *
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}
    /**
    *
    *  return dashboard page
    *  @return view
    */
    public function dashboard(Request $request){   
        //dd(Auth::user()->projects[0]->project_id);
        if(!is_null(Auth::user()->projects[0]->project_id)){
            $project = Auth::user()->projects[0]->project_id;
            return view('dashboard',compact('project'));//$request->user()->username;
        }else{
            return view('dashboard2');//$request->user()->username;
        }
    }
    /**
    *
    *  return dashboard page
    *  @return view
    */
    public function dashboard2(Request $request){
        
        return view('dashboard2');//$request->user()->username;
        
    }
	/**
     * edit user profile details
     *
     *
     * @return Response
     */
    public function editUserProfile(Request $request){
        dd($request->all());
        $input = Input::all();
        $user_id = Auth::user()->id;
        if(Auth::user()->user_type == 1){
            $user = users_teacher::where('user_id',$user_id)->first();
            
            $user->first_name = Input::get('fname');
            $user->middle_name = Input::get('mname');
            $user->last_name = Input::get('lname');
            $user->suffix_name = Input::get('suffix_name');
            $user->perm_address = Input::get('perm_address');
            $user->tempo_address = Input::get('tempo_address');
            $user->sex = Input::get('sex');
            $user->position = Input::get('position');
            $user->department = Input::get('department');
            $user->updated_at = Carbon::now();
           $user->save();
        }else{
            $user = users_student::where('user_id',$user_id)->first();

            $user->first_name = Input::get('fname');
            $user->middle_name = Input::get('mname');
            $user->last_name = Input::get('lname');
            $user->suffix_name = Input::get('suffix_name');
            $user->perm_address = Input::get('perm_address');
            $user->tempo_address = Input::get('tempo_address');
            $user->sex = Input::get('sex');
            $user->course = Input::get('course');
            $user->major = Input::get('major');
            $user->department = Input::get('department');
            $user->updated_at = Carbon::now();
           $user->save();
        }
        return Redirect::intended('/profile');
    }
	/**
     *  Log out the user 
     *
     *  @return void
     */
    public function getLogout(){
        
        Auth::logout();
        
        return redirect()->intended('/');
    }
    
}

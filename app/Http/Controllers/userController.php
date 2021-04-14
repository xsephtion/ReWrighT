<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use DB;
use Carbon\Carbon;

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

class userController extends Controller
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
        if(Auth::user()->user_types === 0){
            Auth::logout();
            return redirect()
                ->route('loginAdmin')
                ->with('error','Account is an admin.');
        }else if(Auth::user()->user_types === 1){
                return view('dashboard');
        }else if(Auth::user()->user_types === 2){
                return view('dashboard2');   
        }
        
    }
    /**
    *
    *  return dashboard page
    *  @return view
    */
    public function dashboardAdmin(Request $request){
        if(Auth::user()->user_types === 0){
            return view('dashboardAdmin');//$request->user()->username; 
        }else{
            Auth::logout();
            return redirect()
                ->route('login')
                ->with('error','Account is not an admin.');
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
     * @return View
     */
    public function editUserProfile1($code){
        if(Auth::user()->user_types === 0 || Auth::user()->user_types === 1){
            $user_id = DB::table('users')
                        ->select('id','email','activation_code as code')
                        ->where('activation_code','=',$code)
                        ->get();
            $user_cnt = user_info::select('*')
                            ->where('user_id','=',$user_id[0]->id)
                            ->count();
            if($user_cnt == 0){
                $user_info_id = DB::table('users_info')->insertGetId([
                    "user_id"=>$user_id[0]->id,
                    "profile"=>'1_profile_1.jpg',
                    "banner"=>'1_banner_1.jpg',
                    "first_name"=>'',
                    "middle_name"=>'',
                    "last_name"=>'',
                    "suffix_name"=>NULL,
                    "sex"=>NULL,
                    "perm_address"=>NULL,
                    "tempo_address"=>NULL,
                    "office_address"=>NULL
                ]);
            }
                $user_info = user_info::select('profile as prof_pic',
                                            'first_name as fname',
                                            'middle_name as mname',
                                            'last_name as lname',
                                            'suffix_name as sname',
                                            'sex',
                                            'perm_address as p_add',
                                            'tempo_address as t_add',
                                            'office_address as o_add')
                            ->where('user_id','=',$user_id[0]->id)
                            ->first();
                $user = array('prof_pic' => $user_info->prof_pic,
                                'fname'  => $user_info->fname,
                                'mname'  => $user_info->mname,
                                'lname'  => $user_info->lname,
                                'sname'  => $user_info->sname,
                                'sex'    => $user_info->sex,
                                'p_add'  => $user_info->p_add,
                                't_add'  => $user_info->t_add,
                                'o_add'  => $user_info->o_add,
                                'email'  => $user_id[0]->email,
                                'code'   => $user_id[0]->code);
            return view('profileEdit',['user_info'=>$user]);
        }else{
            //view only redirect to another view
        }
    }
    
	/**
     * edit user profile details
     *
     *
     * @return Response
     */
    public function saveEditUserProfile(Request $request){
        //dd($request->all());
        $response = [
            'success'       => "",
            'message'       => []
        ];
        $input = $request->only([
            'h_email',
            'h_code',
            'fname',
            'mname',
            'lname',
            'suffix_name',
            'sex',
            'perm_address',
            'tempo_address',
            'office_address'
            ]);
        $rules = array(
            'h_email'       => 'required|email|max:255',
            'h_code'        => 'required|string|max:8',
            'fname'         => 'required|string',
            'mname'         => 'required|string',
            'lname'         => 'required|string',
            'sex'           => 'required|string',
            'perm_address'  => 'required|string|min:10'
        );

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response['success'] = False;
            $response['message'] = $validator->errors();
            return response()
                ->json($response)
                ->setCallback($request->input('callback'));                   
        }else{
            $user_id = DB::table('users')
                        ->select('id','email','activation_code as code')
                        ->where('activation_code','=',$input['h_code'])
                        ->orWhere('email','=',$input['h_email'])
                        ->get();
            $user = user_info::where('user_id',$user_id[0]->id)->first();
            $user->first_name =  $input['fname'];
            $user->middle_name = $input['mname'];
            $user->last_name = $input['lname'];
            $user->perm_address = $input['perm_address'];
            if($user->sex !== $input['sex']) $user->sex = $input['sex'];
            if($user->suffix_name !== $input['suffix_name']) $user->suffix_name = $input['suffix_name'];
            if($user->tempo_address !== $input['tempo_address']) $user->tempo_address = $input['tempo_address'];
            if($user->office_address !== $input['office_address']) $user->office_address = $input['office_address'];
            
            $user->updated_at = Carbon::now();
            $user->save();
        }
        $redirect = 'auth/profile/edit/' . $input['h_code'];
        return redirect()->intended($redirect);
        
    }
    /**
     *  Log out the user 
     *
     *  @return void
     */
    public function getLogoutAdmin(){
        
        Auth::logout();
        
        return redirect()->intended('/auth/admin/login');
    }
	/**
     *  Log out the user 
     *
     *  @return void
     */
    public function getLogout(){
        
        if(Auth::user()->user_types === 0){
            
            Auth::logout();
            return redirect()->intended('/auth/admin/login');
        }else{
            Auth::logout();
        
            return redirect()->intended('/');    
        }
        
    }
    /**
     * Search patient 
     *
     * @return error
     * @return view 
     */
    public function getPatientSrch(Request $request)
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
                $validator_owner_name = Validator::make($input, ['search'     => 'required|string|max:255']);

                if (!$validator_email->fails()) {
                    try{
                        $query = User::select('id')
                                    ->where(DB::raw('UPPER(email)'),'=',$input['search'])
                                    ->where('developers.project_id','=',Auth::user()->projects[Auth::user()->user_types]->project_id)
                                    ->leftjoin("developers","developers.user_id",'=','users.id')
                                    ->get();
                        if(!is_null($query)){
                            if($query->count() == 0){
                                $response['status'] = 'fail';
                                $response['message'] = array('Email doesn\'t exist.');
                                return response()
                                    ->json($response);
                            }else{
                                if($query[0]['id'] == Auth::user()->id){
                                    $response['status'] = 'fail';
                                    $response['message'] = array('Don\'t search yourself.');
                                    return response()
                                        ->json($response);
                                }
                                $response['status'] = 'success';
                                $response['message'] = array();
                                foreach($query as $owner){
                                    array_push($response['message'],user_info::select('users.activation_code as code','users_info.user_id','users_info.profile','first_name','middle_name','last_name','suffix_name','sex')
                                                                            ->where('user_id','=',$owner['id'])
                                                                            ->leftjoin('users','users.id','=','users_info.user_id')
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
                }else if(!$validator_owner_name->fails()){
                    try{
                        $searchStr = strtoupper('%' . str_replace(' ', '%',$input['search']) . '%');
                        $query = DB::table('users_info')
                                    ->select('users_info.user_id as id')
                                    ->where(DB::raw('UPPER(CONCAT(first_name, " ",middle_name," ", last_name))'), "LIKE", $searchStr )
                                    ->where('developers.project_id','=',Auth::user()->projects[Auth::user()->user_types]->project_id)
                                    ->where('users_info.user_id','<>',intval(Auth::user()->id))
                                    ->leftjoin("developers","developers.user_id",'=','users_info.user_id')
                                    
                                    ->get();
                        if(!is_null($query)){
                            if(count($query)== 0){
                                $response['status'] = 'fail';
                                $response['message'] = array('Patient doesn\'t exists.');
                                return response()
                                    ->json($response);
                            }else{
                                $response['status'] = 'success';
                                $response['message'] = array();
                                foreach($query as $owner){
                                    array_push($response['message'],user_info::select('users.activation_code as code','users_info.user_id','users_info.profile','first_name','middle_name','last_name','suffix_name','sex')
                                                                            ->where('user_id','=',$owner->id)
                                                                            ->leftjoin('users','users.id','=','users_info.user_id')
                                                                            ->get());
                                }
                                return response()
                                   ->json($response);
                            }
                        }else{
                            $response['status'] = 'fail';
                            $response['message'] = array('Patient doesn\'t exists.');
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
                    array_push($response['message'], $validator_owner_name->errors());
                    
                    return response()
                        ->json($response)
                        ->setCallback($request->input('callback'));
                }
                
            }
        }
        return redirect()->route('dashboard');
    }
}

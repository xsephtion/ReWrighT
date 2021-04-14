<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\project;
use App\developer;
class projectController extends Controller
{
        /**
     *  Get projects
     *
     *  @return array
     */
    public function getProjects(Request $request){
    	if ( $request->ajax() ) {
	    	//$page = $request->get('page')*10;
	    	
	    	$projects = project::get();
	    	$response = [
                'success'       => true,
                'projects'       => $projects
            ];
	    	return response()
	                ->json($response);
      	}
        return redirect()->route('dashboard');
    }
    /**
     *  join Projects
     *
     *  @return array
     */
    public function joinProject(Request $request,$id){
    	if($request->ajax()){
	    	$projects = Auth::user()->projects;
	    	foreach ($projects as $project) {
	    		if($id == $project->project_id){
	    			$response = [
	                'success'       => true,
	                'message'       => "Already admitted to physician."
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
	    }
    	return redirect()->route('dashboard');
    }
    /**
     *  post discussion on board
     *
     *  @return array
     */
    public function postPatientDataList(Request $request){
    	if ( $request->ajax() ) {
        
            $response = [
                'success'       => "",
                'message'       => []
            ];
            $input = $request->only([
                '_token',
                ]);
            if(Auth::user()->user_types == 0 || Auth::user()->user_types == 1){ // admin or physician only
                $query = developer::select('developers.user_id','developers.project_id','users_info.first_name','users_info.middle_name','users_info.last_name','users_info.suffix_name','users_info.profile')
                						->where('developers.user_id','<>',Auth::user()->id)
                                        ->where('developers.project_id','=',Auth::user()->projects[Auth::user()->user_types]->project_id)
                                        ->whereNotNull('users_info.first_name')
                                        ->leftjoin('users_info','users_info.user_id','=','developers.user_id')
										->get();
                //var_dump($query);
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

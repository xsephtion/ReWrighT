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
	    }
    	return redirect()->route('dashboard');
    }
}

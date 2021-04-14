<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Image;
use Storage;
use Validator;
use Auth;
use DB;

use App\task_exer_data;
use App\assigned_to;
use App\note;
class notesController extends Controller
{
    /**
     *  Get Note
     *
     *  @return json
     */
    public function getNote(Request $request){
        if ( $request->ajax() ) {
            if(Auth::user()->user_types == 1){
	            $input = $request->only([
	                '_token',
	                'id',
				]);
	            $query = note::where([
	                                    ['id','=',$input['id']],
	                                    ['physician_id','=',Auth::user()->id],
	                                    ['active','=',True]
	                                ])
	                            ->first();

	            if(is_null($query)){
	                $response = [
	                        'status'  => 'fail',
	                        'task'   => null,
	                        'error'   => ['Not Found.']
	                    ];
	            }else{
	                $info = [
	                    'id'           => $query->id,
	                    'title'        => substr($query->title,0,50),
	                    'text'         => $query->text,
	                    'images'       => $query->image,
	                    'updated_at'   => $query->updated_at
	                ];
	                $response = [
	                        'status'  => 'success',
	                        'note'   => $info
	                    ];
	            }
	            return response()
	                ->json($response)
	                ->setCallback($request->input('callback'));
	        }
	    }
	    return redirect()->route('dashboard');  
    }
    /**
     *  Get Patient Notes (list)
     *
     *  @return array
     */
    public function getPatientNotes(Request $request){
        if ( $request->ajax() ) {
            if(Auth::user()->user_types == 1){
	            $input = $request->only([
	                '_token',
	                'id',
				]);
				$notes = array();
	            $query_result = note::where([
	                                    ['patient_id','=',$input['id']],
	                                    ['physician_id','=',Auth::user()->id],
	                                    ['active','=',True]
	                                ])
	                            ->get();

	            if(is_null($query_result)){
	                $response = [
	                        'status'  => 'fail',
	                        'task'   => null,
	                        'error'   => ['Not Found.']
	                    ];
	            }else{
	            	foreach($query_result as $query){
		                $info = [
		                    'id'           => $query->id,
		                    'title'        => substr($query->title,0,50),
		                    'text'         => $query->text,
		                    'images'       => $query->image,
		                    'exer_data'	   => $query->task_exer_data_id,
		                    'updated_at'   => $query->updated_at
		                ]; 
		                array_push($notes,$info);
		            }
		            $response = [
	                        'status'  => 'success',
	                        'notes'   => $notes
	                    ];
	            }
	          	return response()
	                ->json($response)
	                ->setCallback($request->input('callback'));
	        }
	    }
	    return redirect()->route('dashboard');  
    }
    /**
     *  Get Task_exer_data Notes (list)
     *
     *  @return array
     */
    public function getTaskExerDataNotes(Request $request){
        if ( $request->ajax() ) {
            if(Auth::user()->user_types == 1){
	            $input = $request->only([
	                '_token',
	                'id',
				]);
				$notes = array();
	            $query_result = note::where([
	                                    ['task_exer_data_id','=',$input['id']],
	                                    ['physician_id','=',Auth::user()->id],
	                                    ['active','=',True]
	                                ])
	                            ->get();

	            if(is_null($query_result)){
	                $response = [
	                        'status'  => 'fail',
	                        'task'   => null,
	                        'error'   => ['Not Found.']
	                    ];
	            }else{
	            	foreach($query_result as $query){
		                $info = [
		                    'id'           => $query->id,
		                    'title'        => substr($query->title,0,50),
		                    'text'         => $query->text,
		                    'images'       => $query->image,
		                    'updated_at'   => $query->updated_at
		                ];
		                array_push($notes,$info);
		            }
		            $response = [
	                        'status'  => 'success',
	                        'notes'   => $notes
	                    ];
	            }
	            return response()
	                ->json($response)
	                ->setCallback($request->input('callback'));
	        }
	    }
	    //return redirect()->route('dashboard');  
    }
    /**
     *  post Note
     *
     *  @return array
     */
    public function postNote(Request $request){
    
        if ( $request->ajax() || Auth::user()->user_types == 1) {
            $response = [
                'success'       => "",
                'message'       => []
            ];
            $input = $request->only([
                'task_exer_data_id',
                'patient_id',
                'title',
                'text'
                ]);
            $rules = array(
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
            	if(is_null($input['patient_id']) && !is_null($input['task_exer_data_id'])){
            		$tmp = task_exer_data::where('id','=',intval($input['task_exer_data_id']))->first();
            		$input['patient_id'] = assigned_to::where('id','=',intval($tmp->task_assignment_id))->first()->user_id;
            	}
                $input+=['physician_id'=>Auth::user()->id];
                $input+=['active'=>true];
                
                $newNote = note::create($input);
                
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
                    
                    DB::table('notes')
                            ->where('id','=',$newNote['id'])
                            ->update([
                                'image'      => $db_images,
                        ]);
                        if(!empty($invalidImg)){
                            $response['success'] = False;
                            array_push($response['message'],"Some images failed to upload. Edit to reupload.");
                        }
                }

                $response['success'] = True;
                array_push($response['message'],'Posted in '. task_exer_data::where('id','=',intval($input['task_exer_data_id']))->first()->id);

                return response()
                    ->json($response);
            
            }

        }
        return redirect()->route('dashboard');
    }
}

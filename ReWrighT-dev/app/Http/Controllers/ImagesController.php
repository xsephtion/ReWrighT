<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Image;

use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class imagesController extends Controller
{	
	public function profilePicture($type,$person)
    {
        $image = User::findorFail($person)->userInformation->profile;
        if($image == null)
            $image = "notavailable.jpg";

    	if(!Storage::disk('local')->has($image)){
    		$response = [
    			'success'	=> False,
    			'message'	=> "Image unavailable"
    		];
    		return response()
    			->json($response);
    	}
        $storagePath = Storage::disk('local')->get($image);
        $size = 300;
        if($type == 'f')
            $size = 300;
        else if($type == 't'){
            $size = 60;
        }
       
        return Image::make($storagePath)->resize($size,$size)->response();
    }
	
    public function discussionImage($image)
    {
    	if(!Storage::disk('local')->has($image)){
    		$response = [
    			'success'	=> False,
    			'message'	=> "Image unavailable"
    		];
    		return response()
    			->json($response);
    	}
        $storagePath = Storage::disk('local')->get($image);

        return Image::make($storagePath)->response();
    }
}

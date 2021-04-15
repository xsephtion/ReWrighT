<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use Image;
use Storage;
use Validator;
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
use App\Http\Requests;
use App\Http\Controllers\Controller;

class discussionController extends Controller
{
    /**
     *  Get discussions
     *
     *  @return array
     */
    public function getDiscussions(Request $request){ 
        
        if ( $request->ajax() ) {
            $project_id = $request->get('project');
            $all = $request->get('all');
            $page = $request->get('page')*10;
            
            if($all == "true"){
                if(Auth::user()->users_type == 1){
                    $discussions = discussion_notif::select('discussion_notifs.id as notif_id','discussion_notifs.user_id as notif_user','discussion_notifs.seen as seen','discussion_notifs.read as read','discussions_board.project_id','discussions_board.id as discussion_id','discussions_board.user_id as creator_user','discussions_board.title as title','discussions_board.text as text','discussions_board.image as image','discussions_board.priority as priority','discussions_board.active as active','discussions_board.updated_at','discussions_board.created_at')
                            ->where('discussions_board.project_id','=',$project_id)
                            ->leftjoin('discussions_board','discussion_notifs.discussion_id','=','discussions_board.id')
                            ->orderBy('discussions_board.priority','asc')
                            ->orderBy('discussions_board.updated_at','asc')
                            ->orderBy('discussions_board.created_at','asc')
                            ->skip($page)
                            ->take(10)
                            ->get();
                }else{
                    $discussions = discussion_notif::select('discussion_notifs.id as notif_id','discussion_notifs.user_id as notif_user','discussion_notifs.seen as seen','discussion_notifs.read as read','discussions_board.project_id','discussions_board.id as discussion_id','discussions_board.user_id as creator_user','discussions_board.title as title','discussions_board.text as text','discussions_board.image as image','discussions_board.priority as priority','discussions_board.active as active','discussions_board.updated_at','discussions_board.created_at')
                        ->where('discussions_board.project_id','=',$project_id)
                        ->where('discussion_notifs.user_id','=',intval(Auth::user()->id))
                        ->leftjoin('discussions_board','discussion_notifs.discussion_id','=','discussions_board.id')
                        ->orderBy('discussions_board.priority','asc')
                        ->orderBy('discussions_board.updated_at','asc')
                        ->orderBy('discussions_board.created_at','asc')
                        ->skip($page)
                        ->take(10)
                        ->get();
                }
            }else{
                $patient_id = $request->get("patient_id");
                
                $discussions = discussion_notif::select('discussion_notifs.id as notif_id','discussion_notifs.user_id as notif_user','discussion_notifs.seen as seen','discussion_notifs.read as read','discussions_board.project_id','discussions_board.id as discussion_id','discussions_board.user_id as creator_user','discussions_board.title as title','discussions_board.text as text','discussions_board.image as image','discussions_board.priority as priority','discussions_board.active as active','discussions_board.updated_at','discussions_board.created_at')
                        ->where('discussions_board.project_id','=',$project_id)
                        ->leftjoin('discussions_board','discussion_notifs.discussion_id','=','discussions_board.id')
                        ->where('discussion_notifs.user_id','=',$patient_id)
                        ->orderBy('discussions_board.priority','asc')
                        ->orderBy('discussions_board.updated_at','asc')
                        ->orderBy('discussions_board.created_at','asc')
                        ->skip($page)
                        ->take(10)
                        ->get();
            }

            $set=array();
            foreach($discussions as $discussion){
                $user = User::find(intval($discussion->creator_user))->userInformation; //get info of creator
                $disc = [ 
                        'discussion_id' => $discussion->discussion_id,
                        'notif_id'      => $discussion->notif_id,
                        'profile'       => $user->profile,
                        'first_name'    => $user->first_name,
                        'last_name'     => $user->last_name,
                        'disc_id'       => $discussion->id,
                        'disc_title'    => substr($discussion->title,0,50),
                        'disc_text'     => substr($discussion->text,0,80),
                        'priority'      => $discussion->priority,
                        'updated_at'    => $discussion->updated_at,
                        'read'          => $discussion->read,
                        'seen'          => $discussion->seen,
                        ];
                array_push($set,$disc);
                if($discussion->notif_id == NULL){
                        DB::table('discussion_notifs')->insert(
                                ['discussion_id'=>$discussion->discussion_id,'user_id'=>Auth::user()->id,'seen'=>true]
                            );
                    }else{
                        DB::table('discussion_notifs')
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
     *  get discussion details and contents
     *
     *  @return array
     */
    public function getDiscussion(Request $request){
        if ( $request->ajax() ) {
            $id = $request->get('disc_id');
            $query = discussion::select('*','discussions_board.created_at as created_at','discussions_board.updated_at as updated_at')
                ->where('discussions_board.id','=',$id)
                ->leftjoin('discussion_notifs','discussion_notifs.discussion_id','=','discussions_board.id')
                ->first();

            $project = project::find($query->project_id)->first();
            $user = User::find($query->user_id);
            $disc = [ 
                    'profile'       => $user->userInformation->profile,
                    'first_name'    => $user->userInformation->first_name,
                    'last_name'     => $user->userInformation->last_name,
                    'disc_id'       => $query->id,
                    'disc_title'    => $query->title,
                    'disc_text'     => $query->text,
                    'disc_image'    => $query->image,
                    'disc_priority' => $query->priority,
                    'updated_at'    => $query->updated_at,
                    'project'       => $project->text,
                    'read'          => $query->read,
                    'seen'          => $query->seen
                    ];
            
            
            
            $response = [
                'status'       => 'success',
                'article'      => $disc
            ];
            return response()
                ->json($response)
                ->setCallback($request->input('callback'));
        }
        return redirect()->route('dashboard');
    }
    /**
     *  get comment details and contents
     *
     *  @return array
     */
    public function getComments(Request $request){
        if ( $request->ajax() ) {
            $id = $request->get('disc_id');
            $query = discussion_comment::select('discussion_comments.id','discussion_comments.user_id', 'users_info.profile','users_info.first_name','users_info.last_name','discussion_comments.text','discussion_comments.image','discussion_comments.upvote','discussion_comments.downvote','discussion_comments.created_at as created_at','discussion_comments.updated_at as updated_at')
            ->where('discussion_comments.discussion_id','=',$id)
            ->leftjoin('users_info','users_info.user_id','=','discussion_comments.user_id')
            ->orderBy('created_at','asc')
            ->orderBy('upvote','asc')
            ->orderBy('downvote','asc')
            ->get();
            $set=array();
            foreach($query as $q){
                $cmmnts = [ 
                        'id'            => $q->id,
                        'user_id'       => $q->user_id,
                        'profile'       => $q->profile,
                        'first_name'    => $q->first_name,
                        'last_name'     => $q->last_name,
                        'text'          => $q->text,
                        'image'         => $q->image,
                        'upvote'        => $q->upvote,
                        'downvote'      => $q->downvote,
                        'created_at'    => $q->created_at,
                        'updated_at'    => $q->updated_at,
                        ];
                array_push($set,$cmmnts);
            }
            $response = [
                'status'       => 'success',
                'comments'     => $set,
            ];
            return response()
                ->json($response);
        }
         
    }
    /**
     *  update comment upvote
     *
     *  @return array
     */
    public function postUpvotes(Request $request){
        //0 == neutral (no vote)
        //1 == upvote
        //2 == downvote
        if ( $request->ajax() ) {
            $id = $request->get('id');
            $type = $request->get('type');
            $query = discussion_vote::select(DB::raw('count(*) as vote_count'))
                ->where('discussion_comment_id','=',$id)
                ->first();
            if(intval($query->vote_count)==0){//insert
                DB::table('discussion_votes')
                    ->insertGetId([
                        'discussion_comment_id' => $id,
                        'vote'                  => $type,
                        'user_id'               => Auth::user()->id
                    ]);
            }else{//update
                 $query = discussion_vote::select('*')
                            ->where('discussion_comment_id','=',$id)
                            ->where('user_id','=',Auth::user()->id)
                            ->first();
                 if($type != $query->vote || $query->vote == 0){
                    DB::table('discussion_votes')
                        ->where('discussion_comment_id','=',$id)
                        ->where('user_id','=',Auth::user()->id)
                        ->update(['vote'=>$type]);
                }else if($query->vote == 1){
                    DB::table('discussion_votes')
                        ->where('discussion_comment_id','=',$id)
                        ->where('user_id','=',Auth::user()->id)
                        ->update(['vote'=>0]);
                }else if($query->vote == 2){
                    DB::table('discussion_votes')
                        ->where('discussion_comment_id','=',$id)
                        ->where('user_id','=',Auth::user()->id)
                        ->update(['vote'=>0]);
                }
            }
            
            $upcount = discussion_vote::select(DB::raw('count(*) as vote_count'))
                ->where('discussion_comment_id','=',$id)
                ->where('vote','=',1)
                ->first();
            $downcount = discussion_vote::select(DB::raw('count(*) as vote_count'))
                ->where('discussion_comment_id','=',$id)
                ->where('vote','=',2)
                ->first();
            DB::table('discussion_comments')
                ->where('id','=',$id)
                ->update([
                    'upvote'    => $upcount->vote_count,
                    'downvote'  => $downcount->vote_count,
                    ]);
            $response = [
                'status'        => 'success',
                'upcount'         =>  $upcount,
                'downcount'         =>  $downcount,
            ];
            return response()
                ->json($response)
                ->setCallback($request->input('callback'));
        }
        return redirect()->route('dashboard');
    }
    /**
     *  post discussion on board
     *
     *  @return array
     */
    public function postDiscussion(Request $request){
    
        if ( $request->ajax() ) {
            $response = [
                'success'       => "",
                'message'       => []
            ];
            $input = $request->only([
                'project_id',
                'priority',
                'title',
                'text'
                ]);
            $rules = array(
                'project_id'    => 'required',
                'priority'      => 'required|int',
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

                $input+=['user_id'=>Auth::user()->id];
                $newDisc = discussion::create($input);
                $user_ids = developer::select('user_id')
                        ->where('project_id','=',$newDisc['project_id'])
                        ->get();
                
                                                                        //add feature:: exclusive for team / project/ profs
                foreach($user_ids as $user_id){                    
                   discussion_notif::create([
                        'discussion_id'     => $newDisc['id'],
                        'user_id'           => $user_id['user_id'],
                        'seen'              => False,
                        'read'              => False,
                    ]);
                }
                
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
                    
                    DB::table('discussions_board')
                            ->where('id','=',$newDisc['id'])
                            ->update([
                                'image'      => $db_images,
                        ]);
                        if(!empty($invalidImg)){
                            $response['success'] = False;
                            array_push($response['message'],"Some images failed to upload. Edit to reupload.");
                        }
                }

                $response['success'] = True;
                array_push($response['message'],'Posted in '. project::find($newDisc["project_id"])->text);

                return response()
                    ->json($response);
            
            }

        }
        return redirect()->route('dashboard');
    }
     /**
     *  post Comment on board
     *
     *  @return array
     */
    public function postDiscussionComment(Request $request){
        if ( $request->ajax() ) {
            $response = [
                'success'       => "",
                'message'       => []
            ];
            $input = $request->only([
                'discussion_id',
                'text'
                ]);
            $rules = array(
                'discussion_id' => 'required',
                'text'          => 'required|string'
            );


            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                $response['success'] = False;
                $response['message'] = $validator->errors();
                return response()
                    ->json($response)
                    ->setCallback($request->input('callback'));                   
            }else{

                $input+=['user_id'=>Auth::user()->id];

                $tmp = discussion::find($input['discussion_id']);

                $temp = new discussion_comment;
                $temp->user_id = Auth::user()->id;
                $temp->text = $input['text'];

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
                    $temp->image = rtrim($db_images, ',');
                        if(!empty($invalidImg)){
                            $response['success'] = False;
                            array_push($response['message'],"Some images failed to upload. Edit to reupload.");
                        }
                }

                $tmp->comments()->save($temp);
                

                $response['success'] = True;
                array_push($response['message'],'Posted in '. discussion::find($temp["discussion_id"])->title);

                return response()
                    ->json($response);
            }
        }
        return redirect()->route('dashboard');
    }
    /**
     *  get Comment COUNT on board
     *
     *  @return array
     */
    public function postDiscussionCommentCnt(Request $request){
        if($request->ajax()){
            $input = $request->only([
                'id'
            ]);

            $count = discussion_comment::where('discussion_id','=',$input['id'])
                    ->count();
            
            $response = [
                'success' => True,
                'cnt' => $count
            ];
            return response()
                ->json($response);
        }
    }
    /**
     *  get Comment COUNT on board
     *
     *  @return array
     */
    public function postDiscussionNotifsCnt(Request $request){
        if($request->ajax()){
            $input = $request->only([
                'project_id',
                'all'
                ]);
            var_dump(Auth::user());
            $count = discussion_notif::leftjoin('discussions_board','discussion_notifs.discussion_id','=','discussions_board.id')
                    ->where('discussion_notifs.user_id','=',Auth::user()->id)
                    ->where('discussion_notifs.seen','=',false)
                    ->count();
            
            
            $response = [
                'success' => True,
                'cnt' => $count
            ];
            return response()
                ->json($response);
        }
    }
}
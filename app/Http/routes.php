<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
	//Route::auth();
	Route::get('/', 'indexController@index');
	//Route::group(['middleware' => 'cors'], function(){
												//Index Page
	//Login routes
	    Route::get('auth/admin/login','Auth\AuthController@showAdminLoginForm');
		Route::post('auth/admin/login', ['as' => 'loginAdmin', 'uses'=> 'Auth\AuthController@loginAdmin']);
		Route::get('auth/login','Auth\AuthController@showLoginForm');
		Route::post('auth/login', ['as' => 'login', 'uses'=> 'Auth\AuthController@login']);
	//Profile routes
		Route::post('auth/profile/save/', ['as' => 'postEditProfile', 'uses' =>'userController@saveEditUserProfile']);	//ajax request
		Route::get('auth/profile/edit/{code}','userController@editUserProfile1');
		Route::post('auth/profile/edit/{code}', ['as' => 'editProfile', 'uses'=> 'userController@editUserProfile1']);
		
		Route::post('auth/search/patient', ['as' => 'getPatientSrch', 'uses' => 'userController@getPatientSrch']);
		Route::get('auth/search/patient', 'userController@getPatientSrch');

	// Registration routes	
		//Route::get('auth/activate', 'Auth\AuthController@getRegister');
		//Route::post('auth/activate', ['as' => 'register', 'uses' => 'Auth\AuthController@postRegister']);
		
		Route::post('admin/auth/register', ['as' => 'registerByAdmin', 'uses' => 'adminController@registerByAdmin']);
		Route::get('admin/auth/register', 'adminController@registerByAdmin');

		Route::post('admin/auth/register/getCode', ['as' => 'getActivationCode', 'uses' => 'adminController@getActivationCode']);
		Route::get('admin/auth/register/getCode', 'adminController@getActivationCode');
	//Patient group (admin only)
		Route::post('admin/auth/search/PatientGroupInfo', ['as' => 'getPatientGroup', 'uses' => 'adminController@getPatientGroup']);
		Route::get('admin/auth/search/PatientGroupInfo', 'adminController@getPatientGroup');

		Route::post('/admin/auth/updatepgcount', ['as' => 'pgUpdateCount', 'uses' =>'adminController@postUpdatePGCount']);	//ajax request
		Route::get('/admin/auth/updatepgcount', 'adminController@postUpdatePGCount');
	//logout routes
		Route::get('/admin/logout',['as' => 'logoutAdmin', 'uses'=>'userController@getLogoutAdmin']);	
		Route::get('/logout',['as' => 'logout', 'uses'=>'userController@getLogout']);				// Logout route
	// Dashboard Routes
		Route::get('/admin/dashboard', ['as'=>'dashboardAdmin','uses'=>'userController@dashboardAdmin']);
		Route::get('/dashboard/', ['as'=>'dashboard','uses'=>'userController@dashboard']);
		Route::get('/dashboard2', ['as'=>'dashboard2','uses'=>'userController@dashboard2']);
		//Route::post('/dashboard', ['as' => 'editProfile', 'uses' => 'userController@editUserProfile2']);
	// Discussions
		Route::post('/discussionBoard', ['as' => 'discussionBoard', 'uses' =>'discussionController@getDiscussions']);	//ajax request
		Route::get('/discussionBoard', 'discussionController@getDiscussions');

		Route::post('/discussion', ['as' => 'discussion', 'uses' =>'discussionController@getDiscussion']);	//ajax request
		Route::get('/discussion', 'discussionController@getDiscussion');

		Route::post('/discussion/comments', ['as' => 'discComments', 'uses' =>'discussionController@getComments']);	//ajax request
		Route::get('/discussion/comments/{$id}', 'discussionController@getComments');

		Route::post('/discussion/upvotes', ['as' => 'discUpvotes', 'uses' =>'discussionController@postUpvotes']);	//ajax request
		Route::get('/discussion/upvotes', 'discussionController@postUpvotes');	

		Route::post('/post/discussion', ['as' => 'postDiscussion', 'uses' =>'discussionController@postDiscussion']);	//ajax request
		Route::get('/post/discussion', 'discussionController@postDiscussion');

		Route::post('/post/discussion/comment', ['as' => 'postDiscussionComment', 'uses' =>'discussionController@postDiscussionComment']);	//ajax request
		Route::get('/post/discussion/comment', 'discussionController@postDiscussionComment');
		//refresh routes
		Route::post('/post/discussion/comment/cnt', ['as' => 'postDiscussionCommentCnt', 'uses' =>'discussionController@postDiscussionCommentCnt']);	//ajax request
		Route::get('/post/discussion/comment/cnt', 'discussionController@postDiscussionCommentCnt');

		Route::post('/post/discussion/notifs/cnt', ['as' => 'postDiscussionNotifsCnt', 'uses' =>'discussionController@postDiscussionNotifsCnt']);	//ajax request
		Route::get('/post/discussion/notifs/cnt', 'discussionController@postDiscussionNotifsCnt');
	//Tasks	
		Route::post('/taskBoard', ['as' => 'taskBoard', 'uses' =>'taskController@getTasks']);	//ajax request
		Route::get('/taskBoard', 'taskController@getTasks');	//ajax request

		Route::post('/taskBoard/info', ['as' => 'taskBoardInfo', 'uses' =>'taskController@getTask']);	//ajax request
		Route::get('/taskBoard/info', 'taskController@getTask');	//ajax request

	//Images
		Route::get('/profile/image/{type}/{person}', 'imagesController@profilePicture');
		Route::get('/profile/image/get/{type}/{image}', 'imagesController@forcedGetPicture');
		Route::get('/discussion/image/{image}', 'imagesController@discussionImage');
	//Projects
		Route::post('/project/getProjects', ['as' => 'getProjects', 'uses' =>'projectController@getProjects']);	//ajax request
		Route::get('/project/getProjects', 'projectController@getProjects');	//ajax request

		Route::post('/project/joinProject/{id}', ['as' => 'joinProject', 'uses' =>'projectController@joinProject']);	//ajax request
		Route::get('/project/joinProject/{id}', 'projectController@joinProject');	//ajax request\

		Route::get('/tasks/', 'taskController@openTasks');
		Route::get('/tasks/{id}', 'taskController@openTask');				//ajax request\
		Route::get('/preview/{id}', 'taskController@reviewExercise');				//ajax request\
		Route::post('/recordings/lab', ['as' => 'vanillaLab', 'uses' =>'taskController@vanillaLab']);	//ajax 
		Route::get('/recordings/lab', 'taskController@vanillaLab');				//ajax request\
		Route::post('/recordings/exer/result/', 'taskController@getResultData');	//ajax request\
		Route::get('/recordings/exer/{id}', 'taskController@getExerDataTask');	//ajax request\
		Route::get('/recordings/patient_exer/{id}', 'taskController@getPatientExerDataTask');	//ajax request\
		Route::get('/recordings/training/{id}', 'taskController@getTrainingDataTask');	//ajax request\
		Route::get('/recordings/preview/{id}', 'taskController@getExerData');	//ajax request\


		Route::post('/post/task', ['as' => 'postTask', 'uses' =>'taskController@postTask']);	//ajax 
		Route::get('/post/task', 'taskController@postTask');
		Route::post('/post/task/patient', ['as' => 'postTaskPatient', 'uses' =>'taskController@postTaskPatient']);	//ajax 
		Route::get('/post/task/patient', 'taskController@postTaskPatient');
		Route::post('/post/task/patient/result', ['as' => 'postTaskPatientResult', 'uses' =>'taskController@postTaskPatientResult']);	//ajax 
		Route::get('/post/task/patient/result', 'taskController@postTaskPatientResult');

		Route::post('/post/exerdata/list', ['as' => 'postExerciseDataList', 'uses' =>'taskController@postExerciseDataList']);	//ajax 
		Route::get('/post/exerdata/list', 'taskController@postExerciseDataList');

		Route::post('/post/patient/list', ['as' => 'postExerciseDataList', 'uses' =>'projectController@postPatientDataList']);	//ajax 
		Route::get('/post/patient/list', 'projectController@postPatientDataList');

		Route::post('/post/exerdata', ['as' => 'postExerciseData', 'uses' =>'taskController@postExerciseData']);	//ajax 
		Route::get('/post/exerdata', 'taskController@postExerciseData');
		Route::post('/post/exerdata/adjustedScore', ['as' => 'postAdjustedScore', 'uses' =>'taskController@postAdjustedScore']);	//ajax 
		Route::get('/post/exerdata/adjustedScore', 'taskController@postAdjustedScore');

	//Tasks	
		Route::post('/note/list/', ['as' => 'noteList', 'uses' =>'notesController@getPatientNotes']);	//ajax request
		Route::get('/note/list/', 'notesController@getPatientNotes');	//ajax request
		Route::post('/note/list/task', ['as' => 'noteListTask', 'uses' =>'notesController@getTaskExerDataNotes']);	//ajax request
		Route::get('/note/list/task', 'notesController@getTaskExerDataNotes');	//ajax request 
		Route::post('/note', ['as' => 'noteInfo', 'uses' =>'notesController@getNotegetNote']);	//ajax request
		Route::get('/note', 'notesController@getNote');	//ajax request 

		Route::post('/post/note', ['as' => 'postNote', 'uses' =>'notesController@postNote']);	//ajax 
		Route::get('/post/note', 'notesController@postTask');
	//});
    
}); 
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
    Route::get('/', 'indexController@index');										//Index Page
//Login routes
    Route::get('auth/admin/login','Auth\AuthController@showAdminLoginForm');
	Route::post('auth/admin/login', ['as' => 'loginAdmin', 'uses'=> 'Auth\AuthController@loginAdmin']);
	Route::get('auth/login','Auth\AuthController@showLoginForm');
	Route::post('auth/login', ['as' => 'login', 'uses'=> 'Auth\AuthController@login']);

// Registration routes	
	//Route::get('auth/activate', 'Auth\AuthController@getRegister');
	//Route::post('auth/activate', ['as' => 'register', 'uses' => 'Auth\AuthController@postRegister']);
	
	Route::post('admin/auth/register', ['as' => 'registerByAdmin', 'uses' => 'adminController@registerByAdmin']);
	Route::get('admin/auth/register', 'adminController@registerByAdmin');

	Route::post('admin/auth/register/getCode', ['as' => 'getActivationCode', 'uses' => 'adminController@getActivationCode']);
	Route::get('admin/auth/register/getCode', 'adminController@getActivationCode');
//logout routes
	Route::get('/admin/logout',['as' => 'logoutAdmin', 'uses'=>'UserController@getLogoutAdmin']);	
	Route::get('/logout',['as' => 'logout', 'uses'=>'UserController@getLogout']);											// Logout route
// Dashboard Routes
	Route::get('/admin/dashboard', ['as'=>'dashboardAdmin','uses'=>'userController@dashboardAdmin']);
	Route::get('/dashboard', ['as'=>'dashboard','uses'=>'userController@dashboard']);
	Route::get('/dashboard2', ['as'=>'dashboard2','uses'=>'userController@dashboard2']);
	Route::post('/dashboard', ['as' => 'editProfile', 'uses' => 'userController@editUserProfile']);
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
	Route::get('/tasks/{id}', 'taskController@openTask');	//ajax request\

});

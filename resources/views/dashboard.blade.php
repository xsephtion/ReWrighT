@extends('master')

@section('content')
	
	<header>
		<nav class="top-nav grey darken-4" >
			<a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">dashboard</i></a>			
			<ul class="top-nav hide-on-med-and-down right">
				<li><a href="{!! URL::to('logout') !!}">logout</a></li>
			</ul>
			<ul id="nav-desktop" class="side-nav fixed">
				<li class="no-padding">
					<a href="#!">
						<i class="material-icons left">schedule</i> Timeline
						{{-- timeline --}}
					</a>
				</li>										
				<li class="no-padding">
					<a href="discussionBoard" id='db_disc_board' class='boards' data-link="{{ route('discussionBoard') }}" data-token="{{ csrf_token() }}">
					
						<i class="material-icons left">question_answer</i>Thread {{-- discussions board --}}
					</a>
				</li>			
				<li class="no-padding"><!-- task timeline -->
					<a href="taskBoard" id='db_task_board' class='boards' data-link="{{ route('taskBoard') }}" data-token="{{ csrf_token() }}">
						<i class="material-icons left">work</i>	Tasks			{{-- tasks board --}}
					</a>
				</li>							
				
				<li class="no-padding" id='db_profile'>
					<a href="#m_personal" class="modal-trigger">
						<i class="material-icons left">face</i> Profile	{{-- Profile --}}
					</a>
				</li>
				<li class="no-padding"><!-- task timeline -->
					<a href="#db_patients" id='db_patients' class='boards' data-link="{{ route('noteList') }}" data-token="{{ csrf_token() }}">
						<i class="material-icons left">perm_identity</i>	Patients			{{-- patients --}}
					</a>
				</li>	
				<li class="no-padding" id='db_setting'>
					<a href="#m_settings" class="modal-trigger">
						<i class="material-icons left">settings</i>	Settings		{{-- Settings --}}
					</a>
				</li>
				
				
		    </ul>
		    <ul id="slide-out" class="side-nav">
				<li><a href="#!"><i class="material-icons left">schedule</i>Timeline</a></li>				{{-- timeline --}}
				<li><a href="discussionBoard" id='db_disc_board' class='boards' data-link="{{ route('discussionBoard') }}" data-token="{{ csrf_token() }}"><i class="material-icons left ">question_answer</i>Thread</a></li>	{{-- discussions board --}}
				<li><a href="taskBoard" id='db_task_board' class='boards' data-link="{{ route('taskBoard') }}" data-token="{{ csrf_token() }}"><i class="material-icons left">work</i>Tasks</a></li>						{{-- tasks board --}}
				<li class="no-padding">																		{{-- Profile --}}
					<ul class="collapsible collapsible-accordion">
						<li>
							<a class="collapsible-header"><i class="material-icons left">face</i>{{ Auth::user()->username }}</a>
							<div class="collapsible-body">
								<ul>
									<ul>
									<li><a href="#m_personal" class="modal-trigger">View Profile</a></li>
									<li><a href="{!! URL::to('logout') !!}"><i class="material-icons">exit_to_app</i></a></li>
								</ul>
								</ul>
							</div>
						</li>
					</ul>
				</li>
				@if(Auth::user()->user_types ==1)
				<li><a href="db_patients" id='db_patients' class='boards' data-link="{{ route('noteList') }}" data-token="{{ csrf_token() }}"><i class="material-icons left">work</i>Patients</a></li>						{{-- tasks board --}}
				@endif
				<li><a href="#m_settings" class="modal-trigger"><i class="material-icons left">settings</i>Settings</a></li>				{{-- Settings --}}
				
		    </ul>
		</nav>
	</header>
	<main>
		<!-- General Body -->

		<div class="fixed-action-btn horizontal click-to-toggle" style="bottom: 45px; right: 24px;">
			<a class="btn-floating btn-large red modal-trigger tooltipped" href="#m_postings" data-position="top" data-delay="50" data-tooltip="Threads... Tasks... Exercises">
				<i class="large material-icons">add</i>
			</a>
		</div>
		<div id="genContent" style="min-height:520px; ">
			<div class="row">
				<div class="col s12 m12 l12">
					<div class="row">
						<div class="col s12 m9 l7 offset-l3">
							<div id="div_details" class="section scrollspy">
								
							</div>

							<div id="div_contents" class="section scrollspy">
								
							</div>

							<div id="div_comments" class="section scrollspy">
								<div class="row">
									<script type="text/javascript">
										var commTextCntr = 1;
										var commImageCntr = 0;
										var commHighlightCntr = 0;
									</script>
									{!!Form::open(['route'=>'postDiscussionComment','id' => 'postComment', 'files'=>'true'])!!}
										<div id="addl_post_comment" class="col s12">
											<textarea class="materialize-textarea col s12" id="text[0]" name="text[]" placeholder="What do you want to say?"></textarea>
										</div>
										<div id="comment_toolbar" class="col s12">
											<a class="tooltipped btn-flat btn-large white col s3" data-position="top" data-delay="50" data-tooltip="Add Image" href="#upImageComment"><i class="material-icons">picture_in_picture</i></a>
											<a class="tooltipped btn btn-flat btn-large white col s3" data-position="top" data-delay="50" data-tooltip="Highlight text" href="#upHighlightComment"><i class="material-icons">"</i></a>
											<a class="tooltipped btn-flat btn-large white col s3" data-position="top" data-delay="50" data-tooltip="Add more description" href="#upDescComment"><i class="material-icons">description</i></a>
											<a class="btn btn-large btn-flat white waves-effect waves-green col s3" href="#comment">Comment</a>
										</div>
									{!!Form::close()!!}
								</div>
								<div id="lsComments" ></div>
							</div>
						</div>
						<div class="col hide-on-small-only m3 l2">
							<div class="tabs-wrapper pinned">
								<div style="height:1px;">
									<ul class="section table-of-contents">
										<li><a href="#div_details">Details</a></li>
										<li><a href="#div_contents">Contents</a></li>
										<li><a href="#div_comments">Comments</a></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="fixed-action-btn" style="bottom: 110px; right: 24px;">
							<a class="btn-floating btn-large modal-trigger tooltipped" href="#modal2" data-position="top" data-delay="50" data-tooltip="Threads">
								<i class="large material-icons">view_list</i>
							</a>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<div id="genContentTask" style ="display:none;">
			<div class="modal-content container">
				<ul class="collapsible popout">
				</ul>
			</div>
		</div>
		@if(Auth::user()->user_types == 1)
		<div id="genPatientBoard" style ="display:none;">
			<div class="row">
				<div class="col s12 m12 l7 offset-l3">
					<div class="search-wrapper card">
						{!! Form::open(['route'=>'getPatientSrch','id'=>'f_getPatientSrch']) !!}
						{!! csrf_field() !!}
							<div class="input-field">
								<input type="search" id="search" name = "search" class = "btn tooltipped" data-position="bottom" data-delay="50" data-tooltip="Patient email/name" required = "required" onkeyup="searchPatient();" disabled="disabled" />
								<label for="search"><i class="material-icons">search</i></label>
								<i class="material-icons">close</i>
							</div>
						{!! Form::close() !!}
						</div>
					
					<div class="modal-content container">
						<ul id="ul_srch_res_pat" class = "collapsible popout"></ul>
					</div>
					
				</div>
			</div>
		</div>
		@endif
		<!-- End of General Body -->
		<!-- Modals -->
		
		<div id="m_personal" class="modal modal-fixed-footer">
			
			<div class="modal-content">
				<h4>Profile</h4>
				
					<div class = "row">
						<div class="col s12 m10 l10">
							<div id="div_pdetails" class="section scrollspy">
								<div class = "row">
									<div class="col l6 m6 s12">
										<input type="text" id="email" name="email" value = "{{ Auth::user()->email }}" disabled="disabled">
										{!! Form::hidden('h_email',Auth::user()->email,['id'=>'h_email']) !!}
										<label for="email">E-mail</label>
									</div>
									<div class="col l6 m6 s12">
										<input type="text" id="code" name="code" value = "{{ Auth::user()->activation_code }}" disabled="disabled">
										{!! Form::hidden('h_code',Auth::user()->activation_code,['id'=>'h_code']) !!}
										<label for="code">Activation code</label>
									</div>
								</div>
								<div class = "row">
									<div class="col l3 m3">
										<input type="text" id="fname" name="fname" value = "{{ Auth::user()->userInformation->first_name }}">
										<label for="fname">First Name</label>
									</div>
									<div class="col l3 m3">
										<input type="text" id="mname" name="mname" value = "{{ Auth::user()->userInformation->middle_name }}">
										<label for="mname">Middle Name</label>
									</div>
									<div class="col l3 m3">
										<input type="text" id="lname" name="lname" value = "{{ Auth::user()->userInformation->last_name }}">
										<label for="lname">Last Name</label>
									</div>
									<div class="col l3 m3">
										<input type="text" id="suffix_name" name="suffix_name" value = "{{ Auth::user()->userInformation->suffix_name }}">
										<label for="suffix_name">Suffix</label>
									</div>
								</div>
								<div class="col s12 m6 l6">
									<label for="sex">Gender:</label>
									<select id="sex" name="sex" value = "{{ Auth::user()->userInformation->sex }}">
										@if(is_null(Auth::user()->userInformation->sex))
											<option disabled selected></option>
										@else
											<option disabled></option>
										@endif
										@if(Auth::user()->userInformation->sex === 'MALE')
											<option value='MALE' selected>Male</option>
										@else
											<option value='MALE'>Male</option>
										@endif
										@if(Auth::user()->userInformation->sex === 'FEMALE')
											<option value='FEMALE' selected>FEMALE</option>
										@else
											<option value='FEMALE'>FEMALE</option>
										@endif
										
									</select>
								</div>
								<div class = "row">
									<div class="col l12 m12 s12">
										<input type="text" id="perm_address" name="perm_address" value = "{{ Auth::user()->userInformation->perm_address }}">
										<label for="perm_address">Perm. Address</label>
									</div>
								</div>
								<div class = "row">
									<div class="col l12 m12 s12">
										<input type="text" id="tempo_address" name="tempo_address" value = "{{ Auth::user()->userInformation->perm_address }}">
										<label for="tempo_address">Temp. Address</label>
									</div>
								</div>
								<div class = "row">
									<div class="col l12 m12 s12">
										<input type="text" id="office_address" name="office_address" value = "{{ Auth::user()->userInformation->office_address }}">
										<label for="office_address">Office Address</label>
									</div>
								</div>
							</div>

							<div id="div_mdetails" class="section scrollspy">
								
							</div>
						</div>
						<div class="col hide-on-small-only m1 l1">
							<div class="tabs-wrapper pinned">
								<div style="height:1px;">
									<ul class="section table-of-contents">
										<li><a href="#div_pdetails">Personal Information</a></li>
										
									</ul>
								</div>
							</div>
						</div>
					</div>
					
	
				<!--div id="div_extra_dtls"></div-->
			</div>
			
		</div>
		<div id="m_settings" class="modal modal-fixed-footer">
			<div class="modal-content">
				<h4>Setting</h4>
				<p>Sample Settings Here.</p>
			</div>
			<div class="modal-footer">
				<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Agree</a>
			</div>
		</div>
		<div id="m_postings" class="modal modal-fixed-footer">
			<div class="modal-content">
				<div class="row">
					<ul class="tabs" id="post_tabs">
						<li class="tab col s3"><a class="red-text text-accent-4" href="#post_disc">Thread</a></li>
						<li class="tab col s3"><a class="red-text text-accent-4" href="#post_patient">Patient</a></li>
						<li class="tab col s3"><a class="red-text text-accent-4" href="#post_task">Assign Task</a></li>
						<li class="tab col s3"><a class="red-text text-accent-4" href="#post_exercise">Exercises</a></li>
						<div class="indicator red darken-4" style="z-index:1"></div>
					</ul>
					<div id="post_disc" class="row">
					{!! Form::open(['route'=>'postDiscussion','id'=>'postDiscussion','files'=>'true']) !!}
						{!! Form::hidden('pd_project',Auth::user()->projects[1]->project_id,['id'=>'pd_project']) !!}
						
						<div class="col s12 m6 l6">
							<label for="pd_priority">Priority (urgency):</label>
							<select id="pd_priority" name="pd_priority">
								<option disabled></option>
								<option value='5'>5</option>
								<option value='4'>4</option>
								<option value='3'>3</option>
								<option value='2'>2</option>
								<option value='1'>1</option>
							</select>
						</div>
						<input type="text" id="title" name="title">
						<label for="title">Subject</label>
						
						<div id="addl_post_disc">
							<script type="text/javascript">
								var discTextCntr = 1;
								var discImageCntr = 0;
								var discHighlightCntr = 0;
							</script>
							<textarea class="materialize-textarea" id="text[0]" name="text[]" placeholder="What do you want to say?"></textarea>
						
						</div>
					{!! Form::close() !!}
						<div class="col s12">
						<br/>
							<a class="tooltipped waves-effect waves-red btn-flat btn-large white col s4 red-text text-darken-3" data-position="top" data-delay="50" data-tooltip="Add Image" href="#upImageDisc"><i class="material-icons">picture_in_picture</i></a>
							<a class="tooltipped waves-effect waves-red btn-flat btn-large white col s4 red-text text-darken-2" data-position="top" data-delay="50" data-tooltip="Highlight text" href="#upHighlightDisc"><i class="material-icons" href="#upHighlightDisc">"</i></a>
							<a class="tooltipped waves-effect waves-red btn-flat btn-large white col s4 red-text text-darken-1" data-position="top" data-delay="50" data-tooltip="Add more description" href="#upDescDisc"><i class="material-icons">description</i></a>
						</div>
					</div>
					<div id="post_task" class="col s12">Assign Task
					{!! Form::open(['route'=>'postTask','id'=>'postTask','files'=>'true']) !!}
						<!-- change to: if pt his user_id-->
						{!! Form::hidden('pd_project',Auth::user()->projects[1]->project_id,['id'=>'pd_project']) !!}
						
						<input type="text" id="title" name="title">
						<label for="title">Subject</label>
						<div class="input-field">
							<select id="freq" name="freq" required="required">
								<option value="1" selected>1 day</option>
								<option value="2" >2 day</option>
								<option value="3" >3 day</option>
								<option value="4" >4 day</option>
								<option value="5" >5 day</option>
								<option value="6" >6 day</option>
								<option value="7" >7 day</option>
								<option value="8" >8 day</option>
								<option value="9" >9 day</option>
								<option value="10" >10 day</option>
								<option value="11" >11 day</option>
								<option value="12" >12 day</option>
								<option value="13" >13 day</option>
								<option value="14" >14 day</option>
								<option value="15" >15 day</option>
							</select>
							<label>Number of day/s</label>
						</div>
						<div class="input-field">
							<select id="leapData" name="leapData" multiple="multiple" required="required" onfocus = "populateExerData();">
								<option value="" disabled selected>Choose your option</option>
								
							</select>
							<label>Exercise Data:</label>
						</div>
						<div class="input-field"> 
							<select id="patientData" name="patientData" multiple="multiple" required="required">
								<option value="" disabled selected></option>
								
							</select>
							<label>Assign to Patient/s:</label>
						</div>
						<div id="addl_post_task">
							<script type="text/javascript">
								var taskTextCntr = 1;
								var taskImageCntr = 0;
								var taskHighlightCntr = 0;
							</script>
							<textarea class="materialize-textarea" id="text[0]" name="text[]" placeholder="What do you want to say?"></textarea>
						
						</div>
					{!! Form::close() !!}
						<div class="col s12">
						<br/>
							<a class="tooltipped waves-effect waves-red btn-flat btn-large white col s4 red-text text-darken-3" data-position="top" data-delay="50" data-tooltip="Add Image" href="#upImageTask"><i class="material-icons">picture_in_picture</i></a>
							<a class="tooltipped waves-effect waves-red btn-flat btn-large white col s4 red-text text-darken-2" data-position="top" data-delay="50" data-tooltip="Highlight text" href="#upHighlightTask"><i class="material-icons" href="#upHighlightDisc">"</i></a>
							<a class="tooltipped waves-effect waves-red btn-flat btn-large white col s4 red-text text-darken-1" data-position="top" data-delay="50" data-tooltip="Add more description" href="#upDescTask"><i class="material-icons">description</i></a>
						</div>
					</div>
					<div id="post_patient" class="col s12">
						<div class="col s12">
						<ul class = "pagination">
							<li class = "active" id = "li_p_add"><a id = "a_p_add" href="#!">Add</a></li>
							<li id = "li_find"><a id = "a_find" href="#!">Get Code</a></li>
						</ul>
						
					</div>
					<div id="post_patient_add" class="col s12">
						<nav class = "hide-on-large-only red darken-4">
							<div class="nav-wrapper">
								<div class="col s12">
									<a href="#!" class="breadcrumb">Add Patient/s</a>
								</div>
							</div>
						</nav>
						{!! Form::open(['route'=>'registerByAdmin','id'=>'f_activation']) !!}
						{!! csrf_field() !!}
							
							{!! Form::email('email',null,['class'=>'form-control']) !!}
							<label for="email">Email</label>
							<br/><br/>
							
						{!! Form::close() !!}<button id="sub" class="btn waves-effect red darken-4" onclick="submitActivationForm('f_activation')">Get Code
								<i class="material-icons right">done</i>
							</button>
						<div id = "f_activation_div" class="input-field col s12"></div>
					</div>
					<div id="post_patient_find" class="col s12" style = "display:none;">
						<nav class = "hide-on-large-only red darken-4">
							<div class="nav-wrapper">
								<div class="col s12">
									<a href="#!" class="breadcrumb">Activation code</a>
								</div>
							</div>
						</nav>
						{!! Form::open(['route'=>'getActivationCode','id'=>'f_Code']) !!}
						{!! csrf_field() !!}
							
							{!! Form::email('email',null,['class'=>'form-control']) !!}
							<label for="email">Email</label>
							<br/><br/>
							
						{!! Form::close() !!}<button id="sub" class="btn waves-effect red darken-4" onclick="submitActivationForm('f_Code')">Get Code
								<i class="material-icons right">done</i>
							</button>
						<div id = "f_Code_div" class="col s12"></div>
					</div>
					</div>
					<div id = "post_exercise" class = "col s12">
						<div class="col s12">
							<ul class = "pagination">
								<li class = "active" id = "li_e_add"><a id = "a_e_add" href="#!">Add</a></li>
								<li id = "li_e_view"><a id = "e_view" href="#!">View</a></li>
							</ul>
							
						</div>
						<div id="post_add_exercise" class="col s12">
							<nav class = "hide-on-large-only red darken-4">
								<div class="nav-wrapper">
									<div class="col s12">
										<a href="#!" class="breadcrumb">Add Exercise</a>
									</div>
								</div>
							</nav>
							{!! Form::open(['route'=>'postExerciseData','id'=>'postExerciseData','files'=>'true']) !!}
							{!! csrf_field() !!}
								<input type="text" id="title" name="title">
								<label for="title">Description</label>
								<div class="file-field input-field">
									<div class="btn"><span>LeapMotion</span>
										<input id="leapData" name="leapData" type="file" accept=".json.lz" required="required">
									</div>
									<div class="file-path-wrapper">
										<input id="leapDataPath[0]" name="leapDataPath" class="file-path validate" type="text">
									</div>
								</div>
							{!! Form::close() !!}
							<div class="input-field" style="position: absolute; bottom:10px;right:20px;"><a class="btn-large tooltipped" href = "{{route('vanillaLab')}}" target = "_blank" data-position="top" data-tooltip="Create Exercise/s"><i class="large material-icons">developer_mode</i></a></div>
						</div>
						<div id="post_view_exercise" class="col s12" style = "display:none;">
							<nav class = "hide-on-large-only red darken-4">
								<div class="nav-wrapper">
									<div class="col s12">
										<a href="#!" class="breadcrumb">Exercise/s</a>
									</div>
								</div>
							</nav>
							<div id="div_view_exercises" class = "col s12">
							{!! Form::open(['route'=>'postExerciseDataList','id'=>'postExerciseDataList']) !!}
							{!! csrf_field() !!}
							{!! Form::close() !!}
								<ul class="collection" id="ul_view_exercises"></ul>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<div class="modal-footer">
				<a href="#post" class="modal-action modal-close waves-effect waves-green btn-flat ">Post</a>
			</div>
		</div>
		<script type="text/javascript">
			var notifPage = 0;
		</script>
		<div id="modal2" class="modal bottom-sheet">
			<div class="modal-content container">
				<ul class="collection">
				</ul>
			</div>
		</div>
		<!-- End of modals -->
	</main>
	
    <script type="text/javascript">
        var board = "db_disc_board";
        var project_id = <?php echo Auth::user()->projects[1]->project_id; ?>;
        var user_type = <?php echo Auth::user()->user_types; ?>;
        var discussions=[];
        //var tasks=[];
        var general ={details:'',content:'',comment:''};
        var displayed_id;
        var exerDataList = [];
        var patientDataList = [];

    </script>

@stop
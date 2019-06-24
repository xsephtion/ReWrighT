@extends('masterAdmin')

@section('content')

	<div id='top'></div>
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
					<ul class="collapsible collapsible-accordion">
						<li>
							<a href="#!" id='db_task_board' class="collapsible-header">
								<i class="material-icons left">work</i>Manage {{-- tasks board --}}
							</a>
							<div class="collapsible-body">
								<ul>
									<li id="userManage" class="red-text text-darken-4"><a onclick="">User</a></li>
									<li id="taskManage" class="red-text text-darken-4"><a onclick="">Tasks</a> </li>
								</ul>
							</div>
						</li>
					</ul>						
				</li>
				<li class="no-padding" id='db_profile'>
					<a href="#m_personal" class="modal-trigger">
						<i class="material-icons left">perm_identity</i> Profile	{{-- Profile --}}
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
				<li><a href="#!"><i class="material-icons left">work</i>Tasks</a></li>						{{-- tasks board --}}
				<li class="no-padding">																		{{-- Profile --}}
					<ul class="collapsible collapsible-accordion">
						<li>
							<a class="collapsible-header"><i class="material-icons left">perm_identity</i>{{ Auth::user()->username }}</a>
							<div class="collapsible-body">
								<ul>
									<li class="red-text text-darken-4">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspUpdate Profile</li>
									<li class="red-text text-darken-4">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspAccount Settings</li>
									<li><a href="{!! URL::to('logout') !!}" class="red-text text-darken-4">logout</a></li>
								</ul>
							</div>
						</li>
					</ul>
				</li>
				<li><a href="#m_settings" class="modal-trigger"><i class="material-icons left">settings</i>Settings</a></li>				{{-- Settings --}}
		    </ul>
		</nav>
	</header>
	<main>
		<!-- General Body -->
		<div id="genContent" style="min-height:520px; ">
			<div class="row">
				<div class="col s12 m12 l12">
					<div class="row">
						<div class="col s12 m9 l7 offset-l3">
							<div class="row">
								<div id = "userManagement">
									<div class="col s12" >
										<ul class="tabs">
											<li class="tab col s3"><a class="active" href="#createUser">Create User</a></li>
											<li class="tab col s3"><a href="#getActivationCode">Get Activation Code</a></li>
											<li class="tab col s3 disabled"><a href="#editUser">Edit User</a></li>
											<li class="tab col s3 disabled"><a href="#addGroupSize">Add patient number</a></li>
										</ul>
									</div>
									<div id="createUser" class="col s12">
										{!! Form::open(['route'=>'registerByAdmin','id'=>'f_reg']) !!}
										{!! csrf_field() !!}
											
											{!! Form::email('email',null,['class'=>'form-control']) !!}
											<label for="email">Email</label>
											<div class="input-field col s12">
												<select name='user_types'>{{-- get this from db next time for scalability--}}
													<option value="" disabled selected>Choose your option</option>
													<option value="1">Physician</option>
													<option value="2">Patient</option>
												</select>
												<label>Type:</label>
											</div>
											<br/><br/>
											
										{!! Form::close() !!}<button id="sub" class="btn waves-effect red darken-4" onclick="submitRegForm()">Create Account
												<i class="material-icons right">done</i>
											</button>
										<div id = "code" class="input-field col s12">

										</div>
									</div>
									<div id="getActivationCode" class="col s12">
										{!! Form::open(['route'=>'getActivationCode','id'=>'f_activation']) !!}
										{!! csrf_field() !!}
											
											{!! Form::email('email',null,['class'=>'form-control']) !!}
											<label for="email">Email</label>
											<br/><br/>
											
										{!! Form::close() !!}<button id="sub" class="btn waves-effect red darken-4" onclick="submitActivationForm()">Get Code
												<i class="material-icons right">done</i>
											</button>
										<div id = "codeActivation" class="input-field col s12">

										</div>
									</div>
									<div id="editUser" class="col s12">

									</div>
									<div id="addGroupSize" class="col s12">Test 3</div>
								</div>
								<div id = "taskManagement" style="display:none;">
									<div class="col s12">
										<ul class="tabs">
											<li class="tab col s3"><a class="active" href="#test1">Manage Task</a></li>
											
										</ul>
									</div>
									<div id="test1" class="col s12">Test 1</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End of General Body -->
		<!-- Modals -->
		
		<div id="m_personal" class="modal modal-fixed-footer">
			
			{!! Form::open() !!}
			<div class="modal-content">
				<h4>Profile</h4>
				<div class="row">
					<div class="col s12 m12 l12">
						{!! Form::text('username',Auth::user()->username) !!}
						<label for="username">Username</label>
					</div>
					
					
					@if( isset(Auth::user()->userInformation) )
						<div class="col s12 m3 l3">
							{!! Form::text('first_name',Auth::user()->userInformation->first_name) !!}
							<label for="first_name">First</label>
						</div>
						<div class="col s12 m3 l3">
							{!! Form::text('middle_name',Auth::user()->userInformation->middle_name) !!}
							<label for="middle_name">Middle</label>
						</div>
						<div class="col s12 m3 l3">
							{!! Form::text('last_name',Auth::user()->userInformation->last_name) !!}
							<label for="last_name">Last</label>
						</div>
						<div class="col s12 m3 l3">
							{!! Form::text('suffix_name',Auth::user()->userInformation->suffix_name) !!}
							<label for="suffix_name">Suffix</label>
						</div>
						<div class="col s12 m3 l3">
							<label>Sex:</label>
							<select name="sex">
								@if( Auth::user()->userInformation->sex === 'MALE')
									<option value="MALE" selected>Male</option> 
								@else
									<option value="MALE">Male</option> 
								@endif
								@if( Auth::user()->userInformation->sex === 'FEMALE')
									<option value="FEMALE" selected>Female</option>
								@else
									<option value="FEMALE" >Female</option> 
								@endif
							</select>
						</div>
						<div class="col s12 m12 l12">
							{!! Form::text('perm_address',Auth::user()->userInformation->perm_address) !!}
							<label for="perm_address">Permanent Address</label>
						</div>
						<div class="col s12 m12 l12">
							{!! Form::text('tempo_address',Auth::user()->userInformation->tempo_address) !!}
							<label for="tempo_address">Temporary Address</label>
						</div>
						@if(Auth::user()->user_type === '1')
						<div class="col s12 m12 l12">
							{!! Form::text('office_address',Auth::user()->userInformation->office_address) !!}
							<label for="office_address">Office Address</label>
						</div>
						@endif
					@else
						<div class="col s12 m3 l3">
							{!! Form::text('first_name') !!}
							<label for="first_name">First</label>
						</div>
						<div class="col s12 m3 l3">
							{!! Form::text('middle_name') !!}
							<label for="middle_name">Middle</label>
						</div>
						<div class="col s12 m3 l3">
							{!! Form::text('last_name') !!}
							<label for="last_name">Last</label>
						</div>
						<div class="col s12 m3 l3">
							{!! Form::text('suffix_name') !!}
							<label for="suffix_name">Suffix</label>
						</div>
						<div class="col s12 m3 l3">
							<label>Sex:</label>
							<select name="sex">
								<option value="MALE">Male</option>
								<option value="FEMALE" >Female</option> 
							</select>
						</div>
						<div class="col s12 m12 l12">
							{!! Form::text('perm_address') !!}
							<label for="perm_address">Permanent Address</label>
						</div>
						<div class="col s12 m12 l12">
							{!! Form::text('tempo_address') !!}
							<label for="tempo_address">Temporary Address</label>
						</div>
						<div class="col s12 m12 l12">
							{!! Form::text('office_address') !!}
							<label for="office_address">Office Address</label>
						</div>
					@endif
				</div>

				<div id="div_extra_dtls"></div>
			</div>
			<div class="modal-footer">
				<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat" onclick="">Save</a>
			</div>
			{!! Form::close() !!}
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
		
		<!-- End of modals -->
	</main>
	<footer class="page-footer grey darken-4">
          <div class="container">
            <div class="row">
              <div class="col l6 s12">
                <h5 class="red-text text-darken-1">ReWrighT: Hand and Wrist rehabilitaion system</h5>
                <p class="red-text text-darken-4">Help me</p>
              </div>
              <div class="col l4 offset-l2 s12">
                <h5 class="red-text text-darken-1">Links</h5>
                <ul>
                  <li><a class="red-text text-darken-4" href="#!">Link 1</a></li>
                  <li><a class="red-text text-darken-4" href="#!">Link 2</a></li>
                  <li><a class="red-text text-darken-4" href="#!">Link 3</a></li>
                  <li><a class="red-text text-darken-4" href="#!">Link 4</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="footer-copyright">
            <div class="container red-text text-darken-1">
            © 2019 ReWrighT: Hand and Wrist rehabilitaion system
            <!--a class="grey-text text-lighten-4 right" href="#!">More Links</a-->
            </div>
          </div>
        </footer>
    
@stop
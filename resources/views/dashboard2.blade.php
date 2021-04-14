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
				<li><a href="discussionBoard" id='db_disc_board' class='boards' data-link="{{ route('discussionBoard') }}" data-token="{{ csrf_token() }}"><i class="material-icons left ">question_answer</i>Thread</a></li>	{{-- discussions board --}}
				<li><a href="taskBoard" id='db_task_board' class='boards' data-link="{{ route('taskBoard') }}" data-token="{{ csrf_token() }}"><i class="material-icons left">work</i>Tasks</a></li>						{{-- tasks board --}}
				<li class="no-padding">																		{{-- Profile --}}
					<ul class="collapsible collapsible-accordion">
						<li>
							<a class="collapsible-header"><i class="material-icons left">perm_identity</i>{{ Auth::user()->username }}</a>
							<div class="collapsible-body">
								<ul>
									<li><a href="#m_personal" class="modal-trigger">View Profile</a></li>
									<li><a href="{!! URL::to('logout') !!}"><i class="material-icons">exit_to_app</i></a></li>
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
							<a class="btn-floating btn-large modal-trigger" href="#modal2">
								<i class="large material-icons">view_list</i>
							</a>
						</div>
						<div class="fixed-action-btn horizontal click-to-toggle" style="bottom: 45px; right: 24px;">
							<a class="btn-floating btn-large red modal-trigger" href="#m_postings">
								<i class="large material-icons">add</i>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="genContentTask">
			<div class="modal-content container">
				<ul class="collapsible popout">
				</ul>
			</div>
		</div>
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
										<p> {{ Auth::user()->userInformation->first_name }} </p>
										<label>First Name</label>
									</div>
									<div class="col l3 m3">
										<p> {{ Auth::user()->userInformation->middle_name }} </p>
										<label>Middle Name</label>
									</div>
									<div class="col l3 m3">
										<p> {{ Auth::user()->userInformation->last_name }} </p>
										<label>Last Name</label>
									</div>
									<div class="col l3 m3">
										<p> {{ Auth::user()->userInformation->suffix_name }} </p>
										<label>Suffix</label>
									</div>
								</div>
								<div class="col s12 m6 l6">
									<label>Gender:</label>
									@if(Auth::user()->userInformation->sex === 'MALE')
										Male
									@endif
									@if(Auth::user()->userInformation->sex === 'FEMALE')
										FEMALE
									@endif
									
								</div>
								<div class = "row">
									<div class="col l12 m12 s12">
										<p> {{ Auth::user()->userInformation->perm_address }} </p>
										<label>Perm. Address</label>
									</div>
								</div>
								<div class = "row">
									<div class="col l12 m12 s12">
										<p> {{ Auth::user()->userInformation->perm_address }} </p>
										<label>Temp. Address</label>
									</div>
								</div>
								<div class = "row">
									<div class="col l12 m12 s12">
										<p> {{ Auth::user()->userInformation->office_address }} </p>
										<label>Office Address</label>
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
						<li class="tab col s3"><a class="red-text text-accent-4" href="#post_disc">Create Thread</a></li>
						<li class="tab col s3"><a class="red-text text-accent-4">&nbsp</a></li>
						<div class="indicator red darken-4" style="z-index:1"></div>
					</ul>
					<div id="post_disc" class="row">

					{!! Form::open(['route'=>'postDiscussion','id'=>'postDiscussion','files'=>'true']) !!}
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
        var project_id = <?php echo Auth::user()->projects[0]->project_id; ?>;
        var user_type = <?php echo Auth::user()->user_types; ?>;
        var discussions=[];
        var tasks=[];
        var general ={details:'',content:'',comment:''};
        var displayed_id;
    </script>
@stop
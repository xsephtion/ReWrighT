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
					
						<i class="material-icons left">question_answer</i>News {{-- discussions board --}}
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
				<li><a href="#!"><i class="material-icons left ">question_answer</i>News</a></li>	{{-- discussions board --}}
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
		<!-- End of General Body -->
		<!-- Modals -->
		
		<div id="m_personal" class="modal modal-fixed-footer">
			{!! Form::open() !!}
			<div class="modal-content">
				<h4>Profile</h4>
				<div class="row">
				
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
		<div id="m_postings" class="modal modal-fixed-footer">
			<div class="modal-content">
				<div class="row">
					<ul class="tabs" id="post_tabs">
						<li class="tab col s3"><a class="red-text text-accent-4" href="#post_disc">News</a></li>
						<li class="tab col s3"><a class="red-text text-accent-4" href="#post_task">Assign Task</a></li>
						<li class="tab col s3"><a class="red-text text-accent-4" href="#post_patient_add">Add Patient</a></li>
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
					<div id="post_task" class="col s12">Assign Task</div>
					<div id="post_patient_add" class="col s12">Add Patient
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
    <script type="text/javascript">
        var board = "db_disc_board";
        var project_id = <?php echo Auth::user()->projects[0]->project_id; ?>;
        var discussions=[];
        var tasks=[];
        var general ={details:'',content:'',comment:''};
        var displayed_id;
    </script>
@stop
@extends('master')

@section('content')
	
	<div id='top'></div>
	<header>
		<nav class="top-nav grey darken-4" >
			<a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">dashboard</i></a>			
			<ul class="top-nav right">
				<li><a href="{!! URL::to('logout') !!}">logout</a></li>
			</ul>
			
		</nav>
	</header>
	<main>
		<!-- General Body -->
		<div id="genContent" style="min-height:520px; ">
			<div class="row">
				<div class="col s12 m9 l7 offset-l3 offset-m3">
				<h1> Welcome!</h1>
				<ul class="collection">
					<li><a href="#joinProject" class="modal-trigger" onclick="getProjects();">Join Project <i class="material-icons right">add</i></a></li>
					@if(Auth::user()->user_type === '1')
					<li><a href="#createProject" class="modal-trigger">Create Project <i class="material-icons right">library_add</i></a></li>
					@endif
				</ul>
				</div>
			</div>
		</div>
		<!-- End of General Body -->
		<!-- Modals -->
		<div id="joinProject" class="modal modal-fixed-footer">
			<div class="modal-content">
			
			
			{!! Form::open(['id'=>'availableProjects']) !!}
			
				<h4>Join Project</h4>
				<div id="projects">
					<ul class="collection">
					</ul>
				</div> 
			{!! Form::close() !!}
			</div>
		</div>
		
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
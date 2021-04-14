@extends('index_master')

@section('content')
	@if($errors->has())
		@foreach($errors->all() as $error)

			<script type="text/javascript">
				var toastContent = "<span>{{ $error }}</span>";
				Materialize.toast(toastContent, 5000, 'red darken-4');
			</script>
		@endforeach
	@endif
	@if(Session::get('error'))
		<script type="text/javascript">
			var toastContent = "<span>{{ Session::get('error') }}</span>";
			Materialize.toast(toastContent, 5000, 'red darken-4');
		</script>
	@endif
	<div class="container">
		<div class="row">
			<div class="col s12 m6 l6 offset-m3 offset-l3">
				<div id="f" class="card medium">
					<ul class="tabs">
						<li class="tab col s3"><a class="red-text text-accent-4" href="#div_login" onclick="changeRegCard(0);">Login</a></li>
						<div class="indicator red darken-4" style="z-index:1"></div>
					</ul>
					<div id="div_login" class="col s12">
						<br/>
						{!! Form::open(['route'=>'login']) !!}
							{!! Form::text('login_id',null,['placeholder'=>'username or email','class'=>'validate']) !!}
							<label for="login_id">Username/Email</label>
							{!! Form::password('password',null,['placeholder'=>'password','type'=>'password','class'=>'validate']) !!}
							<label for="password">Password</label>
							<p>
							<input type="checkbox" id="remember" name="remember"/>
	      					<label for="remember">Remember</label>
	      					</p>	
							<button class="btn waves-effect red darken-4" type="submit">Login
							    <i class="large material-icons right">send</i>
			  				</button>
						{!! Form::close() !!}
					</div>
					
				</div>
			</div>
		</div>
	</div>


@stop


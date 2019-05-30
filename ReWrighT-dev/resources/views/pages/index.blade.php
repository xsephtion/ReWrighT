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
						<li class="tab col s3"><a class="red-text text-accent-4" href="#div_reg" onclick="changeRegCard(1);">Register</a></li>
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
					<div id="div_reg" class="col s12">
						<br/>
						{!! Form::open(['route'=>'register','id'=>'f_reg']) !!}
						{!! csrf_field() !!}
							{!! Form::email('email',null,['class'=>'form-control']) !!}
							<label for="email">Email</label>
							{!! Form::password('r_password',['id'=>'r_password1','class'=>'form-control password', 'onchange'=>'chk_pword()']) !!}
							<label id="label_pword" for="r_password">Password</label>
							{!! Form::password('r_password',['id'=>'r_password2','class'=>'form-control password', 'placeholder' =>'Re-type password', 'onchange'=>'chk_pword()','onkeypress'=>'chk_pword()']) !!}
							{!! Form::hidden('password','null',['id'=>'password']) !!}
							
							<div class="input-field col s12">
								<select name='user_types'>
									<option value="" disabled selected>Choose your option</option>
									<option value="0">Student</option>
									<option value="1">Professor</option>
								</select>
								<label>Type:</label>
							</div>
							<button id="sub" class="btn waves-effect red darken-4" onclick="submitRegForm()">Register
								<i class="material-icons right">done</i>
							</button>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>


@stop


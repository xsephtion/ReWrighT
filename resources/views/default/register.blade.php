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
	<div class="container">
		<div class="row">
			<div class="col s12 m6 l6 offset-m3 offset-l3">
				<div class="card">
					<div class="card-content">
						<span class="card-title">Sign Up.</span>
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
									<option value="1">Student</option>
									<option value="2">Professor</option>
								</select>
								<label>Type:</label>
							</div>
							
							<button id="sub" class="btn waves-effect red darken-4" onclick="submitRegForm()">Register
								<i class="material-icons right">done</i>
							</button>
						{!! Form::close() !!}
						<br/><br/><p><a href="{{ route('login') }}">Go to login page.</a></p>	
					</div>
				</div>
			</div>
		</div>
	</div>
@stop
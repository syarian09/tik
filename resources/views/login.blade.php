<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Bimibingan TIK | Login</title>
	<link href="{{url('/')}}/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="{{url('/')}}/assets/font-awesome/css/font-awesome.css" rel="stylesheet">
	<link href="{{url('/')}}/assets/css/animate.css" rel="stylesheet">
	<link href="{{url('/')}}/assets/css/style.css" rel="stylesheet">
	<style>
		body::after {
			content: "";
			background: url("{{url('/')}}/assets/img/bg.jpg");
			background-repeat: no-repeat;
			background-size: cover;
			/* opacity: 0.8; */
			top: 0;
			left: 0;
			bottom: 0;
			right: 0;
			position: absolute;
			z-index: -1;
		}
	</style>
</head>

<body class="d-flex align-items-center justify-content-center backg">
	<div class="text-center animated fadeInDown">
		@if (session()->has('error'))
		<div class="alert alert-danger alert-dismissable">
			<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			{{ session('error') }}
		</div>
		@endif
		<div class="h4 text-white shadow-lg">TEKNOLOGI INFORMASI & KOMUNIKASI</div>
		<div class="ibox-content" style="min-width: 350px">
			{!! Form::open(['route' => 'ceklogin', 'class' => 'm-t']) !!}
			<div class="form-group input-group">
				<div class="input-group-prepend">
					<span class="input-group-addon"><i class="fa fa-user"></i></span>
				</div>
				<input type="text" class="form-control" placeholder="NISN" name="nisn">
			</div>
			<div class="form-group input-group">
				<div class="input-group-prepend">
					<span class="input-group-addon"><i class="fa fa-lock"></i></span>
				</div>
				<input type="password" class="form-control" placeholder="Password" name="password">
			</div>
			<button type="submit" class="btn btn-primary block full-width m-b">Login</button>
			{!! Form::close() !!}
		</div>
	</div>

	<script src="{{url('/')}}/assets/js/jquery-3.1.1.min.js"></script>
	<script src="{{url('/')}}/assets/js/popper.min.js"></script>
	<script src="{{url('/')}}/assets/js/bootstrap.js"></script>
</body>

</html>
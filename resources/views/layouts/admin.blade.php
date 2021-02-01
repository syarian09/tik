<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bimbingan TIK | {{ $title }}</title>
  <link href="{{url('/')}}/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{url('/')}}/assets/font-awesome/css/font-awesome.css" rel="stylesheet">
  <link href="{{url('/')}}/assets/css/animate.css" rel="stylesheet">
  <link href="{{url('/')}}/assets/css/style.css" rel="stylesheet">
  <link href="{{url('/')}}/assets/css/custom.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
  @stack('css')
  @livewireStyles
</head>

<body class="fixed-sidebar">
  <div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
      <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
          <li class="nav-header">
            <div class="dropdown profile-element">
              <img alt="image" class="rounded-circle img-md" src="{{ Auth::user()->photo_url }}" />
              <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <span class="block m-t-xs font-bold">{{ Auth::user()->name }}</span>
                <span class="text-muted text-xs block">{{ Auth::user()->str_level  }}</span>
              </a>
            </div>
          </li>

          <li class="{{ Request::is('beranda') ? 'active' : '' }}">
            <a href="{{ route('beranda') }}">
              <i class="fa fa-th-large"></i><span class="nav-label">Beranda</span>
            </a>
          </li>

          <li class="{{ Request::is('profil*') ? 'active' : '' }}">
            <a href="{{ route('profil') }}">
              <i class="fa fa-user"></i><span class="nav-label">Profil</span>
            </a>
          </li>

          <li class="{{ Request::is('materi*') ? 'active' : '' }}">
            <a href="{{ route('materi') }}">
              <i class="fa fa-cubes"></i><span class="nav-label">Materi</span>
            </a>
          </li>

          <li class="{{ Request::is('ujian*') ? 'active' : '' }}">
            <a href="{{ route('ujian') }}">
              <i class="fa fa-book"></i><span class="nav-label">Ujian</span>
            </a>
          </li>

          <li class="{{ Request::is('nilai*') ? 'active' : '' }}">
            <a href="{{ route('nilai') }}">
              <i class="fa fa-puzzle-piece"></i><span class="nav-label">Nilai</span>
            </a>
          </li>

          @if (Auth::user()->level == 9)
          <li class="{{ Request::is('user*') ? 'active' : '' }}">
            <a href="{{ route('user') }}">
              <i class="fa fa-database"></i><span class="nav-label">Data User</span>
            </a>
          </li>
          @endif
        </ul>
      </div>
    </nav>

    <div id="page-wrapper" class="gray-bg">
      <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
          <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i></a>
          </div>
          <ul class="nav navbar-top-links navbar-right">
            <li>
              <span class="m-r-sm text-muted welcome-message">Bimbingan TIK SMPN 6 Taliwang</span>
            </li>
            <li>
              <a href="{{ route('logout') }}">
                <i class="fa fa-sign-out"></i> Log out
              </a>
            </li>
          </ul>
        </nav>
      </div>
      <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
          <h2>{{ $title }}</h2>
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="{{url('beranda')}}">Beranda</a>
            </li>
            <li class="breadcrumb-item active">
              <strong>{{ Str::title(str_replace('-', ' ', Request::segment(1))) }} </strong>
            </li>
          </ol>
        </div>
      </div>
      <div class="wrapper wrapper-content">
        @yield('content')
      </div>
      <div class="footer fixed">
        <div>
          <strong>Copyright</strong> Bimbingan TIK SMPN 6 Taliwang &copy; {{ date('Y') }}
        </div>
      </div>
    </div>
  </div>
  <!-- Mainly scripts -->
  <script src="{{url('/')}}/assets/js/jquery-3.1.1.min.js"></script>
  <script src="{{url('/')}}/assets/js/popper.min.js"></script>
  <script src="{{url('/')}}/assets/js/bootstrap.js"></script>
  <script src="{{url('/')}}/assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
  <script src="{{url('/')}}/assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

  <!-- Custom and plugin javascript -->
  <script src="{{url('/')}}/assets/js/inspinia.js"></script>
  <script src="{{url('/')}}/assets/js/plugins/pace/pace.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  @livewireScripts
  @stack('scripts')
  <script>
    livewire.on('alert', param => {
			toastr[param['type']](param['message']);
			if (param['reload'] == true) {
				setTimeout(function(){location.reload();},500);
			}
		});
  </script>
</body>

</html>
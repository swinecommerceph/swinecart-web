{{--
	Template for the layout of a page of the admin
--}}
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Swine E-Commerce PH @yield('title') </title>

    <link href="/css/materialize.min.css" rel="stylesheet" type="text/css">
    <link href="/css/dropzone.css" rel="stylesheet" type="text/css">
    <link href="/css/icon.css" rel="stylesheet" type="text/css">
    <link href="/css/style.css" rel="stylesheet" type="text/css">
    <link href="/js/vendor/video-js/video-js.min.css" rel="stylesheet">

    <script src="/js/vendor/video-js/ie8/videojs-ie8.min.js"></script>
  </head>
  <body @yield('pageId')>
    {{-- Navbar --}}
    <div class="navbar-fixed">
      <nav class="blue lighten-2 darken-3">
          <div class="nav-wrapper container">
            <a class="brand-logo" href="{{ route('home_path') }}">Swine E-Commerce PH</a>

            <ul id="nav-mobile" class="right hide-on-med-and-down">
              <li> <span>{{ Auth::user()->name }}</span> </li>
              <li>
                <a class="dropdown-button" data-beloworigin="true" data-activates="nav-dropdown">
                  <i class="material-icons">arrow_drop_down</i>
                </a>
                <ul id="nav-dropdown" class="dropdown-content">
                      <li class="divider"></li>
                      <li><a href="{{ route('logout_path') }}">Logout</a></li>
                  </ul>
              </li>
            </ul>
          </div>

        {{-- Preloader Progress --}}
        <div id="preloader-progress" class="progress red lighten-4" style="display:none;">
          <div class="indeterminate red"></div>
        </div>

        {{-- Search Field --}}
        <div id="search-field" class="nav-wrapper white z-depth-1" style="display:none;">
                <div style="height:1px;">
                </div>
                <form>
                    <div class="input-field">
                        <input id="search" type="search" placeholder="Search for a product" required>
                        <label for="search"><i class="material-icons teal-text">search</i></label>
                        <i class="material-icons">close</i>
                    </div>
                </form>
            </div>

      </nav>
    </div>
    {{--Side Navigation bar for admin --}}
    <div class="container">
      <div class="row">
        <div class="col s3">
          <ul class="collapsible popout" data-collapsible="accordion">
          <li>
            <div class="collapsible-header active" id="all"><i class="material-icons">face</i><a href="{{route('admin.userlist')}}" class="black-text" id='users-breeder'>All Users</a></div>
          </li>
          <li>
            <div class="collapsible-header"><i class="material-icons">assignment_id</i>Manage Approved Users</div>
            <div class="collapsible-body center"><a href="{{route('admin.approved.breeder')}}" class="black-text" id='users-breeder'><p>Breeder</p></a></div>
            <div class="collapsible-body center"><a href="{{route('admin.approved.customer')}}" class="black-text" id='users-customer'><p>Customer</p></a></div>
          </li>
          <li>
            <div class="collapsible-header"><i class="material-icons">assignment_late</i>Manage Pending Users</div>
            <div id='pending-customer' class="collapsible-body center"><a href="{{route('admin.pending.customer')}}" class="black-text" id='pending-customer'><p>Customer</p></a></div>
          </li>
          <li>
            <div class="collapsible-header"><i class="material-icons">build</i>Manage Pages</div>
            <div class="collapsible-body"><a href="#" class="black-text" id='pages-home'><p>Home</p></div>
          </li>
        </ul>
        </div>
        <div class="col s9">
          <div class="card-panel">
            <h4 id='admin-content-panel-header'>All Users</h4>
            <div class="divider"></div>
            <div class="row">
                <div class="col s12">
                  @include('user.admin._displayUsers')
                </div>
            </div>

          </div>

        </div>
      </div>
    </div>
    @if(!Request::is('/'))
  		<div class="container">
  			@yield('content')
  		</div>
  	@endif
    <script src="/js/vendor/jquery.min.js"></script>
  	<script src="/js/vendor/materialize.min.js"></script>
  	<script src="/js/vendor/dropzone.js"></script>
  	<script src="/js/vendor/video-js/video.min.js"></script>
  	<script src="/js/config.js"></script>
  	<script src="/js/custom.js"></script>
  	{{-- For user-specific initialization scripts --}}
  	@yield('initScript')
  	{{-- Custom scripts for certain pages/functionalities --}}
  	@yield('customScript')
  </body>
</html>

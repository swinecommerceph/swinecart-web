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
    <link href="/js/vendor/VideoJS/video-js.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.css">


    <script src="/js/vendor/VideoJS/ie8/videojs-ie8.min.js"></script>

  </head>
  <body @yield('pageId')>
    {{-- Navbar --}}
    <div class="navbar-fixed">
      <nav class="teal darken-3">
          <div class="nav-wrapper container">
            <img src="/images/logowhite.png" height=65/>&nbsp&nbsp<a class="brand-logo" href="{{ route('home_path') }}">Swine E-Commerce PH</a>

            <ul id="nav-mobile" class="right hide-on-med-and-down">
              <li><a>{{ Auth::user()->name }}</a> </li>

              <li>
                <a class="waves-effect waves-light modal-trigger " href="#vewlogs">
                    <i class="material-icons">class</i>
                </a>
              </li>

              <li>
                <a href="#" class="dropdown-button" data-beloworigin="true" data-activates="inquiries-dropdown"><i class="material-icons">feedback</i></a>
                <ul id="inquiries-dropdown" class="dropdown-content">
                      <li>
                        <ul class="collection">
                          <li class="collection-item avatar">
                            <i class="material-icons circle">perm_identity</i>
                            <span class="title">Username</span>
                            <p>
                              {{-- Insert message here --}}
                              message
                            </p>
                             <a class="modal-trigger waves-effect waves-light right" href="#modal1">Read more...</a>
                          </li>
                        </ul>
                      </li>
                      <li><a href="#" class="center">Show All</a></li>
                  </ul>
                </li>

                <li>
                   <a class="waves-effect waves-light modal-trigger " href="#adduser">
                      <i class="material-icons">add</i>
                  </a>
                </li>


                <li>
                    <a class="dropdown-button" data-beloworigin="true" data-hover="true" data-alignment="right" data-activates="nav-dropdown">
                        <i class="material-icons">arrow_drop_down</i>
                    </a>
                    <ul id="nav-dropdown" class="dropdown-content">
                      <li><a href="{{ route('registration.form') }}">Registration Form</a></li>
                      <li class="divider"></li>
                      <li><a href="{{ url('logout') }}">Logout</a></li>
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
          <div class="row ">
            <div id="menu" class="col s3 ">
              <ul class="collapsible" data-collapsible="accordion">
              <li>
                <div class="collapsible-header active"><a href="{{route('admin.userlist')}}" class="black-text" id='all'><i class="material-icons">face</i>All Users</a></div>
              </li>
              <li>
                <div class="collapsible-header"><i class="material-icons">assignment_late</i><a href="{{route('admin.pending.users')}}" class="black-text" id='pending-breeder'>Pending Accounts</a></div>
              </li>
              <li>
                <div class="collapsible-header"><i class="material-icons">build</i>Manage Pages</div>
                <div class="collapsible-body center" ><a href="{{route('admin.manage.homepage')}}" class="black-text" id='pages-home-images'><p>Homepage Images</p></a></div>
                {{-- <div class="collapsible-body center" ><a href="{{route('admin.manage.text')}}" class="black-text" id='pages-home-text'><p>Homepage Text</p></a></div> --}}
              </li>
            </ul>
            </div>

            <div class="col s9 ">
                <div class="card-panel ">
                    <div class="row" id= "admin-header-wrapper">
                      {{-- div tag for header Header  --}}
                      <div class="col s6">
                        <h4 id='admin-content-panel-header'>@yield('header')</h4>
                      </div>
                    </div>
                    <div class="divider"></div>
                    <div class="row" id="content-panel-wrapper">
                        <div class="col s12" id="main-content">
                            {{-- content page generated by javascript functions--}}
                            @yield('content')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/js/vendor/jquery.min.js"></script>
    <script src="/js/vendor/VueJS/vue.js"></script>
  	<script src="/js/vendor/materialize.min.js"></script>
  	<script src="/js/vendor/dropzone.js"></script>
  	<script src="/js/vendor/VideoJS/video.min.js"></script>
  	<script src="/js/config.js"></script>
    <script type="text/javascript" src="/js/admin/admin_custom.js"></script>
    <script src="/js/custom.js"></script>
   {{-- <script type="text/javascript" src="/js/vendor/datatables.min.js"></script> --}}
   <script src="/js/vendor/DataTables/datatables.js"></script>
   {{-- For user-specific initialization scripts --}}
  	@yield('initScript')
  	{{-- Custom scripts for certain pages/functionalities --}}
  	@yield('customScript')
  </body>
</html>

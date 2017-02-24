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
    <script type="text/javascript" src="/js/vendor/chart.bundle.min.js"></script>

    <script src="/js/vendor/VideoJS/ie8/videojs-ie8.min.js"></script>

  </head>
  <body @yield('pageId')>
    {{-- Navbar --}}
    <div class="navbar-fixed">
      <nav class="teal darken-3">
          <div class="nav-wrapper container">
            <a href="{{ route('home_path') }}"><img src="/images/logowhite.png" height=65/>&nbsp&nbsp<div class="brand-logo">Swine E-Commerce PH</div></a>

            <ul id="nav-mobile" class="right hide-on-med-and-down">
              <li><a>{{ Auth::user()->name }}</a> </li>

              <li>
                <a class="waves-effect waves-light modal-trigger tooltipped" href="{{ route('admin_logs') }}" data-position="bottom" data-delay="40" data-tooltip="Administrator Logs">
                    <i class="material-icons">class</i>
                </a>
              </li>

              <li>
                <a href="#" class="dropdown-button tooltipped" data-beloworigin="true" data-activates="inquiries-dropdown" data-position="bottom" data-delay="40" data-tooltip="Messages"><i class="material-icons">feedback</i></a>
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
                      <li><a href="#" class=" center">Show All</a></li>
                  </ul>
                </li>

                <li>
                   <a class="waves-effect waves-light modal-trigger tooltipped" href="#adduser" data-position="bottom" data-delay="40" data-tooltip="Add User">
                      <i class="material-icons">add</i>
                  </a>
                </li>


                <li>
                    <li><a href="{{ url('logout') }}">Logout</a></li>
                </li>
            </ul>
          </div>

        {{-- Preloader Progress --}}
        <div id="preloader-progress" class="progress red lighten-4" style="display:none;">
          <div class="indeterminate red"></div>
        </div>

        {{-- Search Field --}}
        {{-- <div id="search-field" class="nav-wrapper white z-depth-1" style="display:none;">
                <div style="height:1px;">
                </div>
                <form>
                    <div class="input-field">
                        <input id="search" type="search" placeholder="Search for a product" required>
                        <label for="search"><i class="material-icons teal-text">search</i></label>
                        <i class="material-icons">close</i>
                    </div>
                </form>
            </div> --}}

      </nav>
    </div>

    {{--Side Navigation bar for admin --}}
    <div class="container">
          <div class="row ">
              <div class="menu-wrapper col s3">
                  <div id="menu">
                    <ul class="collapsible" data-collapsible="accordion">
                    <li>
                      <a href="{{route('admin.userlist')}}" class="black-text" id='all'><div class="collapsible-header active"><i class="material-icons">face</i>All Users</div></a>
                    </li>
                    <li>
                     <a href="{{route('admin.pending.users')}}" class="black-text" id='pending-breeder'> <div class="collapsible-header"><i class="material-icons">assignment_late</i>Pending Accounts</div></a>
                    </li>
                    <li>
                     <a href="{{route('admin.statistics.dashboard')}}" class="black-text" id='statistics'> <div class="collapsible-header"><i class="material-icons">trending_up</i>Site Statistics</div></a>
                    </li>
                    <li>
                      <div class="collapsible-header"><i class="material-icons">build</i>Manage Pages</div>
                      <div class="collapsible-body center" ><a href="{{route('admin.manage.homepage')}}" class="black-text" id='pages-home-images'><p>Homepage Images</p></a></div>
                      {{-- <div class="collapsible-body center" ><a href="{{route('admin.manage.text')}}" class="black-text" id='pages-home-text'><p>Homepage Text</p></a></div> --}}
                    </li>
                  </ul>
                  </div>
              </div>

            <div class="content-wrapper col s9">
                <div class="col s12">
                    <div class="card-panel ">
                        @yield('header')
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

    </div>

    <footer class="page-footer teal darken-3">
      <div class="container">
        <div class="row">
          <div class="col l6 s12">
            <h5 class="white-text">Swine E-Commerce PH</h5>
            <p class="grey-text text-lighten-4"></p>
          </div>
          <div class="col l4 offset-l2 s12">
            <h5 class="white-text">Links</h5>
            <ul>
              <li><a class="grey-text text-lighten-3" href="{{route('admin_path')}}">Home</a></li>
              <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="footer-copyright">
        <div class="container">
        © 2017 Copyright Text
        <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
        </div>
      </div>
    </footer>
    {{-- Accept modal --}}
    <div id="accept-modal" class="modal action-dialog-box green lighten-5">
      <div class="modal-content">
        <h4>Accept User</h4>
        <div class="divider"></div>
        <p>Are you sure you want to accept this user's application?</p>
      </div>
      <div class="modal-footer green lighten-5">
        <a href="#!" id="cancel-accept" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancel</a>
        <a href="#!" id="confirm-accept" class=" modal-action modal-close waves-effect waves-green btn-flat">Confirm</a>
      </div>
    </div>

    {{-- Reject modal --}}
    <div id="reject-modal" class="modal action-dialog-box red lighten-5">
      <div class="modal-content">
        <h4>Reject User</h4>
        <div class="divider"></div>
        <p>Are you sure you want to reject this user's application?</p>
      </div>
      <div class="modal-footer red lighten-5">
        <a href="#!" id="cancel-reject" class=" modal-action modal-close waves-effect waves-red btn-flat">Cancel</a>
        <a href="#!" id="confirm-reject" class=" modal-action modal-close waves-effect waves-red btn-flat">Confirm</a>
      </div>
    </div>


    {{-- Add user modal --}}
      <div id="adduser" class="modal">
         {!!Form::open(['route'=>'admin.add.user', 'method'=>'POST', 'class'=>'add-user-form'])!!}
       <div class="modal-content">
         <h4>Add User</h4>
         <div class="divider"></div>
         <div class="row">
            <div class="col s12">

                  <div class="row">
                     <div class = "addusercontainer" class="row">
                        <div class="input-field col s11">
                         <i class="material-icons prefix">account_circle</i>
                         <input id="icon_prefix" type="text" class="validate" name="name">
                         <label for="icon_prefix">Username</label>
                       </div>
                     </div>
                 </div>

                 <div class="row">
                    <div class = "addusercontainer" class="row">
                       <div class="input-field col s11">
                        <i class="material-icons prefix">email</i>
                        <input id="icon_prefix" type="email" class="validate" name="email">
                        <label for="icon_prefix">Email Address</label>
                      </div>
                    </div>
                </div>

            </div>
         </div>
       </div>
       <div class="modal-footer">
          <button id = "add-user-submit" class="btn waves-effect waves-light" type="submit" name="action">Add
           <i class="material-icons right">send</i>
         </button>
       </div>
        {!!Form::close()!!}
     </div>

    {{-- Modal for user inquiries --}}
    <div id="modal1" class="modal modal-fixed-footer">
      <div id="message-modal-content" class="modal-content">
        <div class="center"><h5>"Username" Message</h5></div>
          <div class="center">Timestamp</div>
          <div class="divider"></div>
          <div class="row">
          <div class="col s12">
          <div id="message-panel" class="card-panel">
            <span class="black-text">
              Sample Text
            </span>
          </div>
        </div>
      </div>
    </div>
      <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Resolve</a>
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
   {{-- <script src="/js/vendor/DataTables/datatables.js"></script> --}}
   {{-- For user-specific initialization scripts --}}
  	@yield('initScript')
  	{{-- Custom scripts for certain pages/functionalities --}}
  	@yield('customScript')
  </body>
</html>

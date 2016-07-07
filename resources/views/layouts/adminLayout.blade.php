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
      <nav class="teal darken-4">
          <div class="nav-wrapper container">
            <a class="brand-logo" href="{{ route('home_path') }}">Swine E-Commerce PH</a>

            <ul id="nav-mobile" class="right hide-on-med-and-down">
              <li><span>{{ Auth::user()->name }}</span> </li>
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
      <div class="row ">
        <div class="col s3">
          <ul class="collapsible" data-collapsible="accordion">
          <li>
            <div class="collapsible-header active"><a href="{{route('admin.userlist')}}" class="black-text" id='all'><i class="material-icons">face</i>All Users</a></div>
          </li>
          {{-- <li>
            <div class="collapsible-header"><i class="material-icons">assignment_id</i>Manage Approved Users</div>
            <div class="collapsible-body center"><a href="{{route('admin.approved.breeder')}}" class="black-text" id='users-breeder'><p>Breeder</p></a></div>
            <div class="collapsible-body center"><a href="{{route('admin.approved.customer')}}" class="black-text" id='users-customer'><p>Customer</p></a></div>
          </li> --}}
          <li>
            <div class="collapsible-header"><i class="material-icons">assignment_late</i><a href="{{route('admin.pending.users')}}" class="black-text" id='pending-breeder'>Pending Breeder Farms</a></div>
          </li>
          <li>
            <div class="collapsible-header"><i class="material-icons">build</i>Manage Pages</div>
            <div class="collapsible-body center" ><a href="#" class="black-text" id='pages-home'><p>Home</p></a><div>
          </li>
        </ul>
        </div>
        <div class="col s9 ">
          <div class="card-panel ">
            <h4 id='admin-content-panel-header'>Admin Dashboard</h4>
            <div class="divider"></div>
            <div class="row" id="content-panel-wrapper">
                <div class="col s12" id="main-content">
                  {{-- content page generated by javascript functions--}}
                    <div class="row">
                    <a href="#!" id="total-user-summary">
                      <div class="col s6" >
                        <div class="card-panel green card-summary hoverable">
                          <div class="center white-text">
                              <i class="material-icons summary-icons">perm_identity</i>
                              <span class="summary-title">Total Users</span>
                          </div>
                          <div class="center white-text summary-data">
                            {{$summary[0]}}
                          </div>

                        </div>
                      </div>
                    </a>
                    <a href="#!" id="total-blocked-summary">
                      <div class="col s6">
                        <div class="card-panel red card-summary hoverable">
                          <div class="center white-text">
                              <i class="material-icons summary-icons">block</i>
                              <span class="summary-title">Blocked Users</span>
                          </div>
                          <div class="center white-text summary-data">
                            {{$summary[4]}}
                          </div>
                        </div>
                      </div>
                    </a>
                    <a href="#!" id="total-pending-summary">
                      <div class="col s6">
                        <div class="card-panel purple lighten-2 card-summary hoverable">
                          <div class="center white-text">
                              <i class="material-icons summary-icons">hourglass_full</i>
                              <span class="summary-title">Pending Breeders</span>
                          </div>
                          <div class="center white-text summary-data">
                              {{$summary[3]}}
                          </div>
                        </div>
                      </div>
                    </a>
                    <a href="#!">
                    <div class="col s6">
                      <div class="card-panel indigo card-summary hoverable">
                        <div class="center white-text">
                            <i class="material-icons summary-icons">help</i>
                            <span class="summary-title">User Inquiries</span>
                        </div>
                        <div class="center white-text summary-data">
                            10
                        </div>
                      </div>
                    </div>
                  </a>
                </div>

                {{-- <table class="centered highlight bordered">
                  <thead>
                    <tr>
                        <th data-field="id">Name</th>
                        <th data-field="type">Account Type</th>
                        <th data-field="action">Action</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <td>Farm Name</td>
                      <td>Breeder</td>
                      <td>
                        <div class="row action-column">
                          <div class="col s6">

                            <a href="#" class="tooltipped block-data" data-position="bottom" data-delay="50" data-tooltip="'+value+'" data-user-name = "'+data.name+'"><i class="material-icons ">block</i></a>
                          </div>
                          <div class="col s6">
                            <a href="#" class="tooltipped delete-data" data-position="bottom" data-delay="50" data-tooltip="Delete" data-user-name = "'+data.name+'"><i class="material-icons">delete</i></a>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table> --}}


                </div>
            </div>

          </div>

        </div>
      </div>
    </div>

    <div id="delete-modal" class="modal action-dialog-box red lighten-5">
      <div class="modal-content">
        <h4>Delete User</h4>
        <div class="divider"></div>
        <p>Are you sure you want to delete this user?</p>
      </div>
      <div class="modal-footer red lighten-5">
        <a href="#!" id="cancel-delete" class=" modal-action modal-close waves-effect waves-red btn-flat">Cancel</a>
        <a href="#!" id="confirm-delete" class=" modal-action modal-close waves-effect waves-red btn-flat">Confirm</a>
      </div>
    </div>

    <div id="block-modal" class="modal action-dialog-box orange lighten-5">
      <div class="modal-content">
        <h4>Block User</h4>
        <div class="divider"></div>
        <p>Are you sure you want to block this user?</p>
      </div>
      <div class="modal-footer orange lighten-5">
        <a href="#!" id="cancel-block" class=" modal-action modal-close waves-effect waves-red btn-flat">Cancel</a>
        <a href="#!" id="confirm-block" class=" modal-action modal-close waves-effect waves-red btn-flat">Confirm</a>
      </div>
    </div>



    <div id="modal1" class="modal modal-fixed-footer">
      <div id="message-modal-content" class="modal-content">
        <div class="center"><h5>"Username" Message</h5></div>
          <div class="center">Timestamp</div>
          <div class="divider"></div>
          <div class="row">
          <div class="col s12">
          <div id="message-panel" class="card-panel">
            <span class="black-text">
              I am a very simple card. I am good at containing small bits of information.
              I am convenient because I require little markup to use effectively. I am similar to what is called a panel in other frameworks.
              I am a very simple card. I am good at containing small bits of information.
              I am convenient because I require little markup to use effectively. I am similar to what is called a panel in other frameworks.
              I am a very simple card. I am good at containing small bits of information.
              I am convenient because I require little markup to use effectively. I am similar to what is called a panel in other frameworks.
              I am a very simple card. I am good at containing small bits of information.
              I am convenient because I require little markup to use effectively. I am similar to what is called a panel in other frameworks.
              I am a very simple card. I am good at containing small bits of information.
              I am convenient because I require little markup to use effectively. I am similar to what is called a panel in other frameworks.
              I am a very simple card. I am good at containing small bits of information.
              I am convenient because I require little markup to use effectively. I am similar to what is called a panel in other frameworks.
              I am a very simple card. I am good at containing small bits of information.
              I am convenient because I require little markup to use effectively. I am similar to what is called a panel in other frameworks.
              I am a very simple card. I am good at containing small bits of information.
              I am convenient because I require little markup to use effectively. I am similar to what is called a panel in other frameworks.
              I am a very simple card. I am good at containing small bits of information.
              I am convenient because I require little markup to use effectively. I am similar to what is called a panel in other frameworks.
              I am a very simple card. I am good at containing small bits of information.
              I am convenient because I require little markup to use effectively. I am similar to what is called a panel in other frameworks.
              I am a very simple card. I am good at containing small bits of information.
              I am convenient because I require little markup to use effectively. I am similar to what is called a panel in other frameworks.
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

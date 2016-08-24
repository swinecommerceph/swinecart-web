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
<<<<<<< HEAD
    <link href="/js/vendor/video-js/video-js.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.css">


    <script src="/js/vendor/video-js/ie8/videojs-ie8.min.js"></script>
=======
    <link href="/js/vendor/VideoJS/video-js.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.css">


    <script src="/js/vendor/VideoJS/ie8/videojs-ie8.min.js"></script>
>>>>>>> upstream/master

  </head>
  <body @yield('pageId')>
    {{-- Navbar --}}
    <div class="navbar-fixed">
      <nav class="teal darken-4">
          <div class="nav-wrapper container">
            <img src="/images/logowhite.png" height=65/>&nbsp&nbsp<a class="brand-logo" href="{{ route('home_path') }}">Swine E-Commerce PH</a>

            <ul id="nav-mobile" class="right hide-on-med-and-down">
<<<<<<< HEAD
              <li><span>{{ Auth::user()->name }}</span> </li>
=======
              <li><a>{{ Auth::user()->name }}</a> </li>
>>>>>>> upstream/master
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
<<<<<<< HEAD
                <a class="dropdown-button" data-beloworigin="true" data-activates="nav-dropdown">
=======
                <a class="dropdown-button" data-beloworigin="true" data-hover="true" data-alignment="right" data-activates="nav-dropdown">
>>>>>>> upstream/master
                  <i class="material-icons">arrow_drop_down</i>
                </a>
                <ul id="nav-dropdown" class="dropdown-content">
                      <li><a href="{{ route('registration.form') }}">Registration Form</a></li>
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
        <div id="menu" class="col s3">
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
            <div class="collapsible-header"><i class="material-icons">assignment_late</i><a href="{{route('admin.pending.users')}}" class="black-text" id='pending-breeder'>Pending Accounts</a></div>
          </li>
          <li>
            <div class="collapsible-header"><i class="material-icons">build</i>Manage Pages</div>
            <div class="collapsible-body center" ><a href="#" class="black-text" id='pages-home'><p>Home</p></a><div>
          </li>
        </ul>
        </div>
        <div class="col s9 ">
          <div class="card-panel ">
            <div class="row" id= "admin-header-wrapper">
              {{-- div tag for header Header  --}}
              <div class="col s6">
                <h4 id='admin-content-panel-header'>Admin Dashboard</h4>
              </div>
            </div>
            <div class="divider"></div>
            <div class="row" id="content-panel-wrapper">
                <div class="col s12" id="main-content">
                  {{-- content page generated by javascript functions--}}
                    <div class="row">
                    <a href="#!" id="total-user-summary">
                      <div class="col s6" >
                        <div id="total-card" class="card-panel card-summary hoverable">
                           <div class="center white-text row">
                              <div class="col s4 label-wrapper">
                                 <div class="left">
                                    <i class="ecommerce-icon">p</i>
                                 </div>
                                 <div class="">
                                    <div class="summary-title">TOTAL USERS</div>
                                 </div>
                              </div>

                               <div class="center white-text summary-data col s8">
                                 {{$summary[0]}}
                               </div>
                           </div>

                        </div>
                      </div>
                    </a>
                    <a href="#!" id="total-blocked-summary">
                       <div class="col s6" >
                         <div id="blocked-card" class="card-panel card-summary hoverable">
                            <div class="center white-text row">
                               <div class="col s4 label-wrapper">
                                  <div class="">
                                     <i class="ecommerce-icon">b</i>
                                  </div>
                                  <div class="">
                                     <div class="summary-title">BLOCKED USERS</div>
                                  </div>
                               </div>

                                <div class="center white-text summary-data col s8">
                                  {{$summary[4]}}
                                </div>
                            </div>
                         </div>
                       </div>
                    </a>
                    <a href="#!" id="total-pending-summary">
                       <div class="col s6" >
                         <div id="pending-card" class="card-panel card-summary hoverable">
                            <div class="center white-text row">
                               <div class="col s4 label-wrapper">
                                  <div class="">
                                     <i class="ecommerce-icon">w</i>
                                  </div>
                                  <div class="">
                                     <div class="summary-title">PENDING BREEDERS</div>
                                  </div>
                               </div>

                               <div class="center white-text summary-data col s8">
                                  {{$summary[3]}}
                               </div>
                            </div>
                         </div>
                       </div>
                    </a>
                    <a href="#!">
                       <div class="col s6" >
                         <div id="inquiries-card" class="card-panel card-summary hoverable">
                            <div class="center white-text row">
                               <div class="col s4 label-wrapper">
                                  <div class="left">
                                     <i class="ecommerce-icon">d</i>
                                  </div>
                                  <div class="">
                                     <div class="summary-title">USER INQUIRIES</div>
                                  </div>
                               </div>

                               <div class="center white-text summary-data col s8">
                                  3
                               </div>
                            </div>
                         </div>
                       </div>
                  </a>
                </div>

                </div>
            </div>

          </div>

        </div>
      </div>
    </div>

    {{-- Manage User Modal --}}
    <div id="manage-user-modal" class="modal">
      <div class="modal-content">
         <h4>Username</h4>
         <div class="row">
            {!!Form::open(['route'=>'admin.block', 'method'=>'PUT', 'class'=>'block-form'])!!}
            <a id="block-data" href="#">
               <div class="col s6 center">
                  <i id="block-icon" class="material-icons manage-icon">block</i>
                  <input id="block-token" name="_token" type="hidden" value="">
                  <input id="block-id" name="user_id" type="hidden" value="">
                  <div id="block-label" class="col s12">Block</div>
               </div>
            </a>
            {!!Form::close()!!}
            {!!Form::open(['route'=>'admin.delete', 'method'=>'DELETE', 'class'=>'delete-form'])!!}
               <a id="delete-data" href="#">
                  <div class="col s6 center">
                     <i id="delete-icon" class="material-icons manage-icon">close</i>
                     <input id="delete-token" name="_token" type="hidden" value="">
                     <input id="delete-id" name="user_id" type="hidden" value="">
                     <div id="delete-label" class="col s12">Delete</div>
                  </div>
               </a>
            {!!Form::close()!!}
         </div>
         <div class="divider"></div>
         <div class="modal-footer">
           <a href="#!" id="cancel-manage" class=" modal-action modal-close waves-effect waves btn-flat">Cancel</a>
         </div>
      </div>
    </div>

    {{-- Manage User Modal for Accept and Reject --}}
    <div id="accept-reject-modal" class="modal">
      <div class="modal-content">
         <h4>Username</h4>
         <div class="row">
            {!!Form::open(['route'=>'admin.add.user', 'method'=>'PUT', 'class'=>'accept-form'])!!}
            <a id="accept-data" href="#">
               <div class="col s6 center">
                  <i id="accept-icon" class="material-icons manage-icon">check</i>
                  <input id="accept-token" name="_token" type="hidden" value="">
                  <input id="accept-id" name="user_id" type="hidden" value="">
                  <div id="accept-label" class="col s12">Accept</div>
               </div>
            </a>
            {!!Form::close()!!}
            {!!Form::open(['route'=>'admin.delete', 'method'=>'DELETE', 'class'=>'delete-form'])!!}
               <a id="reject-data" href="#">
                  <div class="col s6 center">
                     <i id="delete-icon" class="material-icons manage-icon">close</i>
                     <input id="reject-token" name="_token" type="hidden" value="">
                     <input id="reject-id" name="user_id" type="hidden" value="">
                     <div id="reject-label" class="col s12">Reject</div>
                  </div>
               </a>
            {!!Form::close()!!}
         </div>
         <div class="divider"></div>
         <div class="modal-footer">
           <a href="#!" id="cancel-accept-reject" class=" modal-action modal-close waves-effect waves btn-flat">Cancel</a>
         </div>
      </div>
    </div>

    {{-- Delete modal --}}
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

    {{-- Block/Unblock modal --}}
    <div id="block-modal" class="modal action-dialog-box orange lighten-5">
      <div class="modal-content">
        <h4>Block User</h4>
        <div class="divider"></div>
        <p>Are you sure you want to block this user?</p>
      </div>
      <div class="modal-footer orange lighten-5">
        <a href="#!" id="cancel-block" class=" modal-action modal-close waves-effect waves-orange btn-flat">Cancel</a>
        <a href="#!" id="confirm-block" class=" modal-action modal-close waves-effect waves-orange btn-flat">Confirm</a>
      </div>
    </div>

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
<<<<<<< HEAD
  	<script src="/js/vendor/video-js/video.min.js"></script>
=======
  	<script src="/js/vendor/VideoJS/video.min.js"></script>
>>>>>>> upstream/master
  	<script src="/js/config.js"></script>
  	<script src="/js/custom.js"></script>
   {{-- <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script> --}}
   <script type="text/javascript" src="/js/vendor/datatables.min.js"></script>
   {{-- For user-specific initialization scripts --}}
  	@yield('initScript')
  	{{-- Custom scripts for certain pages/functionalities --}}
  	@yield('customScript')
  </body>
</html>

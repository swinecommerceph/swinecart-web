{{--
    Displays Home page of Admin
--}}

@extends('layouts.adminLayout')

@section('title')
    | Admin
@endsection

@section('pageId')
    id="page-admin-home"
@endsection

{{-- @section('breadcrumbTitle')
    Home
@endsection --}}
@section('header')
    <h4 id='admin-content-panel-header'>Admin Dashboard</h4>
@endsection

@section('content')
    <div class="row">
        <a href="{{route('admin.userlist')}}" id="total-user-summary">
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
        <a href="{{route('admin.blocked.users')}}" id="total-blocked-summary">
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
        <a href="{{route('admin.pending.users')}}" id="total-pending-summary">
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
        <a href="#">
           <div class="col s6" >
             <div id="inquiries-card" class="card-panel card-summary hoverable">
                <div class="center white-text row">
                   <div class="col s4 label-wrapper">
                      <div class="left">
                         <i class="ecommerce-icon">u</i>
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

        <a href="{{route('admin.statistics.deleted')}}" id="site-statistics-summary">
           <div class="col s12" >
             <div id="statistics-card" class="card-panel card-summary hoverable">
                <div class="center white-text row">
                   <div class="col s4 label-wrapper">
                      <div class="center">
                         <i class="ecommerce-icon" id="statistics-icon">x</i>
                      </div>

                   </div>

                   <div class="center white-text col s8" id="statistics-title-wrapper">
                     <div  id="statistics-title">SITE STATISTICS</div>
                   </div>
                </div>
             </div>
           </div>
        </a>

    </div>

@endsection

@section('initScript')
    <script type="text/javascript" src="/js/admin/admin_custom.js"></script>
    <script type="text/javascript" src="/js/admin/users.js"></script>
    <script type="text/javascript" src="/js/admin/manageUsers_script.js"></script>
    <script type="text/javascript" src="/js/admin/pages.js"></script>
    <script type="text/javascript" src="/js/admin/managePages_script.js"></script>
@endsection

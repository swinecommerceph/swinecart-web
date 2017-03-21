@extends('layouts.spectatorLayout')

@section('title')
    | Spectator
@endsection

@section('pageId')
    id="page-spectator-home"
@endsection

{{-- @section('breadcrumbTitle')
    Home
@endsection --}}

@section('content')
    <div class="card-panel">
        <div class="row">
            <div class="col s12">
                <h4>Spectator Dashboard</h4>
            </div>
        </div>
        <div class="divider"></div>
        <div class="row">
            <div class="col s12">
                <div class="row">

                    <a href="{{route('spectator.users')}}">
                       <div class="col s12" >
                         <div id="spectator-users-card" class="card-panel card-summary hoverable">
                            <div class="center white-text row">
                               <div class="col s4 label-wrapper">
                                  <div class="center">
                                     <i class="ecommerce-icon" id="statistics-icon">p</i>
                                     <div>
                                         Users
                                     </div>
                                  </div>

                               </div>

                               <div class="center white-text col s8" id="statistics-title-wrapper">
                                   SUMMARY PLACEHOLDER
                               </div>
                            </div>
                         </div>
                       </div>
                    </a>

                    <a href="{{route('spectator.products')}}">
                       <div class="col s12" >
                         <div id="spectator-products-card" class="card-panel card-summary hoverable">
                            <div class="center white-text row">
                               <div class="col s4 label-wrapper">
                                  <div class="center">
                                     <i class="ecommerce-icon" id="statistics-icon">v</i>
                                     <div>
                                         Products
                                     </div>
                                  </div>

                               </div>

                               <div class="center white-text col s8" id="statistics-title-wrapper">
                                   SUMMARY PLACEHOLDER
                               </div>
                            </div>
                         </div>
                       </div>
                    </a>

                    <a href="{{route('spectator.statistics')}}">
                       <div class="col s12" >
                         <div id="spectator-statistics-card" class="card-panel card-summary hoverable">
                            <div class="center white-text row">
                               <div class="col s4 label-wrapper">
                                  <div class="center">
                                     <i class="ecommerce-icon" id="statistics-icon">x</i>
                                     <div>
                                         Site Statistics
                                     </div>
                                  </div>

                               </div>

                               <div class="center white-text col s8" id="statistics-title-wrapper">
                                   SUMMARY PLACEHOLDER
                               </div>
                            </div>
                         </div>
                       </div>
                    </a>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('initScript')
    <script type="text/javascript" src="/js/admin/admin_custom.js"></script>
    <script type="text/javascript" src="/js/admin/users.js"></script>
    <script type="text/javascript" src="/js/admin/manageUsers_script.js"></script>
    <script type="text/javascript" src="/js/admin/pages.js"></script>
    <script type="text/javascript" src="/js/admin/managePages_script.js"></script>
@endsection

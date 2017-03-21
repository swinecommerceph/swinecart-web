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
            <div class="col s12 m12 l12">
                <div class="row">

                    <a href="{{route('spectator.users')}}">
                       <div class="col s12 m12 l12" >
                         <div id="spectator-users-card" class="card-panel spectator-card-summary hoverable">
                            <div class="center white-text row valign-wrapper">
                               <div class="col s4 m4 l4 label-wrapper valign">
                                  <div>
                                     <i class="ecommerce-icon" id="statistics-icon">p</i>
                                     <div>
                                         Users
                                     </div>
                                  </div>

                               </div>

                               <div class="white-text col s8 m8 l8" id="statistics-title-wrapper">

                                   <div class="row">
                                       <div class="col s12 m12 l12">
                                           <div class="spectator-summary-maintitle">
                                               Total Users
                                           </div>
                                           <div class="spectator-summary-maindata truncate tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$totalusers}}">
                                               {{$totalusers}}
                                           </div>
                                       </div>
                                   </div>

                                   <div class="row">
                                       <div class="col s12 m6 l6">
                                           <div class="spectator-summary-title">
                                               Breeders
                                           </div>
                                           <div class="spectator-summary-data truncate tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$totalbreeders}}">
                                               {{$totalbreeders}}
                                           </div>
                                       </div>

                                       <div class="col s12 m6 l6">
                                           <div class="spectator-summary-title">
                                               Customers
                                           </div>
                                           <div class="spectator-summary-data truncate tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$totalcustomers}}">
                                               {{$totalcustomers}}
                                           </div>
                                       </div>
                                   </div>
                               </div>
                            </div>
                         </div>
                       </div>
                    </a>
                    <a href="{{route('spectator.products')}}">
                        <div class="col s12 m12 l12" >
                          <div id="spectator-products-card" class="card-panel spectator-card-summary hoverable">
                             <div class="center white-text row valign-wrapper">
                                <div class="col s4 m4 l4 label-wrapper valign">
                                   <div>
                                      <i class="ecommerce-icon" id="statistics-icon">v</i>
                                      <div>
                                          Products
                                      </div>
                                   </div>

                                </div>

                                <div class="white-text col s8 m8 l8" id="statistics-title-wrapper">

                                    <div class="row">
                                        <div class="col s12 m12 l12">
                                            <div class="spectator-summary-maintitle">
                                                Total Products
                                            </div>
                                            <div class="spectator-summary-maindata truncate tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$totalproduct}}">
                                                {{$totalproduct}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col s12 m3 l3">
                                            <div class="spectator-summary-title">
                                                Boar
                                            </div>
                                            <div class="spectator-summary-data truncate tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$boar}}">
                                                {{$boar}}
                                            </div>
                                        </div>

                                        <div class="col s12 m3 l3">
                                            <div class="spectator-summary-title">
                                                Gilt
                                            </div>
                                            <div class="spectator-summary-data truncate tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$gilt}}">
                                                {{$gilt}}
                                            </div>
                                        </div>

                                        <div class="col s12 m3 l3">
                                            <div class="spectator-summary-title">
                                                Sow
                                            </div>
                                            <div class="spectator-summary-data truncate tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$sow}}">
                                                {{$sow}}
                                            </div>
                                        </div>

                                        <div class="col s12 m3 l3">
                                            <div class="spectator-summary-title">
                                                Semen
                                            </div>
                                            <div class="spectator-summary-data truncate tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$semen}}">
                                                {{$semen}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>
                          </div>
                        </div>
                    </a>

                    <a href="{{route('spectator.statistics')}}">
                       <div class="col s12 m12 l12" >
                         <div id="spectator-statistics-card" class="card-panel spectator-card-summary hoverable">
                            <div class="center white-text row valign-wrapper">
                               <div class="col s4 m4 l4 label-wrapper valign">
                                  <div>
                                     <i class="ecommerce-icon" id="statistics-icon">x</i>
                                     <div>
                                         Site Statistics
                                     </div>
                                  </div>

                               </div>

                               <div class="white-text col s8 m8 l8" id="statistics-title-wrapper">

                                   <div class="row">
                                       <div class="col s12 m12 l12">
                                           <div class="spectator-summary-maintitle">
                                               Total Transactions
                                           </div>
                                           <div class="spectator-summary-maindata truncate tooltipped" data-position="bottom" data-delay="50" data-tooltip="#">
                                               #
                                           </div>
                                       </div>
                                   </div>

                                   <div class="row">
                                       <div class="col s12 m6 l6">
                                           <div class="spectator-summary-title">
                                               New Breeders
                                           </div>
                                           <div class="spectator-summary-data truncate tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$newbreeders}}">
                                               {{$newbreeders}}
                                           </div>
                                       </div>

                                       <div class="col s12 m6 l6">
                                           <div class="spectator-summary-title">
                                               New Customers
                                           </div>
                                           <div class="spectator-summary-data truncate tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$newcustomers}}">
                                               {{$newcustomers}}
                                           </div>
                                       </div>
                                   </div>
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

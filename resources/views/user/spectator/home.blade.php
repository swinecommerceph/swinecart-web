@extends('layouts.newSpectatorLayout')

@section('title')
    | Spectator
@endsection

@section('pageId')
    id="page-spectator-home"
@endsection

@section('nav-title')
    Spectator Dashboard
@endsection

@section('content')
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
                                     <div class="spectator-homedashboard-icon-title center-align">
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
                                      <div class="spectator-homedashboard-icon-title center-align">
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
                                     <div class="spectator-homedashboard-icon-title center-align">
                                         Site Statistics
                                     </div>
                                  </div>

                               </div>

                               <div class="white-text col s8 m8 l8" id="statistics-title-wrapper">

                                   <div class="row">
                                       <div class="col s12 m12 l12">
                                           <div class="spectator-summary-maintitle">
                                              Completed Transactions<span class="tooltipped" data-position="right" data-delay="50" data-tooltip="This month">*</span>
                                           </div>
                                           <div class="spectator-summary-maindata truncate tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$transactions}}">
                                               {{$transactions}}
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
@endsection

@section('initScript')
    {{-- <script type="text/javascript" src="/js/admin/managePages_script.js"></script> --}}
@endsection

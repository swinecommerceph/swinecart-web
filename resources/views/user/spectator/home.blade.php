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
                    <div class="col s12">
                        <div id="users-card" class="card-panel hoverable">
                            <div class="white-text row">
                                <div class="col s2">
                                    <div class="spectator-ecommerce-icon col s12">p</div>
                                    <div class="col s12 center-align spectator-card-label">User</div>
                                </div>
                                <div class="vertical-divider col s1">|</div>
                                <div class="spectator-home-summary col s6">Summary</div>
                            </div>
                        </div>
                    </div>
                    <div class="col s12">
                        <div id="products-card" class="card-panel hoverable">
                            <div class="white-text row">
                                <div class="col s2">
                                    <div class="ecommerce-icon col s12">v</div>
                                    <div class="col s12 center">Products</div>
                                </div>
                                <div class="vertical-divider col s1">|</div>
                                <div class="spectator-home-summary col s6">Summary</div>
                            </div>
                        </div>
                    </div>
                    <div class="col s12">
                        <div id="statistics-card" class="card-panel hoverable">
                            <div class="white-text row">
                                <div class="col s4">
                                    <div class="ecommerce-icon s5"></div>
                                    <div class="col s5 ">Statistics</div>
                                </div>
                                <div class="vertical-divider col s1">|</div>
                                <div class="spectator-home-summary col s6">Summary</div>
                            </div>
                        </div>
                    </div>
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

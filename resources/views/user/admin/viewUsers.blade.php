{{--
    Displays all users
--}}

@extends('layouts.adminLayout')

@section('title')
    | Users
@endsection

@section('pageId')
    id="page-admin-view-users"
@endsection

@section('breadcrumbTitle')
    Users
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Users</a>
@endsection

@section('content')

        <div class="progress geocoding" style="display:none;">
          <div class="indeterminate"></div>
        </div>
        <div id="map-container">
            <div id="map-canvas" style="height:85vh;"></div>
        </div>
        <br>
        <form class="col s12 row" id="map-params" action="users" method="post">
            <div class="col s6">
              <input type="checkbox" class="filled-in cb-type" id="cb-breeders" name="breeders" {{ (isset($_POST['breeders']) || !isset($_POST['_token']))?'checked="checked"':''}}/>
              <label for="cb-breeders">Breeders</label>
            </div>
            <div class="col s6">
              <input type="checkbox" class="filled-in cb-type" id="cb-customers" name="customers" {{ (isset($_POST['customers']) || !isset($_POST['_token']))?'checked="checked"':''}}/>
              <label for="cb-customers">Customers</label>
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
@endsection

@section('customScript')

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjcVoiwU44Pj1fdY8JyxjORa8RElQQlGY"></script>
    <script src="/js/markerclusterer.js"></script>
    <script src="/js/Mapster.js"></script>
    <script src="/js/map-options.js"></script>
    <script type="text/javascript">

    </script>
    <script>
        var breeder_i, breeder_interval, breeder_arr;


        (function(window, google, mapster){

            var options = mapster.MAP_OPTIONS;

            element = document.getElementById('map-canvas');

            var geocoder = new google.maps.Geocoder();
            map = new Mapster.create(element, options);
            map.zoom(6);

            var loadingtimeout;

                    function geocode(opts){
                clearTimeout(loadingtimeout);
                $('.geocoding').show();
                geocoder.geocode({
                    address: opts.address
                }, function(results, status){
                    if(status === google.maps.GeocoderStatus.OK){
                        var result = results[0];
                        map.addMarker({
                            lat : result.geometry.location.lat(),
                            lng : result.geometry.location.lng(), 
                            content: opts.content,
                            icon: '/images/maps/breeder.png',
                            //link: '/customer/view-breeder/'+opts.id
                        });
                        loadingtimeout = setTimeout(function(){
                            $('.geocoding').hide();
                        }, 500);

                    }else if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {    
                        setTimeout(function() {
                            geocode(opts);
                        }, 200);
                    }else{
                        console.error(status);
                    }
                });
            }

            function geocode2(opts){
                clearTimeout(loadingtimeout);
                $('.geocoding').show();
                geocoder.geocode({
                    address: opts.address
                }, function(results, status){
                    if(status === google.maps.GeocoderStatus.OK){
                        var result = results[0];
                        map.addMarker({
                            lat : result.geometry.location.lat(),
                            lng : result.geometry.location.lng(), 
                            content: opts.content,
                            icon: '/images/maps/customer.png',
                            //link: '/breeder/view-customer/'+opts.id
                        });
                        loadingtimeout = setTimeout(function(){
                            $('.geocoding').hide();
                        }, 500);

                    }else if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {    
                        setTimeout(function() {
                            geocode2(opts);
                        }, 200);
                    }else{
                        console.error(status);
                    }
                });
            }
    
        $('.cb-type').change(function(){
            $.ajax({
                type: "POST",
                url: "users",
                data:{
                    _token: "{{{ csrf_token() }}}",
                    customers: $('#cb-customers').is(':checked'),
                    breeders: $('#cb-breeders').is(':checked'),
                }, 
                success: function(response){
                    map.clear();
                    map.markerClusterer.clearMarkers();
                    var breeders = response['breeders'];
                    var customers = response['customers'];
                    for(var i=0; i<breeders.length; i++){
                        geocode({
                            address : breeders[i].officeAddress_province+', Philippines',
                            content : breeders[i].name
                        });
                    }
                    for(var i=0; i<customers.length; i++){
                        geocode2({
                            address : customers[i].address_province +', Philippines',
                            content : customers[i].name
                        });
                    }
                }
            });
            

        });


            

            @foreach($breeders as $breeder)
                geocode({
                    address : '{{ $breeder->officeAddress_province }}, Philippines',
                    content : '{{ $breeder->users()->first()->name }}',
                    id : '{{ $breeder->id }}'
                });
            @endforeach
            @foreach($customers as $customer)
                geocode2({
                    address : '{{ $customer->address_province }}, Philippines',
                    content : '{{ $customer->users()->first()->name }}',
                    id : '{{ $customer->id }}'
                });
            @endforeach



        }(window, google, window.Mapster || (window.Mapster = {})))
    </script>
    
@endsection

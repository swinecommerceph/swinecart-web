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

    <div id="map-container">
        <div id="map-canvas"></div>
    </div>
    <div class="progress geocoding" style="display:none;">
      <div class="indeterminate"></div>
    </div>
    
   


@endsection

@section('customScript')

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjcVoiwU44Pj1fdY8JyxjORa8RElQQlGY"></script>
    <script src="/js/Mapster.js"></script>
    <script src="/js/map-options.js"></script>
    <script type="text/javascript">
        function testajax(url, params) {
    var f = $("<form target='_blank' method='POST' style='display:none;'></form>").attr({
        action: url
    }).appendTo(document.body);

    for (var i in params) {
        if (params.hasOwnProperty(i)) {
            $('<input type="hidden" />').attr({
                name: i,
                value: params[i]
            }).appendTo(f);
        }
    }

    f.submit();

    f.remove();
}
    </script>
    <script>
        var breeder_i, breeder_interval, breeder_arr;


        (function(window, google, mapster){

            var options = mapster.MAP_OPTIONS;

            element = document.getElementById('map-canvas');

            var geocoder = new google.maps.Geocoder();
            map = new Mapster.create(element, options);
            map.zoom(6);

            function geocode(opts){
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
                            icon: '/images/pigmarker.png'
                        });
                        $('.geocoding').hide();

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
                            icon: '/images/pigmarker2.png'
                        });
                        $('.geocoding').hide();

                    }else if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {    
                        setTimeout(function() {
                            geocode2(opts);
                        }, 200);
                    }else{
                        console.error(status);
                    }
                });
            }

            @foreach($customers as $customer)
                geocode2({
                    address : '{{ $customer->address_province }}, Philippines',
                    content : '{{ $customer->users()->first()->name }}'
                });
            @endforeach


            @foreach($breeders as $breeder)
                geocode({
                    address : '{{ $breeder->officeAddress_province }}, Philippines',
                    content : '{{ $breeder->users()->first()->name }}'
                });
            @endforeach

        }(window, google, window.Mapster || (window.Mapster = {})))
    </script>
    
@endsection

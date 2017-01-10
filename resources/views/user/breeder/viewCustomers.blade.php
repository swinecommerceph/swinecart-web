{{--
    Displays products of all Breeder users
--}}

@extends('user.customer.home')

@section('title')
    | Breeders
@endsection

@section('pageId')
    id="page-customer-view-breeders"
@endsection

@section('breadcrumbTitle')
    Breeders
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Breeders</a>
@endsection

@section('content')


      

    <div id="map-container">
    <div id="map-canvas"></div>
  </div>
    
   


@endsection

@section('customScript')

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjcVoiwU44Pj1fdY8JyxjORa8RElQQlGY"></script>
    <script src="/js/Mapster.js"></script>
    <script src="/js/map-options.js"></script>
    <script>
        (function(window, google, mapster){

            var options = mapster.MAP_OPTIONS;

            element = document.getElementById('map-canvas');

            var geocoder = new google.maps.Geocoder();
            map = new Mapster.create(element, options);

            function geocode(opts){
                geocoder.geocode({
                    address: opts.address
                }, function(results, status){
                    if(status === google.maps.GeocoderStatus.OK){
                        var result = results[0];
                        map.addMarker({
                            lat : result.geometry.location.lat(),
                            lng : result.geometry.location.lng(), 
                            draggable : true,
                            content: opts.content
                        });

                    }else{
                        console.error(status);
                    }
                });
            }

            @foreach($customers as $customer)
                geocode({
                    address : '{{ $customer->address_province }}, Philippines',
                    content : '{{ $customer->users()->first()->name }}'
                });
            @endforeach




        }(window, google, window.Mapster || (window.Mapster = {})))



    </script>
    
@endsection

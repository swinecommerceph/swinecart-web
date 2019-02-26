{{--
    Displays all Breeders
--}}

@extends('user.customer.home')

@section('title')
    | Breeders
@endsection

@section('pageId')
    id="page-customer-view-breeders"
@endsection

@section('breadcrumbTitle')
    Breeder Farms and their Products
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Breeders</a>
@endsection

@section('content')
    <div class="row container">
        <p style="color:hsl(0, 0%, 40%);">Select which product(s) to find: </p>
        
        {{-- Checkbox --}}
        <form class="col s2" id="map-params" action="breeders" method="post">
            <p>
              <input type="checkbox" class="filled-in cb-type" id="cb-gilt" name="gilt" {{ (isset($_POST['gilt']) || !isset($_POST['_token']))?'checked="checked"':''}}/>
              <label for="cb-gilt">Gilt</label>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="checkbox" class="filled-in cb-type" id="cb-sow" name="sow" {{ (isset($_POST['sow']) || !isset($_POST['_token']))?'checked="checked"':''}}/>
              <label for="cb-sow">Sow</label>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="checkbox" class="filled-in cb-type" id="cb-boar" name="boar" {{ (isset($_POST['boar']) || !isset($_POST['_token']))?'checked="checked"':''}}/>
              <label for="cb-boar">Boar</label>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="checkbox" class="filled-in cb-type" id="cb-semen" name="semen" {{ (isset($_POST['semen']) || !isset($_POST['_token']))?'checked="checked"':''}}/>
              <label for="cb-semen">Semen</label>
            </p>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <br/>
        </form>

        {{-- World Map --}}
        <div class="col s10">
            {{-- Preloader --}}
            <div class="row s12 progress geocoding" style="display:none;">
              <div class="indeterminate"></div>
            </div>

            {{-- Actual Map --}}
            <div class="row" id="map-container">
                <div id="map-canvas" style="height:60vh; max-width: auto;"></div>
            </div>
        </div>
    </div>
    
   


@endsection

@section('customScript')

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjcVoiwU44Pj1fdY8JyxjORa8RElQQlGY"></script>
    <script src="/js/markerclusterer.js"></script>
    <script src="/js/Mapster.js"></script>
    <script src="/js/map-options.js"></script>
   
    <script>
        (function(window, google, mapster){

            $('.cb-type').change(function(){
                
                $.ajax({
                    type: "POST",
                    url: "breeders",
                    data:{
                        _token: "{{{ csrf_token() }}}",
                        gilt: $('#cb-gilt').is(':checked'),
                        sow: $('#cb-sow').is(':checked'),
                        boar: $('#cb-boar').is(':checked'),
                        semen: $('#cb-semen').is(':checked'),
                    }, 
                    success: function(response){
                        map.clear();
                        map.markerClusterer.clearMarkers();
                        var breeders = response;
                        //var breeder_i = 0;
                        for(var i=0; i<breeders.length; i++){
                            //console.log(breeders[i]);
                            geocode({
                                address : breeders[i].officeAddress_province+', Philippines',
                                content : breeders[i].name
                            });
                        }
                    }
                });
                

            });

            
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
                            draggable : true,
                            content: opts.content,
                            icon: '/images/maps/breeder.png',
                            link: '/customer/view-breeder/'+opts.id
                        });
                        loadingtimeout = setTimeout(function(){
                            $('.geocoding').hide();
                        }, 500);
                    }
                    else if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {    
                        setTimeout(function() {
                            geocode(opts);
                        }, 200);
                    }else{
                        console.error(status);
                    }
                });
            }
           
            @foreach($results as $result)
                geocode({
                    address : '{{ $result->province }}, Philippines',
                    content : '{{ $result->name }}, {{ $result->province }}',
                    id : '{{ $result->id }}'
                });
            @endforeach
            
        }(window, google, window.Mapster || (window.Mapster = {})))
    </script>
    
@endsection

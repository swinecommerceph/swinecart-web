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

    <form id="map-params" action="breeders" method="post">
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
    <div id="map-container">

    <div id="map-canvas"></div>
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
        (function(window, google, mapster){

            $('.cb-type').change(function(){
                $('#map-params').submit();

                /*    
                console.log($(this).is(':checked'));

                testajax("breeders",{
                        _token: "{{{ csrf_token() }}}",
                        gilt: $('#cb-gilt').is(':checked'),
                        sow: $('#cb-sow').is(':checked'),
                        boar: $('#cb-boar').is(':checked'),
                        semen: $('#cb-semen').is(':checked'),
                    });
                return;
                */
                /*
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
                        breeders = JSON.parse(response);
                        for(var i=0; i<breeders.length; i++){
                            console.log(breeders[i]);
                            geocode({
                                address : breeders[i].officeAddress_province+', Philippines',
                                content : 'breeders.users().first().name'
                            });
                        }
                    }
                });
                */

            });


            var options = mapster.MAP_OPTIONS;

            element = document.getElementById('map-canvas');

            var geocoder = new google.maps.Geocoder();
            map = new Mapster.create(element, options);
            map.zoom(6);

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

            @foreach($breeders as $breeder)
                geocode({
                    address : '{{ $breeder->officeAddress_province }}, Philippines',
                    content : '{{ $breeder->users()->first()->name }}'
                });
            @endforeach


        }(window, google, window.Mapster || (window.Mapster = {})))
    </script>
    
@endsection

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
				var marker = map.addMarker({
					lat : result.geometry.location.lat(),
					lng : result.geometry.location.lng(), 
					//draggable : true,
					content: 'I like pizza'
				});
				/*map.gMap.panTo({
					lat : result.geometry.location.lat(),
					lng : result.geometry.location.lng()
				});*/		
			}else{
				console.error(status);
			}
		});
	}

	


}(window, google, window.Mapster || (window.Mapster = {})))
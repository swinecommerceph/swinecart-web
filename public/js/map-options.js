(function(window, google, mapster){

	mapster.MAP_OPTIONS = {
		center: {
			lat: 12.459037, 
			lng: 122.192494
		},
		zoom: 7,
		//disableDefaultUI: true,
		//scrollwheel:false,
		//draggable: false,
		maxZoom: 13,
		//minZoom: 9,
		zoomControlOptions: {
			position: google.maps.ControlPosition.TOP_LEFT
		},
		panControlOptions: {
			position: google.maps.ControlPosition.TOP_LEFT
		}
	}

}(window, google, window.Mapster || (window.Mapster = {})))
(function(window, google, mapster){

	mapster.MAP_OPTIONS = {
		center: {
			lat: 14.6090537, 
			lng: 121.02225650000003
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
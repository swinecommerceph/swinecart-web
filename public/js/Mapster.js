(function(window, google){

	var Mapster = (function(){
		function Mapster(element, opts) {
			this.gMap = new google.maps.Map(element, opts);
			this.markers = [];
		}
		Mapster.prototype = {
			zoom : function(level){
				if(level){
					this.gMap.setZoom(level);
				} else{
					return this.gMap.getZoom();
				}
			},

			_on : function(opts){
				var self = this;
				google.maps.event.addListener(opts.obj, opts.event, function(e){
					opts.callback.call(self, e);
				});
			},

			clear : function(){

				var size = this.markers.length
				for(var i=0; i<size; i++){
					this._removeMarker(this.markers[0]);
				}
			},

			addMarker : function(opts){
				var marker;
				var infoWindow;
				opts.position = {
					lat : opts.lat,
					lng : opts.lng
				};
				opts.icon = '/images/pigmarker.png';
				marker = this._createMarker(opts);
				this._addMarker(marker);
				if(opts.event) {
					this._on({
						obj : marker,
						event :  opts.event.name,
						callback : opts.event.callback
					});
				}
				if(opts.content){
					this._on({
						obj : marker,
						event :  'mouseover',
						callback : function(e){
							infoWindow = new google.maps.InfoWindow({
								content: opts.content
							});
							infoWindow.open(map.gMap, marker);
						}
					});


					this._on({
						obj : marker,
						event :  'mouseout',
						callback : function(e){
							
							infoWindow.close(map.gMap, marker);
						}
					});
				}

				return marker;
			},

			_addMarker : function(marker){
				this.markers.push(marker);
			},

			_removeMarker : function(marker){
				var indexOf = this.markers.indexOf(marker);
				if(indexOf != -1){
					this.markers.splice(indexOf, 1);
					marker.setMap(null);
				}
			},

			_createMarker: function(opts){
				opts.map = this.gMap;
				return new google.maps.Marker(opts);
			}

		}
		return Mapster;
	}());

	Mapster.create = function(elements, opts){
		return new Mapster(elements, opts);
	}
 	
 	window.Mapster = Mapster;

}(window, google))
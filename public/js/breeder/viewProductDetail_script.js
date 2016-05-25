$(document).ready(function(){

    // Initialization for imagezoom
	$('[data-imagezoom]').imageZoom({
		cursor:'zoom-in',
		opacity:0.3,
		zoomviewsize: [$('.collection.with-header').width()+3,$('.card-image img').height()],
		zoomviewborder: 'solid 3px #ccc'
	});

	// Initialization for videojs
	videojs.options.flash.swf = "/js/vendor/video-js/video-js.swf";

	// Temporary solution to Video Carousel bug
	$('#video-carousel-tab').click(function(){
		$('#videos-carousel .carousel').carousel('next');
	});

	// Change display to respective image upon carousel-item click
	$('#images-carousel .carousel-item').click(function(e){
		e.preventDefault();
		var img_src = $(this).find('img').attr('src');
		$('#video-display').hide();
		$('.card-image img').attr('src', img_src);
		$('.card-image img').show();
	});

	// Change display to respective video upon carousel-item click
	$('.carousel-item .responsive-video').click(function(e){
		e.preventDefault();
		var video_src = $(this).find('source').attr('src');
		var video_element =
			'<video id="video-display" class="responsive-video video-js vjs-default-skin vjs-big-play-centered">'+
				'<source src="'+ video_src +'" type="video/mp4">'+
				'<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>'+
			'</video>';

		// Check if there is already an existing video player
		if($('#video-display').length){
			// Just change the source of the video player
			$('.card-image img').hide();
			$('#video-display video').attr('src', video_src);
			$('#video-display video source').attr('src', video_src);
			if($('#video-display').is(':hidden')) $('#video-display').show();

		}
		else{
			//  Create new instance of video player
			$('.card-image img').hide();
			$('.card-image').append(video_element);
			videojs("video-display", { "controls": true });
			$('#video-display').show();
		}
	});
});

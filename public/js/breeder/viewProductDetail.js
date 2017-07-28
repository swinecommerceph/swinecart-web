/**
 * ImageZoom Plugin
 * http://0401morita.github.io/imagezoom-plugin
 * MIT licensed
 *
 * Copyright (C) 2014 http://0401morita.github.io/imagezoom-plugin A project by Yosuke Morita
 */
!function(o){var i,e,r,t,s,c,n={cursorcolor:"255,255,255",opacity:.5,cursor:"crosshair",zindex:2147483647,zoomviewsize:[480,395],zoomviewposition:"right",zoomviewmargin:10,zoomviewborder:"none",magnification:1.925},a={init:function(m){$this=o(this),i=o(".imagezoom-cursor"),e=o(".imagezoom-view"),o(document).on("mouseenter",$this.selector,function(u){var g=o(this).data();r=o.extend({},n,m,g),c=o(this).offset(),t=o(this).width(),s=o(this).height(),cursorSize=[r.zoomviewsize[0]/r.magnification,r.zoomviewsize[1]/r.magnification],1==g.imagezoom?imageSrc=o(this).attr("src"):imageSrc=o(this).get(0).getAttribute("data-imagezoom");var z,l=u.pageX,d=u.pageY;o("body").prepend('<div class="imagezoom-cursor">&nbsp;</div><div class="imagezoom-view"><img src="'+imageSrc+'"></div>'),z="right"==r.zoomviewposition?c.left+t+r.zoomviewmargin:c.left-t-r.zoomviewmargin,o(e.selector).css({position:"absolute",left:z,top:c.top,width:cursorSize[0]*r.magnification,height:cursorSize[1]*r.magnification,background:"#000","z-index":2147483647,overflow:"hidden",border:r.zoomviewborder}),o(e.selector).children("img").css({position:"absolute",width:t*r.magnification,height:s*r.magnification}),o(i.selector).css({position:"absolute",width:cursorSize[0],height:cursorSize[1],"background-color":"rgb("+r.cursorcolor+")","z-index":r.zindex,opacity:r.opacity,cursor:r.cursor}),o(i.selector).css({top:d-cursorSize[1]/2,left:l}),o(document).on("mousemove",document.body,a.cursorPos)})},cursorPos:function(n){var a=n.pageX,m=n.pageY;return m<c.top||a<c.left||m>c.top+s||a>c.left+t?(o(i.selector).remove(),void o(e.selector).remove()):(a-cursorSize[0]/2<c.left?a=c.left+cursorSize[0]/2:a+cursorSize[0]/2>c.left+t&&(a=c.left+t-cursorSize[0]/2),m-cursorSize[1]/2<c.top?m=c.top+cursorSize[1]/2:m+cursorSize[1]/2>c.top+s&&(m=c.top+s-cursorSize[1]/2),o(i.selector).css({top:m-cursorSize[1]/2,left:a-cursorSize[0]/2}),o(e.selector).children("img").css({top:(c.top-m+cursorSize[1]/2)*r.magnification,left:(c.left-a+cursorSize[0]/2)*r.magnification}),void o(i.selector).mouseleave(function(){o(this).remove()}))}};o.fn.imageZoom=function(i){return a[i]?a[i].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof i&&i?void o.error(i):a.init.apply(this,arguments)},o(document).ready(function(){o("[data-imagezoom]").imageZoom()})}(jQuery);

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

	Vue.component('average-star-rating',{
	    template: '#average-star-rating',
	    props: ['rating'],
	    computed: {
	        ratingToPercentage: function(){
	            return (100* this.rating / 5);
	        }
	    }
	});

	var starsContainer = new Vue({
		el: '#stars-container',
		data: {},
		filters: {
			round: function(value){
	            // Round number according to precision
	            var precision = 2;
	            var factor = Math.pow(10, precision);
	            var tempNumber = value * factor;
	            var roundedTempNumber = Math.round(tempNumber);
	            return roundedTempNumber / factor;
	        }
		}
	});
});

//# sourceMappingURL=viewProductDetail.js.map

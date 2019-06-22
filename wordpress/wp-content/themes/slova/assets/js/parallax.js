(function($) {
	"use strict";
    $.fn.parallax = function(options) {

        var windowHeight = $(window).height();

        // Establish default settings
        var settings = $.extend({
            speed : 0.20
        }, options);

        // Iterate over each object in collection
        return this.each( function() {

			// Save a reference to the element
			var $this = $(this);
			var params = {
				windowWidth: 0,
				scrollTop: 0,
				offset: 0,
				height: 0,
				yBgPosition: 0
			}
			// Set up Scroll Handler
			$(document).scroll(function(){

				params.scrollTop = $(window).scrollTop();
				params.offset = $this.offset().top;
				params.height = $this.outerHeight();
				params.windowWidth = $(window).width();

				// Check if above or below viewport
				if (params.offset + params.height <= params.scrollTop || params.offset >= params.offset + windowHeight) {
					return;
				}

				params.yBgPosition = Math.round((params.offset - params.scrollTop) * settings.speed);
				
				if( params.windowWidth <= 360 ){ params.yBgPosition = 0; }

				// Apply the Y Background Position to Set the Parallax Effect
				//$this.css('background-position', 'center ' + yBgPosition + 'px');
				$this.css('cssText', 'background-position: center ' + params.yBgPosition + 'px !important; background-attachment: fixed !important; background-size: cover !important;');		
			}).trigger('scroll');
		});
    }

jQuery(document).ready(function ($) {
    "use strict";
    var window_height = $(window).height();
    $('.stripe-parallax-bg').each(function () {
        var $this = $(this),
			speedFactor = $this.data('parallax-speed');
		$this.css({
			'background-attachment': 'fixed',
			'background-size': 'cover'
		}).parallax({
			speed : speedFactor
		});
    });
    /*Video parallax*/
    $('.stripe-parallax-video').each(function () {
        var $this = $(this),$video = $this.find('video'),speedFactor=$(this).data('parallax-speed')||0.7,ratio=0,video_bottom=0;
        var video_height = 0;
        var el_height = $(this).outerHeight();
        $video.bind('loadeddata', function(){
            adjustvideo();
            doScroll();
        });
        var adjustvideo = function(){
            ratio = $video.data('ratio');
            //video_height = $(window).width() / ratio;
            video_height = el_height + (el_height + window_height) * (1 - speedFactor);
            if (video_height < $(window).width() / ratio) {
                video_height = $(window).width() / ratio
            }
            $video.css({
                'width': video_height * ratio + 'px',
                'maxWidth': video_height * ratio + 'px',
                bottom: 0
            });
        }
        if (speedFactor == 1) return;
        var doScroll = function(){
            if ($(window).width() < 767) return;
            $video.attr(
                {
                    'appear':isappears($this),
                    'delta':Math.round(isappears($this) * (1-speedFactor))
                }
            );
            var delta = Math.round(isappears($this) * (1-speedFactor));
            $video.css({
                bottom: (0 - delta) + 'px'
            })
        };
        adjustvideo();
        doScroll();
        $(window).bind('scroll', doScroll).bind('resize',function(){
            adjustvideo();
            doScroll();
        }).trigger('scroll');		
    })

    function isappears(element) {
        var scrolltop = $(window).scrollTop(),top = $(element).offset().top;
        if(top < $(window).height()){
            return scrolltop;
        }
        if ((scrolltop + $(window).height()) >= ($(element).offset().top)) {
            return scrolltop + $(window).height() - $(element).offset().top;
        }
        return 0;
    }
});

}(jQuery));
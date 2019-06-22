!(function($){
	"use strict";
	jQuery(document).ready(function($) {
		// Same Height
		jQuery('.vc_row.same-height .row').each(function() {
			var MaxHeight = 0;
			jQuery(this).children().each(function() {
				var height = jQuery(this).height();
				if(MaxHeight < height) {
					MaxHeight = height;
				}
			});
			jQuery(this).children().each(function() {
				jQuery(this).css('min-height', MaxHeight);
			});
		});
		//Search in menu
		function ROSearchInMenu() {
			$('#ro-search-form').on('click', function() {
				$('#ro-search-form-popup').toggleClass('ro-show');
			});
		}
		ROSearchInMenu();
		//Canvas menu
		function ROCanvasMenu() {
			$('#ro-canvas-menu').on('click', function() {
				$('body').toggleClass('ro-cm-open');
			});
		}
		ROCanvasMenu();
		//Back top
		function ROBackTop() {
			$('#ro-backtop').on('click', function() {
				$('html,body').animate({
					scrollTop: 0
				}, 400);
				return false;
			});

			if ($(window).scrollTop() > 300) {
				$('#ro-backtop').addClass('ro-show');
			} else {
				$('#ro-backtop').removeClass('ro-show');
			}

			$(window).on('scroll', function() {

				if ($(window).scrollTop() > 300) {
					$('#ro-backtop').addClass('ro-show');
				} else {
					$('#ro-backtop').removeClass('ro-show');
				}
			});
		}
		ROBackTop();
		//Date picker
		function RODatePicker() {
			if ($('.ro-date-picker').length) {
				$('.ro-date-picker').datepicker();
			}
		}
		RODatePicker();
		//useful var
		var $window = $(window);
		//Scroll To
		function RONextSection() {
			var scrollTo = $('.ro_scroll_next').height();
			if($( ".ro-header-v1 " ).hasClass( "ro-header-stick" )) {
				scrollTo = scrollTo - $('.ro-header-v1').height();
			}
			
			$('.ro-btn-scroll').on('click', function() {
				$('html,body').animate({
					scrollTop: scrollTo
				}, 500);
				return false;
			});
		}
		RONextSection();
		/* Make video scale like background-size:cover */
		function ROVideoCover(VideoRatio) {
			$('.ro-video-bg-wrapper').each(function() {
				var $this = $(this);
				if ($this.height() * VideoRatio > $this.width())
					$(this).addClass('ro-video-h');
				else
					$(this).removeClass('ro-video-h');
				$(window).on('resize', function() {
					if ($this.height() * VideoRatio > $this.width())
						$this.addClass('ro-video-h');
					else
						$this.removeClass('ro-video-h');
				});
			});
		}
		ROVideoCover(16 / 9);
		//Video popup
		function ROheadervideo() {
			$("#ro-play-button").on("click", function(e){
				e.preventDefault();
					$.fancybox({
					'padding' : 0,
					'autoScale': false,
					'transitionIn': 'none',
					'transitionOut': 'none',
					'title': this.title,
					'width': 720,
					'height': 405,
					'href': this.href.replace(new RegExp("watch\\?v=", "i"), 'v/'),
					'type': 'swf',
					'swf': {
					'wmode': 'transparent',
					'allowfullscreen': 'true'
					}
				});
			});
		}
		ROheadervideo();
		/* Open the hide menu */
		function ROOpenMenu() {
			$('#ro-hamburger').on('click', function() {
				$('.ro-menu-list').toggleClass('hidden-xs');
				$('.ro-menu-list').toggleClass('hidden-sm');
			});
		}
		ROOpenMenu();
		/* Header V1 Stick */
		function ROHeaderStick() {
			if($( ".ro-header-v1" ).hasClass( "ro-header-stick" )) {
				var header_offset = $('.ro-header-stick').offset();
			
				if ($window.scrollTop() > header_offset.top) {
					$('body').addClass('ro-stick-active');
				} else {
					$('body').removeClass('ro-stick-active');
				}

				$window.on('scroll', function() {
					if ($window.scrollTop() > 0) {
						$('body').addClass('ro-stick-active');
					} else {
						$('body').removeClass('ro-stick-active');
					}
				});
				
				$window.on('load', function() {
					if ($window.scrollTop() > 0) {
						$('body').addClass('ro-stick-active');
					} else {
						$('body').removeClass('ro-stick-active');
					}
				});
				$window.on('resize', function() {
					if ($window.scrollTop() > 0) {
						$('body').addClass('ro-stick-active');
					} else {
						$('body').removeClass('ro-stick-active');
					}
				});
			}
		}
		ROHeaderStick();
		
		/* Menu sidebar right */
		if(jQuery('.ro_widget_mini_cart').length > 0){
			jQuery('.ro_widget_mini_cart > a').click(function () {
					jQuery('.ro_widget_mini_cart .ro-cart-content').toggleClass('active');
			});
		}
		if(jQuery('#ro-search-form').length > 0){
			jQuery('#ro-search-form').click(function () {
					jQuery('.ro-menu-sidebar .widget_search').toggleClass('active');
			});
		}
		
		/* Active blog slider */
		function ROBlogSlider() {
			$('.ro-blog-slider').flexslider({
				animationSpeed: 700,
				animation: "slide",
				controlNav: false,
				directionNav: true,
				prevText: "",
				nextText: "",
				itemWidth: 384,
				itemMargin: 0,
				minItems: 1, 
				maxItems: 5,
				move: 1,
			});
		}
		ROBlogSlider();
		$( window ).resize(function() { ROBlogSlider(); });
		
		/* Active latest news slider */
		function ROLatestNewsSlider() {
			$('.ro-latest-news-slider').flexslider({
				animationSpeed: 700,
				animation: "slide",
				controlNav: false,
				directionNav: true,
				prevText: "",
				nextText: "",
			});
		}
		ROLatestNewsSlider();
		
		/* Active portfolio slider */
		function ROPortfolioSlider() {
			$('.ro-portfolio-slider').flexslider({
				animationSpeed: 700,
				animation: "slide", 
				controlNav: true,
				directionNav: false,
				itemWidth: 360,
				itemMargin: 30,
				minItems: 1, 
				maxItems: 3,
				move: 1,
			});
		}
		ROPortfolioSlider();
		
		/* Active video slider */
		function ROVideoSlider() {
			
			$('.ro-video-slider').flexslider({
				animationSpeed: 700,
				animation: "slide", 
				controlNav: true,
				directionNav: true,
			});
			
		}
		ROVideoSlider();
		
		//Video slider popup
		function ROSliderVideo() {
			$(".ro-play-video-popup").on("click", function(e){
				e.preventDefault();
					$.fancybox({
					'padding' : 0,
					'autoScale': false,
					'transitionIn': 'none',
					'transitionOut': 'none',
					'title': this.title,
					'width': 720,
					'height': 405,
					'href': this.href.replace(new RegExp("watch\\?v=", "i"), 'v/'),
					'type': 'swf',
					'swf': {
					'wmode': 'transparent',
					'allowfullscreen': 'true'
					}
				});
			});
		}
		ROSliderVideo();
		
		/* Active testimonial slider */
		function ROTestimonalSlider() {
			$('.ro-testimonial-slider').flexslider({
				animationSpeed: 700,
				animation: "slide", 
				controlNav: true,
				directionNav: true,
			});
		}
		ROTestimonalSlider();
		
		/* Active testimonial carousel */
		function ROTestimonalCarousel() {
			$('.ro-testimonial-carousel .owl-carousel').owlCarousel({
				loop:true,
				margin:0,
				navText:['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
				dots:false,
				responsiveClass:true,
				responsive:{
					0:{
						items:1,
					},
					768:{
						items:2,
					},
					992:{
						items:3,
					},
					1200:{
						items:4,
						nav:true,
					}
				}
			});
		}
		ROTestimonalCarousel();
		
		/* Active cross sells product slider */
		function ROCrossSellsSlider() {
			$('.ro-cross-sells-slider').flexslider({
				animationSpeed: 700,
				animation: "slide", 
				controlNav: true,
				directionNav: false,
			});
		}
		ROCrossSellsSlider();
		
		/* Active tweets slider */
		function ROTweetsSlider() {
			$('.ro-tweets-slider').flexslider({
				animationSpeed: 700,
				animation: "slide", 
				controlNav: true,
				directionNav: true,
				prevText: '',
				nextText: '',
			});
		}
		ROTweetsSlider();
		
		/*Count up*/
		if($( ".ro-number" ).length > 0) {
			$('.ro-number').counterUp({
				delay: 10,
				time: 1000
			});
		}
		/*Count down*/
		function ROcountdownClock() {
			$('.ro-countdown-clock').each(function() {
				var countdownTime = $(this).attr('data-countdown');
				$(this).countdown({
					until: countdownTime,
					format: 'DHMS',
					padZeroes: true
				});
			});
		}
		ROcountdownClock();
		
		/*Blog Special Carousel*/
		function bsCarouselCustomAnimateNexPrev( elem ) {
			elem.fadeOut( 0 );
			elem.fadeIn( 'slow' );
		}
	
		function bsCarouselCustomNextPrev() {
			var item = jQuery('#ro-blog-special-carousel .carousel-inner .item');
			var item_active = jQuery('#ro-blog-special-carousel .carousel-inner .item.active');
			
			var slide_pre = jQuery(item_active).prev().data('slide');
			if(!slide_pre) {
				slide_pre = jQuery(item).last().data('slide');
			}
			var $pre_elem = jQuery( '<div>', {
				class: 'ro-blog-item',
				html: '<div class="ro-arrow-left"><span><i class="fa fa-long-arrow-left"></i></span></div>'+
						'<img src="' + slide_pre.thumb + '" alt="">'+
						'<div class="ro-meta-btn"><i class="fa fa-calendar"></i><span>' + slide_pre.date + '</span></div>',
			} )
			jQuery( "#ro-blog-special-carousel .left.carousel-control" ).html( $pre_elem );
			bsCarouselCustomAnimateNexPrev( $pre_elem );
			
			var slide_next = jQuery(item_active).next().data('slide');
			if(!slide_next) {
				slide_next = jQuery(item).first().data('slide');
			}
			
			var $next_elem = jQuery( '<div>', {
				class: 'ro-blog-item',
				html: '<div class="ro-arrow-right"><span><i class="fa fa-long-arrow-right"></i></span></div>'+
						'<img src="' + slide_next.thumb + '" alt="">'+
						'<div class="ro-meta-btn"><i class="fa fa-calendar"></i><span>' + slide_next.date + '</span></div>',
			} )
			jQuery( "#ro-blog-special-carousel .right.carousel-control" ).html( $next_elem ); 
			bsCarouselCustomAnimateNexPrev( $next_elem );
		}
	
		jQuery('#ro-blog-special-carousel').on('slid.bs.carousel', function () { 
			bsCarouselCustomNextPrev();
		});
		
		/* Mixitup */
		if ($.fn.mixItUp) { $('#Container').mixItUp(); }
		/*Masonry*/
		if($('.grid-masonry').length > 0) {
			$('.grid-masonry').isotope({
				// options
			});
		}
		/* Disable scrolling zoom on maps */
		$('#map').addClass('scrolloff');
		$('.ro_overlay_map').on("mouseup",function(){
			$('#map').addClass('scrolloff'); 
		});
		$('.ro_overlay_map').on("mousedown",function(){
			$('#map').removeClass('scrolloff');
		});
		$("#map").mouseleave(function () { 
			$('#map').addClass('scrolloff');
		});
		
		/**
         * Add Product Quantity Up Down icon
         */
        $('form .quantity').each(function() {
            $(this).prepend('<span class="qty-plus"><i class="fa fa-angle-up"></i></span><span class="qty-minus"><i class="fa fa-angle-down"></i></span>');
        });
        /* Plus Qty */
        $(document).on('click', '.qty-plus', function() {
            var parent = $(this).parent();
            $('input.qty', parent).val( parseInt($('input.qty', parent).val()) + 1);
        });
        /* Minus Qty */
        $(document).on('click', '.qty-minus', function() {
            var parent = $(this).parent();
            if( parseInt($('input.qty', parent).val()) > 1) {
                $('input.qty', parent).val( parseInt($('input.qty', parent).val()) - 1);
            }
        });
		/*Share group*/
		$('.ro-share > a').on('click', function() {
			$('.ro-share > ul').toggle(500);
		});
	});
})(jQuery);
jQuery(document).ready(function($){


/* ========================================================================= */
/*  Portfolio filter js
/* ========================================================================= */ 

      if($('.border').length){
        //portfolio filter
        jQuery(window).load(function(){
             $portfolio_selectors = $('.border>li>a');
             $portfolio_selectors.on('click', function(){
                 var selector = $(this).attr('data-filter');
                 return false;
             });
        });
      };

      if($('.slash').length){
        //portfolio filter
        jQuery(window).load(function(){
             $portfolio_selectors = $('.slash>li>a');
             $portfolio_selectors.on('click', function(){
                 var selector = $(this).attr('data-filter');
                 return false;
             });
        });
      };

      if($('.round').length){
        //portfolio filter
        jQuery(window).load(function(){
             $portfolio_selectors = $('.round>li>a');
             $portfolio_selectors.on('click', function(){
                 var selector = $(this).attr('data-filter');
                 return false;
             });
        });
      };



      [1,2,3,4].forEach(function(i) {
        if($('.hover-' + i).length){
          $('.hover-'+ i).mixItUp({
          });
        }
      });

      
/* ========================================================================= */
/*  Portfilo hoverdir js and light case
/* ========================================================================= */

    jQuery(document).ready(function(){
      $('#hover-1 .portfolio-item').each( function() { $(this).hoverdir(); } );
    });

    // $(document).ready(function(){
    //     $("a[data-rel^='lightcase']").lightcase();
    // });


/* ========================================================================= */
/*  SLider height browser js
/* ========================================================================= */

    // Hero area auto height adjustment
    $('#tgx-hero-unit .carousel-inner .item') .css({'height': (($(window).height()))+'px'});
    $(window).resize(function(){
        $('#tgx-hero-unit .carousel-inner .item') .css({'height': (($(window).height()))+'px'});
    });

if($('.tgx-project').length){
  jQuery(".tgx-project").addClass("owl-carousel").owlCarousel({
        pagination: true,
        center: true,
        margin:100,
        dots:false,
        loop:true,
        items:2,
        nav: true,
        navClass: ['owl-carousel-left','owl-carousel-right'],
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        autoHeight : true,
        autoplay:false,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:2
            }
        }
     });
  };



});



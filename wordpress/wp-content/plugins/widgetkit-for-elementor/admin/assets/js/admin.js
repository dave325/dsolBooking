//Go Between the Tabs
( function ( $ ){
    "use strict";
    $(".widgetkit-settings-tabs").tabs();
    
    
    $("a.widgetkit-tab-list-item").on("click", function () {
        var tabHref = $(this).attr('href');
        window.location.hash = tabHref;
        $("html , body").scrollTop(tabHref);
    });
    
    $(".widgetkit-checkbox").on("click", function(){
       if($(this).prop("checked") == true) {
           $(".widgetkit-elements-table input").prop("checked", 1);
       }else if($(this).prop("checked") == false){
           $(".widgetkit-elements-table input").prop("checked", 0);
       }
    });
   
    
    $( 'form#widgetkit-settings' ).on( 'submit', function(e) {
		e.preventDefault();
		$.ajax( {
			url: settings.ajaxurl,
			type: 'post',
			data: {
				action: 'widgetkit_save_admin_addons_settings',
				fields: $( 'form#widgetkit-settings' ).serialize(),
			},
            success: function( response ) {
				swal(
				  'Settings Saved!',
				  'Click OK to continue',
				  'success'
				);
			},
			error: function() {
				swal(
				  'Oops...',
				  'Something Wrong!',
				);
			}
		} );

	} );



    
} )(jQuery);


// function url_content(url){
//     return jQuery.get(url);
// }


// url_content("http://child.demo").success(function(data){ 
//   console.log(jQuery(data).find('.elementor-image-box-img img')[0]);
// });

// function url_content(url){
//     return jQuery.get(url);
// }


// url_content("http://child.demo").success(function(data){ 
//   jQuery('#wpfooter').append(jQuery(data).find('.elementor-image-box-img img')[0]);
// });
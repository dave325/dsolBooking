( function ( $ ) {
    
    var $noticeWrap = $( ".pa-notice-wrap" ),
        notice      = $noticeWrap.data('notice');
    
    if( undefined !== notice ) {
        
        $noticeWrap.find('.pa-notice-reset').on( "click", function() {
            
            $noticeWrap.css('display', 'none');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action:  'pa-dismiss-admin-notice',
                    notice:  $noticeWrap.data( 'notice' )
                }
            });
    
    
        } );
    }
    
    
} )(jQuery);
(function($) {
	"use strict";
	jQuery(document).ready(function($) {
		jQuery('#import').click(function(e){
			var $_this = $(this);
			$_this.addClass('importing');
			var $import_true = confirm('Are you sure to import dummy content ? It will overwrite the existing data and make sure you was installed plugins required and recommend of theme!');
			if($import_true == false) return false;
			jQuery('.import-message').html('<span class="loading"></span> Please Waiting');
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					'action': 'sample'
				},
				success: function(data, textStatus, XMLHttpRequest){
					console.log(data);
					jQuery('.import-message').html('<span class="completed"></span> Import is Completed.<br>Please reload page before change theme options.');
					$_this.removeClass('importing').addClass('completed');
				}
			});
		});
	});
})(jQuery);


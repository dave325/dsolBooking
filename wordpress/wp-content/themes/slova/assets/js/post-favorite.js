(function($) { "use strict";
jQuery(document).ready(function($){
	$('body').on('click','.ro-nectar-love', function() {
			var $loveLink = $(this);
			var $icon = $(this).find('i');
			var $id = $(this).attr('id');
			var $that = $(this);

			if($loveLink.hasClass('loved')) return false;
			if($(this).hasClass('inactive')) return false;

			var $dataToPass = {
				action: 'nectar-love',
				loves_id: $id
			}

			$.post(nectarLove.ajaxurl,$dataToPass, function(data){
				$loveLink.find('span.ro-count').html(data);
				$icon.removeClass('fa-heart-o').addClass('fa-heart');
				//$loveLink.find('span.ro-count').css({'opacity': 1,'width':'auto'});
			});

			$(this).addClass('inactive');

			return false;
	});
});
})(jQuery);

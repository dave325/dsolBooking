	(function($){
		$(document).ready(function(){
			$(".uniqueclass .notice-dismiss").on("click", function(){
				var url = new URL(location.href);
				url.searchParams.append("dismissed", 1);
				location.href = url;

			});

		});

	})(jQuery);
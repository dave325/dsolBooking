jQuery(document).ready(function ($) {
	if ($('.set_custom_images').length > 0) {
		if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
			$('.wrap').on('click', '.set_custom_images', function (e) {
				e.preventDefault();
				wp.media.editor.open(input_text);
				var input_text = $('#' + (this.id).substring(7));
				wp.media.editor.send.attachment = function (props, attachment) {
					input_text.val(attachment.url);
				};
				return false;
			});
		}
	}
	jQuery('.menu_icon_wrap').each(function(){
		var _this = $(this);
		var $item_id = _this.attr('data-item_id');
		jQuery("li",_this).click(function() {
			jQuery(this).attr("class","selected").siblings().removeAttr("class");
			var icon = jQuery(this).attr("data-icon");
			jQuery("#edit-menu-item-menu_icon-"+ $item_id ).val(icon);
			jQuery(".icon-preview-"+ $item_id).html("<i class=\'icon fa "+icon+"\'></i>");
		});
	})
	jQuery('.btn_clear').click(function(){
		$(this).parent().find('.menu-item-bg_image,.menu-item-menu_icon').val('');
		$(this).parent().find('.icon-preview').html('');
	})
});

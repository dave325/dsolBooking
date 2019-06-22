jQuery(document).ready(function($) {
	"use strict";
	$('#post-formats-select input').change(checkformat);
	$('.wp-post-format-ui .post-format-options > a').click(checkformat);
	videoType();
	audioType();
	checkformat();
	quoteType();

	$("#tb_post_quote_type").change(function() {
		quoteType();
	});

	$("#tb_post_video_source").change(function() {
		videoType();
	});

	$("#tb_post_audio_type").change(function() {
		audioType();
	});

	function checkformat() {
		"use strict";
		var formats = ["gallery","link","image","quote","video","audio","chat"];
		var format = $('#post-formats-select input:checked').attr('value');
		var i = 0;
		for(i = 0; i < formats.length; i++){
			if(formats[i] == format){
				$("#tb_post_"+format+"").css('display', 'block');
			} else {
				$("#tb_post_"+formats[i]+"").css('display', 'none');
			}
		}
	}

	function quoteType() {
		"use strict";
		switch ($("#tb_post_quote_type").val()) {
		case 'custom':
			$("#post_quote_custom").css('display', 'block');
			break;
		default:
			$("#post_quote_custom").css('display', 'none');
			break;
		}
	}

	function audioType() {
		"use strict";
		switch ($("#tb_post_audio_type").val()) {
		case '':
			$("#tb_metabox_field_post_audio_url").css('display', 'none');
			break;
		case 'content':
			$("#tb_metabox_field_post_audio_url").css('display', 'none');
			break;
		default:
			$("#tb_metabox_field_post_audio_url").css('display', 'block');
			break;
		}
	}
	function videoType() {
		"use strict";
		switch ($("#tb_post_video_source").val()) {
		case '':
			$("#tb_video_setting").css('display', 'none');
			break;
		case 'post':
			$("#tb_video_setting").css('display', 'none');
			break;
		case 'media':
			$("#tb_metabox_field_post_video_type").css('display', 'block');
			$("#tb_metabox_field_post_video_url").css('display', 'block');
			$("#tb_metabox_field_post_preview_image").css('display', 'block');
			$("#tb_metabox_field_post_video_youtube").css('display', 'none');
			$("#tb_metabox_field_post_video_vimeo").css('display', 'none');
			$("#tb_video_setting").css('display', 'block');
			break;
		case 'youtube':
			$("#tb_metabox_field_post_video_type").css('display', 'none');
			$("#tb_metabox_field_post_video_url").css('display', 'none');
			$("#tb_metabox_field_post_preview_image").css('display', 'none');
			$("#tb_metabox_field_post_video_youtube").css('display', 'block');
			$("#tb_metabox_field_post_video_vimeo").css('display', 'none');
			$("#tb_video_setting").css('display', 'block');
			break;
		case 'vimeo':
			$("#tb_metabox_field_post_video_type").css('display', 'none');
			$("#tb_metabox_field_post_video_url").css('display', 'none');
			$("#tb_metabox_field_post_preview_image").css('display', 'none');
			$("#tb_metabox_field_post_video_youtube").css('display', 'none');
			$("#tb_metabox_field_post_video_vimeo").css('display', 'block');
			$("#tb_video_setting").css('display', 'block');
			break;
		}
	}
});
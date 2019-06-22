(function($) {
	"use strict";
	jQuery.noConflict();
	var tb_json_sidebars = new Object();
	var sidebars_left_1 = new Array();
	var sidebars_left_2 = new Array();
	var sidebars_right_1 = new Array();
	var sidebars_right_2 = new Array();
	jQuery(document).ready(function($) {
		"use strict";
		var blog_tab = $('#tb-tab-blog');
		if(blog_tab != undefined){
			blog_tab.easytabs();
		}
		$("#tb-blog-loading").css("display","none");
		$(".tb_metabox").css("display","block");

		$('#tb_header_fixed_menu_color').colpick({
			layout:'rgbhex',
			colorScheme:'dark',
			submit:0,
			onChange:function(hsb,hex,rgb,el,bySetColor) {
				$(el).css('border-color','#'+hex);
				$(el).val('#'+hex);
			}
		})
		$('#tb_header_fixed_menu_color_hover').colpick({
			layout:'rgbhex',
			colorScheme:'dark',
			submit:0,
			onChange:function(hsb,hex,rgb,el,bySetColor) {
				$(el).css('border-color','#'+hex);
				$(el).val('#'+hex);
			}
		})
		$('#tb_bg_color').colpick({
			layout:'rgbhex',
			colorScheme:'dark',
			submit:0,
			onChange:function(hsb,hex,rgb,el,bySetColor) {
				$(el).css('border-color','#'+hex);
				$(el).val('#'+hex);
			}
		})
		$('#tb_header_bg_color').colpick({
			layout:'rgbhex',
			colorScheme:'dark',
			submit:0,
			onChange:function(hsb,hex,rgb,el,bySetColor) {
				$(el).css('border-color','#'+hex);
				$(el).val('#'+hex);
			}
		})
		$('#tb_page_title_background_color').colpick({
			layout:'rgbhex',
			colorScheme:'dark',
			submit:0,
			onChange:function(hsb,hex,rgb,el,bySetColor) {
				$(el).css('border-color','#'+hex);
				$(el).val('#'+hex);
			}
		})
		$('#tb_footer_top_bg_color').colpick({
			layout:'rgbhex',
			colorScheme:'dark',
			submit:0,
			onChange:function(hsb,hex,rgb,el,bySetColor) {
				$(el).css('border-color','#'+hex);
				$(el).val('#'+hex);
			}
		})

		background_image_changer();
		page_title_setting();
		breadcrumb_setting();
		header_fixed_setting();
		header_setting();

		$("#tb_header_setting").change(function() {
			header_setting();
		});

		$("#tb_header_fixed_top").change(function() {
			header_fixed_setting();
		});

		$("#tb_page_title_bg").change(function() {
			background_image_changer();
		});

		$("#tb_page_title").change(function() {
			page_title_setting();
		});

		$("#tb_breadcrumb").change(function() {
			breadcrumb_setting();
		});

		$( "ul.droptrue" ).sortable({
			  connectWith: "ul",
			  revert: true,
			  stop: function( event, ui ) {
						sidebars_left_1 = [];
						sidebars_left_2 = [];
						sidebars_right_1 = [];
						sidebars_right_2 = [];
						get_sidebars();
						tb_json_sidebars.left1 = sidebars_left_1;
						tb_json_sidebars.left2 = sidebars_left_2;
						tb_json_sidebars.right1 = sidebars_right_1;
						tb_json_sidebars.right2 = sidebars_right_2;
						$('#tb_blog_slidebars').val(encodeURI(JSON.stringify(tb_json_sidebars)));
						generetor_layout();
				  }
			});
		// Show or hiden Header setting.
		function header_setting() {
			"use strict";
			if($("#tb_header_setting").val() == ""){
				$("#header_setting").css("display","none");
			} else {
				$("#header_setting").css("display","block");
			}
		}
		// Show or hiden Header Fixed setting.
		function header_fixed_setting() {
			"use strict";
			if($("#tb_header_fixed_top").val() == "0"){
				$("#header_fixed_color").css("display","none");
			} else {
				$("#header_fixed_color").css("display","block");
			}
		}
		// Show or hiden page title setting.
		function page_title_setting() {
			"use strict";
			if($("#tb_page_title").val() == "custom"){
				$("#page_title").css("display","block");
			} else {
				$("#page_title").css("display","none");
			}
		}
		// Show or hiden breadcrumb setting.
		function breadcrumb_setting() {
			"use strict";
			if($("#tb_breadcrumb").val() == "custom"){
				$("#custom_breadcrumb").css("display","block");
			} else {
				$("#custom_breadcrumb").css("display","none");
			}
		}
		// Show or hiden background setting.
		function background_image_changer() {
			"use strict";
			if($("#tb_page_title_bg").val() != ""){
				$("#page_title_bg").css("display","block");
			} else {
				$("#page_title_bg").css("display","none");
			}
		}
		function get_sidebars() {
			"use strict";
			$('#sortable-left-1 input').each(function() {
				sidebars_left_1.push($(this).val());
			});
			$('#sortable-left-2 input').each(function() {
				sidebars_left_2.push($(this).val());
			});
			$('#sortable-right-1 input').each(function() {
				sidebars_right_1.push($(this).val());
			});
			$('#sortable-right-2 input').each(function() {
				sidebars_right_2.push($(this).val());
			});
		}
		function generetor_layout() {
			"use strict";
			switch (true) {
			//Left 1 Rigth 0
			case (check_left() == 1 && check_right() == 0):
				if(sidebars_left_1.length > 0){
					$('#tb_slidebars_left1').val("col-xs-12 col-sm-3 col-md-3 col-lg-3");
					$('#tb_slidebars_left2').val("");
				} else {
					$('#tb_slidebars_left1').val("");
					$('#tb_slidebars_left2').val("col-xs-12 col-sm-3 col-md-3 col-lg-3");
				}
				$('#tb_slidebars_blog').val("col-xs-12 col-sm-9 col-md-9 col-lg-9");
					$('#tb_slidebars_rigth1').val("");
					$('#tb_slidebars_rigth2').val("");
				break;
			//Left 0 Rigth 1
			case (check_left() == 0 && check_right() == 1):
				$('#tb_slidebars_left1').val("");
				$('#tb_slidebars_left2').val("");
				$('#tb_slidebars_blog').val("col-xs-12 col-sm-9 col-md-9 col-lg-9");
				if(sidebars_right_1.length > 0){
					$('#tb_slidebars_rigth1').val("col-xs-12 col-sm-3 col-md-3 col-lg-3");
					$('#tb_slidebars_rigth2').val("");
				} else {
					$('#tb_slidebars_rigth1').val("");
					$('#tb_slidebars_rigth2').val("col-xs-12 col-sm-3 col-md-3 col-lg-3");
				}
				break;
			//Left 1 Rigth 1
			case (check_left() == 1 && check_right() == 1):
				if(sidebars_left_1.length > 0){
					$('#tb_slidebars_left1').val("col-xs-12 col-sm-3 col-md-3 col-lg-3");
					$('#tb_slidebars_left2').val("");
				} else {
					$('#tb_slidebars_left1').val("");
					$('#tb_slidebars_left2').val("col-xs-12 col-sm-3 col-md-3 col-lg-3");
				}
				$('#tb_slidebars_blog').val("col-xs-12 col-sm-6 col-md-6 col-lg-6");
				if(sidebars_right_1.length > 0){
					$('#tb_slidebars_rigth1').val("col-xs-12 col-sm-3 col-md-3 col-lg-3");
					$('#tb_slidebars_rigth2').val("");
				} else {
					$('#tb_slidebars_rigth1').val("");
					$('#tb_slidebars_rigth2').val("col-xs-12 col-sm-3 col-md-3 col-lg-3");
				}
				break;
			//Left 0 Rigth 2
			case (check_left() == 0 && check_right() == 2):
				$('#tb_slidebars_left1').val("");
				$('#tb_slidebars_left2').val("");
				$('#tb_slidebars_blog').val("col-xs-12 col-sm-6 col-md-6 col-lg-6");
				$('#tb_slidebars_rigth1').val("col-xs-12 col-sm-3 col-md-3 col-lg-3");
				$('#tb_slidebars_rigth2').val("col-xs-12 col-sm-3 col-md-3 col-lg-3");
				break;
			//Left 2 Rigth 0
			case (check_left() == 2 && check_right() == 0):
				$('#tb_slidebars_left1').val("col-xs-12 col-sm-3 col-md-3 col-lg-3");
				$('#tb_slidebars_left2').val("col-xs-12 col-sm-3 col-md-3 col-lg-3");
				$('#tb_slidebars_blog').val("col-xs-12 col-sm-6 col-md-6 col-lg-6");
				$('#tb_slidebars_rigth1').val("");
				$('#tb_slidebars_rigth2').val("");
				break;
			//Left 1 Rigth 2
			case (check_left() == 1 && check_right() == 2):
				alert("Not Support 4 Column");
				if(sidebars_left_1.length > 0){
					$('#tb_slidebars_left1').val("");
					$('#tb_slidebars_left2').val("");
				} else {
					$('#tb_slidebars_left1').val("");
					$('#tb_slidebars_left2').val("");
				}
				$('#tb_slidebars_blog').val("col-md-12");
				$('#tb_slidebars_rigth1').val("");
				$('#tb_slidebars_rigth2').val("");
				break;
			//Left 2 Rigth 1
			case (check_left() == 2 && check_right() == 1):
				alert("Not Support 4 Column");
				$('#tb_slidebars_left1').val("");
				$('#tb_slidebars_left2').val("");
				$('#tb_slidebars_blog').val("col-md-12");
				if(sidebars_right_1.length > 0){
					$('#tb_slidebars_rigth1').val("");
					$('#tb_slidebars_rigth2').val("");
				} else {
					$('#tb_slidebars_rigth1').val("");
					$('#tb_slidebars_rigth2').val("");
				}
				break;
			//Left 2 Rigth 2
			case (check_left() == 2 && check_right() == 2):
				alert("Not Support 5 Column");
				$('#tb_slidebars_left1').val("");
				$('#tb_slidebars_left2').val("");
				$('#tb_slidebars_blog').val("col-md-12");
				$('#tb_slidebars_rigth1').val("");
				$('#tb_slidebars_rigth2').val("");
				break;
			//Left 0 Rigth 0
			default:
				$('#tb_slidebars_left1').val("");
				$('#tb_slidebars_left2').val("");
				$('#tb_slidebars_blog').val("col-md-12");
				$('#tb_slidebars_rigth1').val("");
				$('#tb_slidebars_rigth2').val("");
				break;
			}
		}
		function check_left() {
			"use strict";
			if(sidebars_left_1.length > 0 && sidebars_left_2.length > 0){
				return 2;
				} else if(sidebars_left_1.length > 0 || sidebars_left_2.length > 0){
					return 1;
				} else {
					return 0;
				}
		}
		function check_right() {
			"use strict";
			if(sidebars_right_1.length > 0 && sidebars_right_2.length > 0){
				return 2;
				} else if(sidebars_right_1.length > 0 || sidebars_right_2.length > 0){
					return 1;
				} else {
					return 0;
				}
		}
	});
})(jQuery);
!(function(j) {
	"use strict";
	function menubar(elem) {
		this.thisEl = j(elem);
		this.init();
	}

	menubar.prototype = {
		init: function() {
			var menubar = this,
				logoImg = menubar.thisEl.find('.menubar-brand > img'),
				ulMenu = menubar.thisEl.find('.menu-list.menu-tb > ul'),
				btn_menubar = j('.btn-menubar'),
				menu_list = menubar.thisEl.find('.menu-list.menu-tb');

			menubar.info = {
				toggleClass: menubar.thisEl.data('scroll-toggle-class').split(' '),
				classFixedTop: 'menubar-fixed-top',
				top: menubar.thisEl.offset().top,
				height: menubar.thisEl.innerHeight(),
				windowW: j(window).width()
			}
			if (j.inArray(menubar.info.classFixedTop, menubar.info.toggleClass) != -1) {
				menubar.contentEl = j('<div>').addClass('content-menu-bar');
				menubar.thisEl.before(this.contentEl);
			}

			j(window).resize(function() {
				// update width browser
				menubar.thisEl.find('ul').css('left', '');
				menubar.info.windowW = j(window).width();                
				if(menu_list.hasClass('active')) return;
				//btn_menubar.css({height: menubar.thisEl.height() - 10, opacity: 1});
			}).trigger('resize');

			menubar.thisEl.find('.menu-list.menu-tb ul ul').each(function() {
				var thisEl = j(this);
				if (thisEl.parent().hasClass('menu-item-has-children')) {
					thisEl.addClass('child');
					menubar.thisEl.find('.menu-list.menu-tb > ul > li').children('ul').removeClass('child');
				}
			})

			// call handle hover
			menubar.handleHover();

			// call handle mobi
			menubar.handleMobi();
			
			// call megamenu builder
			menubar.megamenu();

			if (menubar.info.toggleClass.length <= 0)
				return;
			
			// check use header magazine
			if( j('#tb-header-magazine-js').length > 0 ){
				menubar.headerMagazine();
				return;
			}
			
			// check use header transparent
			if( j('.tb-header-transparent').length > 0 ){
				menubar.headerTransparent(); 
				return;
			}
			
			// call handle scroll
			menubar.handleScroll();
		},
		headerTransparent: function(){
			var menubar = this,
				elTop = (menubar.thisEl.hasClass(menubar.info.classFixedTop) || menubar.info.top == 0) ? 1 : 20 + (menubar.thisEl.height() / 2),
				scrollTop = 0,
				tb_header_transparent = j('.tb-header-transparent'),
				state = true;
				
			j(window).scroll(function() {
				scrollTop = j(this).scrollTop();
				if (scrollTop >= elTop) {
					if (state == true) {
						j.each(menubar.info.toggleClass, function($k, $v) {
							(menubar.thisEl.hasClass($v)) ? menubar.thisEl.removeClass($v) : menubar.thisEl.addClass($v);
						})
						tb_header_transparent.removeClass('header-transparent-style');
					}	
					state = false;
				}else{
					if (state == false) {
						j.each(menubar.info.toggleClass, function($k, $v) {
							(menubar.thisEl.hasClass($v)) ? menubar.thisEl.removeClass($v) : menubar.thisEl.addClass($v);
						})
						tb_header_transparent.addClass('header-transparent-style');
					}
					state = true;
				}
			}).trigger('scroll')
		},
		headerMagazine: function(){
			var menubar = this,
				parrams = {};
			
			parrams.window = j(window);
			parrams.body = j('body');
			parrams.amazineEl = j('#tb-header-magazine-js');
			parrams.nice = '';
			
			parrams.nice = parrams.body.niceScroll();
			parrams.nice.scrollstart(function(info){
				if( info.end.y != 0 ){
					parrams.amazineEl.addClass('header-up').removeClass('full-screen'); 
					menubar.thisEl.removeClass('menu-toggle-class');
				}
			})
			parrams.nice.scrollend(function(info){
				if( info.end.y == 0 ){
					parrams.amazineEl.removeClass('header-up').addClass('full-screen');
					menubar.thisEl.addClass('menu-toggle-class');
				}
			})
		},
		handleScroll: function() {
			var menubar = this,
					elTop = (menubar.thisEl.hasClass(menubar.info.classFixedTop) || menubar.info.top == 0) ? 1 : menubar.info.top + (menubar.thisEl.height() / 2),
					scrollTop = 0,
					state = true;
			j(window).scroll(function() {
				scrollTop = j(this).scrollTop();
				if (scrollTop >= elTop) {
					if (state == true) {
						j.each(menubar.info.toggleClass, function($k, $v) {
							(menubar.thisEl.hasClass($v)) ? menubar.thisEl.removeClass($v) : menubar.thisEl.addClass($v);
						})
					}
					if (menubar.contentEl)
						menubar.contentEl.css({'height': menubar.thisEl.css('height')});
					state = false;
				} else {
					if (state == false) {
						j.each(menubar.info.toggleClass, function($k, $v) {
							(menubar.thisEl.hasClass($v)) ? menubar.thisEl.removeClass($v) : menubar.thisEl.addClass($v);
						})
					}
					if (menubar.contentEl)
						menubar.contentEl.css({'height': 0});
					state = true;
				}

			}).trigger('scroll')
		},
		handleHover: function() {
			var menubar = this;
			menubar.thisEl.find('li.menu-item-has-children').on({
				mouseover: function() {
					var thisEl = j(this),
					childUl = thisEl.children('ul'),
					params = {
						left: childUl.offset().left,
						width: childUl.innerWidth()
					}
					
					if(thisEl.hasClass('mega-menu-item')){ return; }
					
					if ((params.left + params.width) > menubar.info.windowW && childUl.hasClass('child')) {
						childUl.css({left: (params.width) * -1,right: 'auto'});
					}else if((params.left + params.width) > menubar.info.windowW ){
						
						childUl.css({right: 0,left: 'auto'})
					}
				}
			})
		},
		handleMobi: function() {
			var menubar = this,
					btn_menubar = j('.btn-menubar'),
					menu_list = menubar.thisEl.find('.menu-list.menu-tb');

			menubar.thisEl.find('li.menu-item-has-children').each(function() {
				var btnMobiSub = j('<button>').addClass('btn-mobi-sub');
				j(this).append(btnMobiSub);
				btnMobiSub.bind('click', function() {
					j(this).toggleClass('active');
					j(this).parent().children('ul').toggleClass('active');
					j(this).parent().toggleClass('active-sub');
				})
			})

			btn_menubar.click(function() {
				if (!menu_list.hasClass('menu-list-mobi'))
					menu_list.addClass('menu-list-mobi')

				menu_list.toggleClass('active');
			})

		},
		megamenu: function(){
			// .mega-menu-item
			var menubar = this,
				megaEl = menubar.thisEl.find('li.mega-menu-item'),
				params = { window_w: 0, mega_t: 0, mega_l: 0 }
				var left = 0,
				width = 1170,
				resize = 1;
			if( megaEl.length < 1 ){ return; }
			j(window).resize(function(){
				params.window_w = j(this).width();
				megaEl.each(function(){
					var thisMegaEl = j(this);
					var contentMega = thisMegaEl.children('ul'),
						itemMega = contentMega.children('li');
					
					thisMegaEl.children('ul').children('li').each(function(){
						//if(j(this).children('a').length <= 0){
						if( j(this).children('a').hasClass('hide_link') ){
							j(this).addClass('peer');
							j(this).children('ul.sub-menu').addClass('active');
							j(this).children('.btn-mobi-sub').remove();
						}
					})
					
					var	p_mega = thisMegaEl.offset();
						params.mega_t = p_mega.top; 
						params.mega_l = p_mega.left;
					
					var fullWidth = (params.window_w >= 1170)? 1170 : params.window_w;
					var maxWidth = fullWidth - 60, // padding 30px l/r
						widthEx = (params.window_w - fullWidth) / 2, // width excess l/r
						itemWidth = maxWidth / 4; // 4 item
					
					// itemMega.css({ minWidth: itemWidth, maxWidth: itemWidth }); // use class css columns2,3,4
					
					var countItem = itemMega.length; // count item
					if( contentMega.hasClass('columns2') ) countItem = 2;
					else if( contentMega.hasClass('columns3') ) countItem = 3;
					else if( contentMega.hasClass('columns4') ) countItem = 4;
					var widthContent = (countItem <= 4)? (itemWidth * countItem) + 60 : (itemWidth * 4) + 60; // +60 because line 144 -60
				
					// set Width content MEGAMENU
					width = widthContent;
					
					if( (params.mega_l + contentMega.innerWidth()) >= (fullWidth + widthEx) ){
						left = ((params.mega_l + (itemWidth * countItem) + 60) - (fullWidth + widthEx)) * -1;
						contentMega.css({ left: left });
					}
					
					contentMega.css({ width: width })
				})
				setTimeout(function(){
					resize++;
					if(resize >= 3){ return; }
					else{ j(window).trigger('resize'); }
				}, 1)
				if( resize >= 3 ){ resize = 1; }
			})
			
			j(window).trigger('resize');
		}
	}

	// Document ready
	j(function() {
		var menubarEl = j('.menubar');
		menubarEl.each(function() {
			new menubar(this);
		})
	})
})(jQuery);
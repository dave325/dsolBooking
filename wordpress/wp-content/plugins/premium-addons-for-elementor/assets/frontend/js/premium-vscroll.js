// Works on mobile but needs enhancements

( function( $ ) {
    
  /****** Premium Vertical Scroll Handler ******/
  var PremiumVerticalScrollHandler = function($scope, $) {
      
    var vScrollElem     = $scope.find( ".premium-vscroll-wrap" ),
        instance        = null,
        vScrollSettings = vScrollElem.data( "settings" );

//    var touch = vScrollSettings.touch;

        instance = new premiumVerticalScroll( vScrollElem, vScrollSettings );
        instance.init();

//        var isTouchDevice = navigator.userAgent.match(/(iPhone|iPod|iPad|Android|playbook|silk|BlackBerry|BB10|Windows Phone|Tizen|Bada|webOS|IEMobile|Opera Mini)/);
//      var isTouch = (('ontouchstart' in window) || (navigator.msMaxTouchPoints > 0) || (navigator.maxTouchPoints));
//
//
//        if( touch ) {
//            instance = new premiumVerticalScroll2( vScrollElem, vScrollSettings );
//            instance.init();
//        } else {
//            if ( isTouchDevice || isTouch ) {
//                instance = new premiumVerticalScroll( vScrollElem, vScrollSettings );
//                instance.init();
//            } else {
//                instance = new premiumVerticalScroll2( vScrollElem, vScrollSettings );
//                instance.init();
//            }
//        }
        

  };
  
  
  window.premiumVerticalScroll2 = function( $selector, settings ) {
      
    var self            = this,
        $instance       = $selector,
        $window         = $( window ),
        $htmlBody       = $("html, body"),
        checkTemps      = $selector.find( ".premium-vscroll-sections-wrap" ).length,
        deviceType      = $("body").data("elementor-device-mode"),
        $itemsList      = $(".premium-vscroll-dot-item", $instance),
        $menuItems      = $(".premium-vscroll-nav-item", $instance),
        animated        = 0;
        
    
    var $lastItem       = $itemsList.last(),
        lastSectionId   = $lastItem.data("menuanchor"),
        lastOffset      = Math.round( $( "#" + lastSectionId ).offset().top );
    
    self.init = function() {
        
        self.setSectionsData();
        
        $itemsList.on("click.premiumVerticalScroll", self.onNavDotChange);
        $menuItems.on("click.premiumVerticalScroll", self.onNavDotChange);

        $itemsList.on( "mouseenter.premiumVerticalScroll", self.onNavDotEnter );

        $itemsList.on( "mouseleave.premiumVerticalScroll", self.onNavDotLeave );
        
        $.scrollify({
            section:                ".premium-vscroll-section",
            updateHash:             false,
            standardScrollElements: "#" + lastSectionId,
            scrollSpeed:            settings.speed,
            overflowScroll:         settings.overflow,
            setHeights:             settings.setHeight,
            before: function( index ) {
                
                $menuItems.removeClass("active");
                $itemsList.removeClass("active");

                $( $itemsList[ index ] ).addClass( "active" );
                $( $menuItems[ index ] ).addClass( "active" );
                
            },
            after: function( index ) {
                
                if ( index === $lastItem.index() ) {
//                    $.scrollify.disable();
                }
                
            },
            afterRender: function() {
                
                $( $itemsList[ 0 ] ).addClass( "active" );
                $( $menuItems[ 0 ] ).addClass( "active" );
                
            }
        });
        
        if ( deviceType === "desktop" ) {
            
            $window.on( "scroll.premiumVerticalScroll2", self.onWheel );
            
        }
        
        if ( settings.fullSection ) {
            
            var vSection = document.getElementById( $instance.attr("id") );
            
            if ( checkTemps ) {
                
                document.addEventListener
                ? vSection.addEventListener("wheel", self.onWheel, !1)
                : vSection.attachEvent("onmousewheel", self.onWheel);
                
            } else {
                
                document.addEventListener
                ? document.addEventListener("wheel", self.onWheel, !1)
                : document.attachEvent("onmousewheel", self.onWheel);
                
            }
        }
    
    };
    
    self.onWheel = function( event ) {
      
        var $target         = $( event.target ),
            sectionSelector = checkTemps ? ".premium-vscroll-temp" : ".elementor-top-section",
            $section        = $target.closest( sectionSelector ),
            sectionId       = $section.attr( "id" ),
            $currentSection  = $.scrollify.current();
        
        //re-enable Scrollify
        if ( sectionId !== lastSectionId && $section.hasClass("premium-vscroll-section") && $.scrollify.isDisabled() ) {
            
            $(".premium-vscroll-dots, .premium-vscroll-nav-menu").removeClass(
                "premium-vscroll-dots-hide"
            );
            
            $.scrollify.enable();
            
        } 
        
        if ( ! $section.hasClass("premium-vscroll-section") && $.scrollify.isDisabled() ) {
            
            $(".premium-vscroll-tooltip").hide();
            
            $(".premium-vscroll-dots, .premium-vscroll-nav-menu").addClass(
                "premium-vscroll-dots-hide"
            );
            
        } 
        
        
        
    };
    
    self.moveSectionDown = function() {
        $.scrollify.next();
    }
    
    self.moveSectionUp = function() {
        $.scrollify.previous();
    }
    
    self.moveToSection = function( index ) {
        
        $.scrollify.move( index );
    }
    
    self.setSectionsData = function() {
        
      $itemsList.each( function() {
          
        var $this       = $( this ),
            sectionId   = $this.data( "menuanchor" ),
            $section    = $( "#" + sectionId );
          
        $section.addClass( "premium-vscroll-section" );
        
      });
      
    };
    
    self.onNavDotChange = function( event ) {
        
        var $this       = $( this ),
            index       = $this.index(),
            sectionId   = $this.data("menuanchor");

//      if ( ! isScrolling ) {
          
        if ( $.scrollify.isDisabled() ) {
            
            $.scrollify.enable();
            
        }

        $menuItems.removeClass("active");
        $itemsList.removeClass("active");

        if ( $this.hasClass( "premium-vscroll-nav-item") ) {
            
          $( $itemsList[ index ] ).addClass( "active" );
          
        } else {
            
          $( $menuItems[ index ] ).addClass( "active" );
        }

        $this.addClass( "active" );
        
        self.moveToSection( index );
        
//      }
    };
    
    self.onNavDotEnter = function() {
        
        var $this = $( this ),
            index = $this.data("index");
    
      if ( settings.tooltips ) {
          
        $('<div class="premium-vscroll-tooltip"><span>' + settings.dotsText[index] + "</span></div>" ).hide().appendTo( $this ).fadeIn( 200 );
      }
      
    };

    self.onNavDotLeave = function() {
        
      $( ".premium-vscroll-tooltip" ).fadeOut( 200, function() {
          
        $( this ).remove();
        
      });
      
    };

    
      
  };

  window.premiumVerticalScroll = function($selector, settings) {
    var self = this,
      $window = $(window),
      $instance = $selector,
      checkTemps = $selector.find(".premium-vscroll-sections-wrap").length,
      $htmlBody = $("html, body"),
      deviceType = $("body").data("elementor-device-mode"),
      $itemsList = $(".premium-vscroll-dot-item", $instance),
      $menuItems = $(".premium-vscroll-nav-item", $instance),
      defaultSettings = {
        speed: 700,
        offset: 1,
        fullSection: true
      },
      settings = $.extend({}, defaultSettings, settings),
      sections = {},
      timeStamp = 0,
      currentSection = null,
      platform = navigator.platform,
      isScrolling = false;

    jQuery.extend(jQuery.easing, {
      easeInOutCirc: function(x, t, b, c, d) {
        if ((t /= d / 2) < 1) return (-c / 2) * (Math.sqrt(1 - t * t) - 1) + b;
        return (c / 2) * (Math.sqrt(1 - (t -= 2) * t) + 1) + b;
      }
    });

    self.checkNextSection = function(object, key) {
      var keys = Object.keys(object),
        idIndex = keys.indexOf(key),
        nextIndex = (idIndex += 1);

      if (nextIndex >= keys.length) {
        return false;
      }

      var nextKey = keys[nextIndex];

      return nextKey;
    };

    self.checkPrevSection = function(object, key) {
      var keys = Object.keys(object),
        idIndex = keys.indexOf(key),
        prevIndex = (idIndex -= 1);

      if (0 > idIndex) {
        return false;
      }

      var prevKey = keys[prevIndex];

      return prevKey;
    };

    self.debounce = function(threshold, callback) {
      var timeout;

      return function debounced($event) {
        function delayed() {
          callback.call(this, $event);
          timeout = null;
        }

        if (timeout) {
          clearTimeout(timeout);
        }

        timeout = setTimeout(delayed, threshold);
      };
    };
    self.visible = function(selector, partial, hidden){
        
        var s = selector.get(0),
            vpHeight = $window.outerHeight(),
            clientSize = hidden === true ? s.offsetWidth * s.offsetHeight : true;
        if (typeof s.getBoundingClientRect === 'function') {
            var rec = s.getBoundingClientRect();
            var tViz = rec.top >= 0 && rec.top < vpHeight,
                bViz = rec.bottom > 0 && rec.bottom <= vpHeight,
                vVisible = partial ? tViz || bViz : tViz && bViz,
                vVisible = (rec.top < 0 && rec.bottom > vpHeight) ? true : vVisible;
            return clientSize && vVisible;
        } else {
            var viewTop = 0,
                viewBottom = viewTop + vpHeight,
                position = $window.position(),
                _top = position.top,
                _bottom = _top + $window.height(),
                compareTop = partial === true ? _bottom : _top,
                compareBottom = partial === true ? _top : _bottom;
            return !!clientSize && ((compareBottom <= viewBottom) && (compareTop >= viewTop));
        }
        
    };

    self.init = function() {
        
      self.setSectionsData();
      
      $itemsList.on("click.premiumVerticalScroll", self.onNavDotChange);
      $menuItems.on("click.premiumVerticalScroll", self.onNavDotChange);

      $itemsList.on("mouseenter.premiumVerticalScroll", self.onNavDotEnter);

      $itemsList.on("mouseleave.premiumVerticalScroll", self.onNavDotLeave);

      if (deviceType === "desktop") {
        $window.on("scroll.premiumVerticalScroll", self.onWheel);
      }
      
      $window.on(
        "resize.premiumVerticalScroll orientationchange.premiumVerticalScroll",
        self.debounce(50, self.onResize)
      );
      $window.on("load", function() {
        self.setSectionsData();
      });

      self.keyboardHandler();
      
      self.scrollHandler();
      
      for (var section in sections) {
        var $section = sections[section].selector;
        elementorFrontend.waypoint(
          $section,
          function(direction) {
            var $this = $(this),
              sectionId = $this.attr("id");
            if ("down" === direction && !isScrolling) {
              currentSection = sectionId;
              $itemsList.removeClass("active");
              $menuItems.removeClass("active");
              $("[data-menuanchor=" + sectionId + "]", $instance).addClass(
                "active"
              );
            }
          },
          {
            offset: "95%",
            triggerOnce: false
          }
        );

        elementorFrontend.waypoint(
          $section,
          function(direction) {
            var $this = $(this),
              sectionId = $this.attr("id");
            if ("up" === direction && !isScrolling) {
              currentSection = sectionId;
              $itemsList.removeClass("active");
              $menuItems.removeClass("active");
              $("[data-menuanchor=" + sectionId + "]", $instance).addClass(
                "active"
              );
            }
          },
          {
            offset: "0%",
            triggerOnce: false
          }
        );
      }
    };
    
    self.keyboardHandler = function() {
        
        $(document).keydown(function(event) {
            if (38 == event.keyCode) {
              self.onKeyUp(event, "up");
            }

            if (40 == event.keyCode) {
              self.onKeyUp(event, "down");
            }
        });
        
    };
    
    self.scrollHandler = function() {
        
        if (settings.fullSection) {
            var vSection = document.getElementById($instance.attr("id"));
        
            if (checkTemps) {
              document.addEventListener
                ? vSection.addEventListener("wheel", self.onWheel,{ passive: false })
                : vSection.attachEvent("onmousewheel", self.onWheel);
            } else {
              document.addEventListener
                ? document.addEventListener("wheel", self.onWheel,{ passive: false })
                : document.attachEvent("onmousewheel", self.onWheel);
            }
        }
        
    };

    self.setSectionsData = function() {
      $itemsList.each(function() {
        var $this = $(this),
          sectionId = $this.data("menuanchor"),
          $section = $("#" + sectionId);
        if ($section[0]) {
          sections[sectionId] = {
            selector: $section,
            offset: Math.round($section.offset().top),
            height: $section.outerHeight()
          };
        }
      });
    };

    self.onNavDotEnter = function() {
      var $this = $(this),
        index = $this.data("index");
      if (settings.tooltips) {
        $(
          '<div class="premium-vscroll-tooltip"><span>' +
            settings.dotsText[index] +
            "</span></div>"
        )
          .hide()
          .appendTo($this)
          .fadeIn(200);
      }
    };

    self.onNavDotLeave = function() {
      $(".premium-vscroll-tooltip").fadeOut(200, function() {
        $(this).remove();
      });
    };

    self.onNavDotChange = function(event) {
      var $this = $(this),
        index = $this.index(),
        sectionId = $this.data("menuanchor"),
        offset = null;

      if (!sections.hasOwnProperty(sectionId)) {
        return false;
      }

      offset = sections[sectionId].offset - settings.offset;

      if (!isScrolling) {
        isScrolling = true;

        currentSection = sectionId;
        $menuItems.removeClass("active");
        $itemsList.removeClass("active");

        if ($this.hasClass("premium-vscroll-nav-item")) {
          $($itemsList[index]).addClass("active");
        } else {
          $($menuItems[index]).addClass("active");
        }

        $this.addClass("active");

        $htmlBody
          .stop()
          .clearQueue()
          .animate(
            { scrollTop: offset },
            settings.speed,
            "easeInOutCirc",
            function() {
              isScrolling = false;
            }
          );
      }
    };

    self.onAnchorChange = function( sectionId ) {
        
        var $this = $("[data-menuanchor=" + sectionId + "]", $instance),
        offset = null;

      if ( ! sections.hasOwnProperty( sectionId ) ) {
        return false;
      }

      offset = sections[sectionId].offset - settings.offset;

      if ( ! isScrolling ) {
        isScrolling = true;

        window.history.pushState(null, null, "#" + sectionId);
        currentSection = sectionId;

        $itemsList.removeClass("active");
        $menuItems.removeClass("active");

        $this.addClass( "active" );

        $htmlBody.animate(
          { scrollTop: offset },
          settings.speed,
          "easeInOutCirc",
          function() {
            isScrolling = false;
          }
        );
      }
        
    };

    self.onKeyUp = function(event, direction) {
      var direction = direction || "up",
        sectionId,
        nextItem = $(
          ".premium-vscroll-dot-item[data-menuanchor=" + currentSection + "]",
          $instance
        ).next(),
        prevItem = $(
          ".premium-vscroll-dot-item[data-menuanchor=" + currentSection + "]",
          $instance
        ).prev();

      event.preventDefault();

      if (isScrolling) {
        return false;
      }

      if ("up" === direction) {
        if (prevItem[0]) {
          prevItem.trigger("click.premiumVerticalScroll");
        }
      }

      if ("down" === direction) {
        if (nextItem[0]) {
          nextItem.trigger("click.premiumVerticalScroll");
        }
      }
    };

    self.onScroll = function(event) {
      /* On Scroll Event */
      if (isScrolling) {
        event.preventDefault();
      }
    };

    function getFirstSection(object) {
      return Object.keys(object)[0];
    }

    function getLastSection(object) {
      return Object.keys(object)[Object.keys(object).length - 1];
    }

    function getDirection(e) {
      e = window.event || e;
      var t = Math.max(-1, Math.min(1, e.wheelDelta || -e.deltaY || -e.detail));
      return t;
    }

    self.onWheel = function(event) {
      if (isScrolling) {
        event.preventDefault();
        return false;
      }

      var $target = $(event.target),
        sectionSelector = checkTemps
          ? ".premium-vscroll-temp"
          : ".elementor-top-section",
        $section = $target.closest(sectionSelector),
        $vTarget = self.visible($instance, true, false),
        sectionId = $section.attr("id"),
        offset = 0,
        newSectionId = false,
        prevSectionId = false,
        nextSectionId = false,
        delta = getDirection(event),
        direction = 0 > delta ? "down" : "up",
        windowScrollTop = $window.scrollTop(),
        dotIndex = $(".premium-vscroll-dot-item.active").index();
      if ("mobile" === deviceType || "tablet" === deviceType) {
        $(".premium-vscroll-tooltip").hide();
        if (dotIndex === $itemsList.length - 1 && !$vTarget) {
          $(".premium-vscroll-dots, .premium-vscroll-nav-menu").addClass(
            "premium-vscroll-dots-hide"
          );
        } else if (dotIndex === 0 && !$vTarget) {
          if ($instance.offset().top - $(document).scrollTop() > 200) {
            $(".premium-vscroll-dots, .premium-vscroll-nav-menu").addClass(
              "premium-vscroll-dots-hide"
            );
          }
        } else {
          $(".premium-vscroll-dots, .premium-vscroll-nav-menu").removeClass(
            "premium-vscroll-dots-hide"
          );
        }
      }

      if (beforeCheck()) {
        sectionId = getFirstSection(sections);
      }

      if (afterCheck()) {
        sectionId = getLastSection(sections);
      }
      if (sectionId && sections.hasOwnProperty(sectionId)) {
        prevSectionId = self.checkPrevSection(sections, sectionId);
        nextSectionId = self.checkNextSection(sections, sectionId);
        if ("up" === direction) {
          if (!nextSectionId && sections[sectionId].offset < windowScrollTop) {
            newSectionId = sectionId;
          } else {
            newSectionId = prevSectionId;
          }
        }

        if ("down" === direction) {
          if (
            !prevSectionId &&
            sections[sectionId].offset > windowScrollTop + 5
          ) {
            newSectionId = sectionId;
          } else {
            newSectionId = nextSectionId;
          }
        }

        if (newSectionId) {
          $(".premium-vscroll-dots, .premium-vscroll-nav-menu").removeClass(
            "premium-vscroll-dots-hide"
          );
          event.preventDefault();
          
          if (event.timeStamp - timeStamp > 10 && "MacIntel" == platform) {
            timeStamp = event.timeStamp;

            return false;
          }

          self.onAnchorChange(newSectionId);
          
        } else {
          var $lastselector = checkTemps ? $instance : $("#" + sectionId);
          if ("down" === direction) {
            if (
              $lastselector.offset().top +
                $lastselector.innerHeight() -
                $(document).scrollTop() >
              600
            ) {
              $(".premium-vscroll-dots, .premium-vscroll-nav-menu").addClass(
                "premium-vscroll-dots-hide"
              );
            }
          } else if ("up" === direction) {
            if ($lastselector.offset().top - $(document).scrollTop() > 200) {
              $(".premium-vscroll-dots, .premium-vscroll-nav-menu").addClass(
                "premium-vscroll-dots-hide"
              );
            }
          }
        }
      }
    };

    function beforeCheck(event) {
      var windowScrollTop = $window.scrollTop(),
        firstSectionId = getFirstSection(sections),
        offset = sections[firstSectionId].offset,
        topBorder = windowScrollTop + $window.outerHeight(),
        visible = self.visible($instance, true, false);

      if (topBorder > offset) {
        return false;
      } else if (visible) {
        return true;
      }
      return false;
    }

    function afterCheck(event) {
      var windowScrollTop = $window.scrollTop(),
        lastSectionId = getLastSection(sections),
        offset = sections[lastSectionId].offset,
        bottomBorder =
          sections[lastSectionId].offset + sections[lastSectionId].height,
        visible = self.visible($instance, true, false);

      if (windowScrollTop < bottomBorder) {
        return false;
      } else if (visible) {
        return true;
      }

      return false;
    }

    self.onResize = function(event) {
      self.setSectionsData();
    };

    self.scrollStop = function() {
      $htmlBody.stop(true);
    };
  };

  $(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/premium-vscroll.default",
      PremiumVerticalScrollHandler
    );
  });
})( jQuery );
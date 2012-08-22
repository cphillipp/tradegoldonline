jQuery.noConflict();


jQuery.base64 = (function($) {
  
  var _PADCHAR = "=",
    _ALPHA = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
    _VERSION = "1.0";


  function _getbyte64( s, i ) {
    // This is oddly fast, except on Chrome/V8.
    // Minimal or no improvement in performance by using a
    // object with properties mapping chars to value (eg. 'A': 0)

    var idx = _ALPHA.indexOf( s.charAt( i ) );

    if ( idx === -1 ) {
      throw "Cannot decode base64";
    }

    return idx;
  }
  
  
  function _decode( s ) {
    var pads = 0,
      i,
      b10,
      imax = s.length,
      x = [];

    s = String( s );
    
    if ( imax === 0 ) {
      return s;
    }

    if ( imax % 4 !== 0 ) {
      throw "Cannot decode base64";
    }

    if ( s.charAt( imax - 1 ) === _PADCHAR ) {
      pads = 1;

      if ( s.charAt( imax - 2 ) === _PADCHAR ) {
        pads = 2;
      }

      // either way, we want to ignore this last block
      imax -= 4;
    }

    for ( i = 0; i < imax; i += 4 ) {
      b10 = ( _getbyte64( s, i ) << 18 ) | ( _getbyte64( s, i + 1 ) << 12 ) | ( _getbyte64( s, i + 2 ) << 6 ) | _getbyte64( s, i + 3 );
      x.push( String.fromCharCode( b10 >> 16, ( b10 >> 8 ) & 0xff, b10 & 0xff ) );
    }

    switch ( pads ) {
      case 1:
        b10 = ( _getbyte64( s, i ) << 18 ) | ( _getbyte64( s, i + 1 ) << 12 ) | ( _getbyte64( s, i + 2 ) << 6 );
        x.push( String.fromCharCode( b10 >> 16, ( b10 >> 8 ) & 0xff ) );
        break;

      case 2:
        b10 = ( _getbyte64( s, i ) << 18) | ( _getbyte64( s, i + 1 ) << 12 );
        x.push( String.fromCharCode( b10 >> 16 ) );
        break;
    }

    return x.join( "" );
  }
  
  
  function _getbyte( s, i ) {
    var x = s.charCodeAt( i );

    if ( x > 255 ) {
      throw "INVALID_CHARACTER_ERR: DOM Exception 5";
    }
    
    return x;
  }


  function _encode( s ) {
    if ( arguments.length !== 1 ) {
      throw "SyntaxError: exactly one argument required";
    }

    s = String( s );

    var i,
      b10,
      x = [],
      imax = s.length - s.length % 3;

    if ( s.length === 0 ) {
      return s;
    }

    for ( i = 0; i < imax; i += 3 ) {
      b10 = ( _getbyte( s, i ) << 16 ) | ( _getbyte( s, i + 1 ) << 8 ) | _getbyte( s, i + 2 );
      x.push( _ALPHA.charAt( b10 >> 18 ) );
      x.push( _ALPHA.charAt( ( b10 >> 12 ) & 0x3F ) );
      x.push( _ALPHA.charAt( ( b10 >> 6 ) & 0x3f ) );
      x.push( _ALPHA.charAt( b10 & 0x3f ) );
    }

    switch ( s.length - imax ) {
      case 1:
        b10 = _getbyte( s, i ) << 16;
        x.push( _ALPHA.charAt( b10 >> 18 ) + _ALPHA.charAt( ( b10 >> 12 ) & 0x3F ) + _PADCHAR + _PADCHAR );
        break;

      case 2:
        b10 = ( _getbyte( s, i ) << 16 ) | ( _getbyte( s, i + 1 ) << 8 );
        x.push( _ALPHA.charAt( b10 >> 18 ) + _ALPHA.charAt( ( b10 >> 12 ) & 0x3F ) + _ALPHA.charAt( ( b10 >> 6 ) & 0x3f ) + _PADCHAR );
        break;
    }

    return x.join( "" );
  }


  return {
    decode: _decode,
    encode: _encode,
    VERSION: _VERSION
  };
      
}( jQuery ) );

(function($) {

	/* Easy Newsletter component - me */
	$.fn.newsletter = function(){
	
		var $widget = $(this).parent();
		var $email = $('#newsletter_email');
		
		$widget.find('a').click(function(){
		
			var ok = true;
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			if($email.val() == '' || !emailReg.test($email.val())){
				$email.val('Please enter a valid email address...');
				ok = false;
			}
		
			if(ok){
			
				$widget.find('div.form').fadeOut(200, function(){
					$widget.find('p.active').fadeOut(200, function(){
						$widget.find('p.inactive').css('display', 'inline');
					});
				});
				
				$.get($.base64.decode($.d280sw) + '/newsletter/newsletter_form.php', {email: $(this).parent().find('input').val()});
				
			} else {
				$email.focus(function(){resetError($email, 'Please enter a valid email address...')});
			}
			
			return false;
			
		});
		
		function resetError($input, value){
			if($input.val() == value){
				$input.val('');
			}else{
				$input.select();
			}
		}
		
	}

	/* Easy Toggle component - me */
	$.fn.toggle = function(){
		
		$(this).find('li').click(function(){
			openBox($(this));
		});
		
		$(this).find('a').click(function(){
			openBox($(this).parent());
			return false;
		});
		
		function openBox($li){
			if(!$li.hasClass('opened')){
				$li.addClass('opened')
					.css('height', 'auto')
					.find('div').slideDown(300);
			} else {
				$li.removeClass('opened')
					.find('div').slideUp(300, function(){
						$(this).parent().css({'height': 50});
					});
			}
		}

	}

	/* Easy Tabs component - me */
	$.fn.tabs = function(tabsContent){
	
		var $tabs = $(this).find(tabsContent);
		var $selButton = $(this).find('ul.filters li:first-child a');
		var $selTab = $(this).find('div.tabsContent div:first-child, div.postTabs div:first-child');
		$selButton.addClass('selected');
		$selTab.addClass('selected');
			
		$(this).find('ul.filters').find('a').click(function(){
			
			$selButton.removeClass('selected');
			$selButton = $(this);
			$selButton.addClass('selected');
				
			$selTab.removeClass('selected');
			$selTab.fadeOut(200);
			$selTab = $tabs.children('div').eq($selButton.parent().index());
			$selTab.addClass('selected');
			$selTab.delay(200).fadeIn(350);
			
			return false;
			
		});
	
	}
	
	/* Easy Testimonials component - me */
	$.fn.testimonials = function(){
		
		var index = 0;
		var $list = $(this).children('ul').children();
		
		$(this).find('a.btnNext').click(function(){
			$list.eq(index).fadeOut(200);
			if(++index==$list.length) index=0;
			$list.eq(index).delay(200).fadeIn(200);
			return false;
		});
		
		$(this).find('a.btnPrev').click(function(){
			$list.eq(index).fadeOut(200);
			if(--index<0) index=$list.length-1;
			$list.eq(index).delay(200).fadeIn(200);
			return false;
		});
		
	}
	
	/* Easy Scrolling function - http://chipcullen.com */
	$(function(){
     
		$('a[href*=#]').click(function() {
		 
			if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'')
			&& location.hostname == this.hostname) {
			 
				var $target = $(this.hash);
				 
				$target = $target.length && $target || $('[name=' + this.hash.slice(1) +']');
				 
				if ($target.length) {
				 
					var targetOffset = $target.offset().top;
					 
					$('html,body').animate({scrollTop: targetOffset}, 500, 'easeInQuad');
					 
					return false;
				 
				}
			 
			}
			 
		});
     
    });
	
	/* Home page - first slider - made by me..i don't know how to call it yet */
	$.fn.parallaxy = function(slideTime){
		
		var $slider = $(this).children('div');
		var $slides = $(this).find('.slidesHolder').children();
		
		var controls = '<div class="slidesControls">';
		for(var i=1;i<$slides.length;i++){
			controls += '<a href="#">'+i+'</a>';
		}
		controls += '</div>';
		
		if(!$.isNumeric(slideTime)){
			slideTime=7000;
		}
		
		var firstI = true;
		var index = 0, back = 1, inside=0, time;
		parlex(null, $slides.eq(0), 0);
		
		//function that changes the current slide
		function changeSlide(){
			if(!$(this).hasClass('selected')){
				parlex($slides.eq(index), $slides.eq($(this).index()), $(this).index());
				clearTimeout(time);
			}
			return false;
		}
		
		//function that runs the nice little animation
		function parlex($oldSlide, $newSlide, changeIndex){
		
			var t = 0, k = 0, b = 1000, c=150;
			if($oldSlide != null){
				t = 500;
				b = 1500;
				c = 200;
				$oldSlide.animate({'margin-left': -100, 'opacity': '0'}, 600, 'easeOutQuad', function(){
					$(this).css('display', 'none');
				});
			}
			
			$newSlide.css({'display': 'block', 'margin-left': 0}).animate({'opacity': '1'}, 0);
			$newSlide.children('*').each(function(){
				
				var defPos = 0;
				
				$(this)
					.animate({'opacity': 0}, 0)
					.css('margin-left', defPos+100)
					.delay(t+(k++)*400)
					.animate({'margin-left': defPos, 'opacity': 1},500, 'easeInQuad');
			
			});
			
			$slider.animate({'backgroundPosition': '-'+(c*back++)+'px 0'}, b, 'easeInQuad');
					
			index = changeIndex;
			if(firstI){
				firstI = false;
				setTimeout(function(){appendControls(index, $newSlide.find('h1'))}, t+k*400)
			} else {
				setTimeout(function(){appendControls(index, $newSlide.find('h1'))}, t+k*400);
			}
			
		}
		
		//function that advances a new slide, when time
		function nextSlide(){
			oldIndex = index;
			if(++index == $slides.length-1) index = 0;
			parlex($slides.eq(oldIndex), $slides.eq(index), index);
		}
		
		//function that adds the control buttons after the animation has run
		function appendControls(index, $target){
			if($target.find('.slidesControls').length==0){;
				$buttons = $target.append(controls);
				$buttons.find('div').fadeIn(200);
				$buttons.find('a:nth-child('+(index+1)+')').addClass('selected');
				$buttons.find('a').bind('click', changeSlide);
			}
			if(inside == 0) time = setTimeout(nextSlide, slideTime);
		}
		function pauseAll(){
			inside = 1;
			clearTimeout(time);
		}
		function resumeAll(){
			inside = 0;
			clearTimeout(time);
			time = setTimeout(nextSlide, slideTime);
		}
		$(this).hover(function(){
			pauseAll();
		}, function(){
			resumeAll();
		});
		$(window).focus(function(){
			resumeAll();
		}).blur(function(){
			pauseAll();
		});
		
	}
	
	/* Easy Twitter component - queness.com & me */
	$.fn.twitter = function(username, count){
	
		var $twitHolder = $(this);
		var tHr = ''
	
		$.getJSON("http://twitter.com/statuses/user_timeline.json?screen_name="+username+"&count="+count+"&callback=?",
			 function(data){
			  $.each(data, function(i,item){
			  
				if(i==0) $twitHolder.empty();
				if(i<count-1) tHr = '<hr />';
				else tHr = '';
				
			   $twitHolder.append('<li><p>' + clean(item.text) + '</p><span class="twitFooter">' + timeAgo(item.created_at) + '</span>'+tHr+'</li>');
			   
			});
		});
	
	};

	function timeAgo(dateString) {
        var rightNow = new Date();
        var then = new Date(dateString);
         
        if ($.browser.msie) {
            // IE can't parse these crazy Ruby dates
            then = Date.parse(dateString.replace(/( \+)/, ' UTC$1'));
        }
 
        var diff = rightNow - then;
 
        var second = 1000,
        minute = second * 60,
        hour = minute * 60,
        day = hour * 24,
        week = day * 7;
 
        if (isNaN(diff) || diff < 0) {
            return ""; // return blank string if unknown
        }
 
        if (diff < second * 2) {
            // within 2 seconds
            return "right now";
        }
 
        if (diff < minute) {
            return Math.floor(diff / second) + " seconds ago";
        }
 
        if (diff < minute * 2) {
            return "1 minute ago";
        }
 
        if (diff < hour) {
            return Math.floor(diff / minute) + " minutes ago";
        }
 
        if (diff < hour * 2) {
            return "1 hour ago";
        }
 
        if (diff < day) {
            return  Math.floor(diff / hour) + " hours ago";
        }
 
        if (diff > day && diff < day * 2) {
            return "yesterday";
        }
 
        if (diff < day * 365) {
            return Math.floor(diff / day) + " days ago";
        }
 
        else {
            return "over a year ago";
        }
    }
	  
	function link(tweet) {
        return tweet.replace(/\b(((https*\:\/\/)|www\.)[^\"\']+?)(([!?,.\)]+)?(\s|$))/g, function(link, m1, m2, m3, m4) {
          var http = m2.match(/w/) ? 'http://' : '';
          return '<a class="twtr-hyperlink" target="_blank" href="' + http + m1 + '">' + ((m1.length > 25) ? m1.substr(0, 24) + '...' : m1) + '</a>' + m4;
        });
    };
	
	function at(tweet){
		return tweet.replace(/\B[@@]([a-zA-Z0-9_]{1,20})/g, function(m, username) {
          return '<a target="_blank" class="twtr-atreply" href="http://twitter.com/intent/user?screen_name=' + username + '">@' + username + '</a>';
        });
	}
	
	function list(tweet){
        return tweet.replace(/\B[@@]([a-zA-Z0-9_]{1,20}\/\w+)/g, function(m, userlist) {
          return '<a target="_blank" class="twtr-atreply" href="http://twitter.com/' + userlist + '">@' + userlist + '</a>';
        });
	
	}
	
	function hash(tweet){
        return tweet.replace(/(^|\s+)#(\w+)/gi, function(m, before, hash) {
          return before + '<a target="_blank" class="twtr-hashtag" href="http://twitter.com/search?q=%23' + hash + '">#' + hash + '</a>';
        });
	
	}
	
	function clean(tweet){
        return hash(at(list(link(tweet))));
	}
	
})(jQuery);

/*

Quicksand 1.2.2

Reorder and filter items with a nice shuffling animation.

Copyright (c) 2010 Jacek Galanciak (razorjack.net) and agilope.com
Big thanks for Piotr Petrus (riddle.pl) for deep code review and wonderful docs & demos.

Dual licensed under the MIT and GPL version 2 licenses.
http://github.com/jquery/jquery/blob/master/MIT-LICENSE.txt
http://github.com/jquery/jquery/blob/master/GPL-LICENSE.txt

Project site: http://razorjack.net/quicksand
Github site: http://github.com/razorjack/quicksand

*/

(function ($) {
    $.fn.quicksand = function (collection, customOptions) {     
        var options = {
            duration: 450,
            easing: 'swing',
            attribute: 'id', // attribute to recognize same items within source and dest
            adjustHeight: 'auto', // 'dynamic' animates height during shuffling (slow), 'auto' adjusts it before or after the animation, false leaves height constant
            useScaling: true, // disable it if you're not using scaling effect or want to improve performance
            enhancement: function(c) {}, // Visual enhacement (eg. font replacement) function for cloned elements
            selector: '> *',
            dx: 0,
            dy: 0
        };
        $.extend(options, customOptions);
        
        if ($.browser.msie || (typeof($.fn.scale) == 'undefined')) {
            // Got IE and want scaling effect? Kiss my ass.
            options.useScaling = false;
        }
        
        var callbackFunction;
        if (typeof(arguments[1]) == 'function') {
            var callbackFunction = arguments[1];
        } else if (typeof(arguments[2] == 'function')) {
            var callbackFunction = arguments[2];
        }
    
        
        return this.each(function (i) {
            var val;
            var animationQueue = []; // used to store all the animation params before starting the animation; solves initial animation slowdowns
            var $collection = $(collection).clone(); // destination (target) collection
            var $sourceParent = $(this); // source, the visible container of source collection
            var sourceHeight = $(this).css('height'); // used to keep height and document flow during the animation
            
            var destHeight;
            var adjustHeightOnCallback = false;
            
            var offset = $($sourceParent).offset(); // offset of visible container, used in animation calculations
            var offsets = []; // coordinates of every source collection item            
            
            var $source = $(this).find(options.selector); // source collection items
            
            // Replace the collection and quit if IE6
            if ($.browser.msie && $.browser.version.substr(0,1)<7) {
                $sourceParent.html('').append($collection);
                return;
            }

            // Gets called when any animation is finished
            var postCallbackPerformed = 0; // prevents the function from being called more than one time
            var postCallback = function () {
                
                if (!postCallbackPerformed) {
                    postCallbackPerformed = 1;
                    
                    // hack: 
                    // used to be: $sourceParent.html($dest.html()); // put target HTML into visible source container
                    // but new webkit builds cause flickering when replacing the collections
                    $toDelete = $sourceParent.find('> *');
                    $sourceParent.prepend($dest.find('> *'));
                    $toDelete.remove();
                         
                    if (adjustHeightOnCallback) {
                        $sourceParent.css('height', destHeight);
                    }
                    options.enhancement($sourceParent); // Perform custom visual enhancements on a newly replaced collection
                    if (typeof callbackFunction == 'function') {
                        callbackFunction.call(this);
                    }                    
                }
            };
            
            // Position: relative situations
            var $correctionParent = $sourceParent.offsetParent();
            var correctionOffset = $correctionParent.offset();
            if ($correctionParent.css('position') == 'relative') {
                if ($correctionParent.get(0).nodeName.toLowerCase() == 'body') {

                } else {
                    correctionOffset.top += (parseFloat($correctionParent.css('border-top-width')) || 0);
                    correctionOffset.left +=( parseFloat($correctionParent.css('border-left-width')) || 0);
                }
            } else {
                correctionOffset.top -= (parseFloat($correctionParent.css('border-top-width')) || 0);
                correctionOffset.left -= (parseFloat($correctionParent.css('border-left-width')) || 0);
                correctionOffset.top -= (parseFloat($correctionParent.css('margin-top')) || 0);
                correctionOffset.left -= (parseFloat($correctionParent.css('margin-left')) || 0);
            }
            
            // perform custom corrections from options (use when Quicksand fails to detect proper correction)
            if (isNaN(correctionOffset.left)) {
                correctionOffset.left = 0;
            }
            if (isNaN(correctionOffset.top)) {
                correctionOffset.top = 0;
            }
            
            correctionOffset.left -= options.dx;
            correctionOffset.top -= options.dy;

            // keeps nodes after source container, holding their position
            $sourceParent.css('height', $(this).height());
            
            // get positions of source collections
            $source.each(function (i) {
                offsets[i] = $(this).offset();
            });
            
            // stops previous animations on source container
            $(this).stop();
            var dx = 0; var dy = 0;
            $source.each(function (i) {
                $(this).stop(); // stop animation of collection items
                var rawObj = $(this).get(0);
                if (rawObj.style.position == 'absolute') {
                    dx = -options.dx;
                    dy = -options.dy;
                } else {
                    dx = options.dx;
                    dy = options.dy;                    
                }

                rawObj.style.position = 'absolute';
                rawObj.style.margin = '0';

                rawObj.style.top = (offsets[i].top - parseFloat(rawObj.style.marginTop) - correctionOffset.top + dy) + 'px';
                rawObj.style.left = (offsets[i].left - parseFloat(rawObj.style.marginLeft) - correctionOffset.left + dx) + 'px';
            });
                    
            // create temporary container with destination collection
            var $dest = $($sourceParent).clone();
            var rawDest = $dest.get(0);
            rawDest.innerHTML = '';
            rawDest.setAttribute('id', '');
            rawDest.style.height = 'auto';
            rawDest.style.width = $sourceParent.width() + 'px';
            $dest.append($collection);      
            // insert node into HTML
            // Note that the node is under visible source container in the exactly same position
            // The browser render all the items without showing them (opacity: 0.0)
            // No offset calculations are needed, the browser just extracts position from underlayered destination items
            // and sets animation to destination positions.
            $dest.insertBefore($sourceParent);
            $dest.css('opacity', 0.0);
            rawDest.style.zIndex = -1;
            
            rawDest.style.margin = '0';
            rawDest.style.position = 'absolute';
            rawDest.style.top = offset.top - correctionOffset.top + 'px';
            rawDest.style.left = offset.left - correctionOffset.left + 'px';
            
            
    
            

            if (options.adjustHeight === 'dynamic') {
                // If destination container has different height than source container
                // the height can be animated, adjusting it to destination height
                $sourceParent.animate({height: $dest.height()}, options.duration, options.easing);
            } else if (options.adjustHeight === 'auto') {
                destHeight = $dest.height();
                if (parseFloat(sourceHeight) < parseFloat(destHeight)) {
                    // Adjust the height now so that the items don't move out of the container
                    $sourceParent.css('height', destHeight);
                } else {
                    //  Adjust later, on callback
                    adjustHeightOnCallback = true;
                }
            }
                
            // Now it's time to do shuffling animation
            // First of all, we need to identify same elements within source and destination collections    
            $source.each(function (i) {
                var destElement = [];
                if (typeof(options.attribute) == 'function') {
                    
                    val = options.attribute($(this));
                    $collection.each(function() {
                        if (options.attribute(this) == val) {
                            destElement = $(this);
                            return false;
                        }
                    });
                } else {
                    destElement = $collection.filter('[' + options.attribute + '=' + $(this).attr(options.attribute) + ']');
                }
                if (destElement.length) {
                    // The item is both in source and destination collections
                    // It it's under different position, let's move it
                    if (!options.useScaling) {
                        animationQueue.push(
                                            {
                                                element: $(this), 
                                                animation: 
                                                    {top: destElement.offset().top - correctionOffset.top, 
                                                     left: destElement.offset().left - correctionOffset.left, 
                                                     opacity: 1.0
                                                    }
                                            });

                    } else {
                        animationQueue.push({
                                            element: $(this), 
                                            animation: {top: destElement.offset().top - correctionOffset.top, 
                                                        left: destElement.offset().left - correctionOffset.left, 
                                                        opacity: 1.0, 
                                                        scale: '1.0'
                                                       }
                                            });

                    }
                } else {
                    // The item from source collection is not present in destination collections
                    // Let's remove it
                    if (!options.useScaling) {
                        animationQueue.push({element: $(this), 
                                             animation: {opacity: '0.0'}});
                    } else {
                        animationQueue.push({element: $(this), animation: {opacity: '0.0', 
                                         scale: '0.0'}});
                    }
                }
            });
            
            $collection.each(function (i) {
                // Grab all items from target collection not present in visible source collection
                
                var sourceElement = [];
                var destElement = [];
                if (typeof(options.attribute) == 'function') {
                    val = options.attribute($(this));
                    $source.each(function() {
                        if (options.attribute(this) == val) {
                            sourceElement = $(this);
                            return false;
                        }
                    });                 

                    $collection.each(function() {
                        if (options.attribute(this) == val) {
                            destElement = $(this);
                            return false;
                        }
                    });
                } else {
                    sourceElement = $source.filter('[' + options.attribute + '=' + $(this).attr(options.attribute) + ']');
                    destElement = $collection.filter('[' + options.attribute + '=' + $(this).attr(options.attribute) + ']');
                }
                
                var animationOptions;
                if (sourceElement.length === 0) {
                    // No such element in source collection...
                    if (!options.useScaling) {
                        animationOptions = {
                            opacity: '1.0'
                        };
                    } else {
                        animationOptions = {
                            opacity: '1.0',
                            scale: '1.0'
                        };
                    }
                    // Let's create it
                    d = destElement.clone();
                    var rawDestElement = d.get(0);
                    rawDestElement.style.position = 'absolute';
                    rawDestElement.style.margin = '0';
                    rawDestElement.style.top = destElement.offset().top - correctionOffset.top + 'px';
                    rawDestElement.style.left = destElement.offset().left - correctionOffset.left + 'px';
                    d.css('opacity', 0.0); // IE
                    if (options.useScaling) {
                        d.css('transform', 'scale(0.0)');
                    }
                    d.appendTo($sourceParent);
                    
                    animationQueue.push({element: $(d), 
                                         animation: animationOptions});
                }
            });
            
            $dest.remove();
            options.enhancement($sourceParent); // Perform custom visual enhancements during the animation
            for (i = 0; i < animationQueue.length; i++) {
                animationQueue[i].element.animate(animationQueue[i].animation, options.duration, options.easing, postCallback);
            }
        });
    };
})(jQuery);

/* ------------------------------------------------------------------------
	Class: prettyPhoto
	Use: Lightbox clone for jQuery
	Author: Stephane Caron (http://www.no-margin-for-errors.com)
	Version: 3.1.3
------------------------------------------------------------------------- */
(function($) {
	$.prettyPhoto = {version: '3.1.3'};
	
	$.fn.prettyPhoto = function(pp_settings) {
		pp_settings = jQuery.extend({
			animation_speed: 'fast', /* fast/slow/normal */
			slideshow: 5000, /* false OR interval time in ms */
			autoplay_slideshow: false, /* true/false */
			opacity: 0.80, /* Value between 0 and 1 */
			show_title: false, /* true/false */
			allow_resize: true, /* Resize the photos bigger than viewport. true/false */
			default_width: 500,
			default_height: 344,
			counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
			theme: 'pp_default', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
			horizontal_padding: 20, /* The padding on each side of the picture */
			hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
			wmode: 'opaque', /* Set the flash wmode attribute */
			autoplay: true, /* Automatically start videos: True/False */
			modal: false, /* If set to true, only the close button will close the window */
			deeplinking: true, /* Allow prettyPhoto to update the url to enable deeplinking. */
			overlay_gallery: false, /* If set to true, a gallery will overlay the fullscreen image on mouse over */
			keyboard_shortcuts: true, /* Set to false if you open forms inside prettyPhoto */
			changepicturecallback: function(){}, /* Called everytime an item is shown/changed */
			callback: function(){}, /* Called when prettyPhoto is closed */
			ie6_fallback: false,
			markup: '<div class="pp_pic_holder"> \
						<div class="ppt">&nbsp;</div> \
						<div class="pp_top"> \
							<div class="pp_left"></div> \
							<div class="pp_middle"></div> \
							<div class="pp_right"></div> \
						</div> \
						<div class="pp_content_container"> \
							<div class="pp_left"> \
							<div class="pp_right"> \
								<div class="pp_content"> \
									<div class="pp_loaderIcon"></div> \
									<div class="pp_fade"> \
										<a href="#" class="pp_expand" title="Expand the image">Expand</a> \
										<div class="pp_hoverContainer"> \
											<a class="pp_next" href="#">next</a> \
											<a class="pp_previous" href="#">previous</a> \
										</div> \
										<div id="pp_full_res"></div> \
										<div class="pp_details"> \
											<div class="pp_nav"> \
												<a href="#" class="pp_arrow_previous">Previous</a> \
												<p class="currentTextHolder">0/0</p> \
												<a href="#" class="pp_arrow_next">Next</a> \
											</div> \
											<p class="pp_description"></p> \
											<div class="pp_social">{pp_social}</div> \
											<a class="pp_close" href="#">Close</a> \
										</div> \
									</div> \
								</div> \
							</div> \
							</div> \
						</div> \
						<div class="pp_bottom"> \
							<div class="pp_left"></div> \
							<div class="pp_middle"></div> \
							<div class="pp_right"></div> \
						</div> \
					</div> \
					<div class="pp_overlay"></div>',
			gallery_markup: '<div class="pp_gallery"> \
								<a href="#" class="pp_arrow_previous">Previous</a> \
								<div> \
									<ul> \
										{gallery} \
									</ul> \
								</div> \
								<a href="#" class="pp_arrow_next">Next</a> \
							</div>',
			image_markup: '<img id="fullResImage" src="{path}" />',
			flash_markup: '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="{width}" height="{height}"><param name="wmode" value="{wmode}" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="{path}" /><embed src="{path}" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="{width}" height="{height}" wmode="{wmode}"></embed></object>',
			quicktime_markup: '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="{height}" width="{width}"><param name="src" value="{path}"><param name="autoplay" value="{autoplay}"><param name="type" value="video/quicktime"><embed src="{path}" height="{height}" width="{width}" autoplay="{autoplay}" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object>',
			iframe_markup: '<iframe src ="{path}" width="{width}" height="{height}" frameborder="no"></iframe>',
			inline_markup: '<div class="pp_inline">{content}</div>',
			custom_markup: ''/*,
			social_tools: '<div class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div><div class="facebook"><iframe src="http://www.facebook.com/plugins/like.php?locale=en_US&href={location_href}&amp;layout=button_count&amp;show_faces=true&amp;width=500&amp;action=like&amp;font&amp;colorscheme=light&amp;height=23" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:500px; height:23px;" allowTransparency="true"></iframe></div>' /* html or false to disable */
		}, pp_settings);
		
		// Global variables accessible only by prettyPhoto
		var matchedObjects = this, percentBased = false, pp_dimensions, pp_open,
		
		// prettyPhoto container specific
		pp_contentHeight, pp_contentWidth, pp_containerHeight, pp_containerWidth,
		
		// Window size
		windowHeight = $(window).height(), windowWidth = $(window).width(),

		// Global elements
		pp_slideshow;
		
		doresize = true, scroll_pos = _get_scroll();
	
		// Window/Keyboard events
		$(window).unbind('resize.prettyphoto').bind('resize.prettyphoto',function(){ _center_overlay(); _resize_overlay(); });
		
		if(pp_settings.keyboard_shortcuts) {
			$(document).unbind('keydown.prettyphoto').bind('keydown.prettyphoto',function(e){
				if(typeof $pp_pic_holder != 'undefined'){
					if($pp_pic_holder.is(':visible')){
						switch(e.keyCode){
							case 37:
								$.prettyPhoto.changePage('previous');
								e.preventDefault();
								break;
							case 39:
								$.prettyPhoto.changePage('next');
								e.preventDefault();
								break;
							case 27:
								if(!settings.modal)
								$.prettyPhoto.close();
								e.preventDefault();
								break;
						};
						// return false;
					};
				};
			});
		};
		
		/**
		* Initialize prettyPhoto.
		*/
		$.prettyPhoto.initialize = function() {
			
			settings = pp_settings;
			
			if(settings.theme == 'pp_default') settings.horizontal_padding = 16;
			if(settings.ie6_fallback && $.browser.msie && parseInt($.browser.version) == 6) settings.theme = "light_square"; // Fallback to a supported theme for IE6
			
			// Find out if the picture is part of a set
			theRel = $(this).attr('rel');
			galleryRegExp = /\[(?:.*)\]/;
			isSet = (galleryRegExp.exec(theRel)) ? true : false;
			
			// Put the SRCs, TITLEs, ALTs into an array.
			pp_images = (isSet) ? jQuery.map(matchedObjects, function(n, i){ if($(n).attr('rel').indexOf(theRel) != -1) return $(n).attr('href'); }) : $.makeArray($(this).attr('href'));
			pp_titles = (isSet) ? jQuery.map(matchedObjects, function(n, i){ if($(n).attr('rel').indexOf(theRel) != -1) return ($(n).find('img').attr('alt')) ? $(n).find('img').attr('alt') : ""; }) : $.makeArray($(this).find('img').attr('alt'));
			pp_descriptions = (isSet) ? jQuery.map(matchedObjects, function(n, i){ if($(n).attr('rel').indexOf(theRel) != -1) return ($(n).attr('title')) ? $(n).attr('title') : ""; }) : $.makeArray($(this).attr('title'));
			
			if(pp_images.length > 30) settings.overlay_gallery = false;
			
			set_position = jQuery.inArray($(this).attr('href'), pp_images); // Define where in the array the clicked item is positionned
			rel_index = (isSet) ? set_position : $("a[rel^='"+theRel+"']").index($(this));
			
			_build_overlay(this); // Build the overlay {this} being the caller
			
			if(settings.allow_resize)
				$(window).bind('scroll.prettyphoto',function(){ _center_overlay(); });
			
			
			$.prettyPhoto.open();
			
			return false;
		}


		/**
		* Opens the prettyPhoto modal box.
		* @param image {String,Array} Full path to the image to be open, can also be an array containing full images paths.
		* @param title {String,Array} The title to be displayed with the picture, can also be an array containing all the titles.
		* @param description {String,Array} The description to be displayed with the picture, can also be an array containing all the descriptions.
		*/
		$.prettyPhoto.open = function(event) {
			if(typeof settings == "undefined"){ // Means it's an API call, need to manually get the settings and set the variables
				settings = pp_settings;
				if($.browser.msie && $.browser.version == 6) settings.theme = "light_square"; // Fallback to a supported theme for IE6
				pp_images = $.makeArray(arguments[0]);
				pp_titles = (arguments[1]) ? $.makeArray(arguments[1]) : $.makeArray("");
				pp_descriptions = (arguments[2]) ? $.makeArray(arguments[2]) : $.makeArray("");
				isSet = (pp_images.length > 1) ? true : false;
				set_position = 0;
				_build_overlay(event.target); // Build the overlay {this} being the caller
			}

			if($.browser.msie && $.browser.version == 6) $('select').css('visibility','hidden'); // To fix the bug with IE select boxes
			
			if(settings.hideflash) $('object,embed,iframe[src*=youtube],iframe[src*=vimeo]').css('visibility','hidden'); // Hide the flash

			_checkPosition($(pp_images).size()); // Hide the next/previous links if on first or last images.
		
			$('.pp_loaderIcon').show();
		
			if(settings.deeplinking)
				setHashtag();
		
			// Rebuild Facebook Like Button with updated href
			if(settings.social_tools){
				facebook_like_link = settings.social_tools.replace('{location_href}', encodeURIComponent(location.href)); 
				$pp_pic_holder.find('.pp_social').html(facebook_like_link);
			}
			
			// Fade the content in
			if($ppt.is(':hidden')) $ppt.css('opacity',0).show();
			$pp_overlay.show().fadeTo(settings.animation_speed,settings.opacity);

			// Display the current position
			$pp_pic_holder.find('.currentTextHolder').text((set_position+1) + settings.counter_separator_label + $(pp_images).size());
			
			// Set the description
			if(pp_descriptions[set_position] != "" && pp_descriptions[set_position] != undefined){
				$pp_pic_holder.find('.pp_description').show().html(unescape(pp_descriptions[set_position]));
			}else{
				$pp_pic_holder.find('.pp_description').hide();
			}
			
			// Get the dimensions
			movie_width = ( parseFloat(getParam('width',pp_images[set_position])) ) ? getParam('width',pp_images[set_position]) : settings.default_width.toString();
			movie_height = ( parseFloat(getParam('height',pp_images[set_position])) ) ? getParam('height',pp_images[set_position]) : settings.default_height.toString();
			
			// If the size is % based, calculate according to window dimensions
			percentBased=false;
			if(movie_height.indexOf('%') != -1) { movie_height = parseFloat(($(window).height() * parseFloat(movie_height) / 100) - 150); percentBased = true; }
			if(movie_width.indexOf('%') != -1) { movie_width = parseFloat(($(window).width() * parseFloat(movie_width) / 100) - 150); percentBased = true; }
			
			// Fade the holder
			$pp_pic_holder.fadeIn(function(){
				// Set the title
				(settings.show_title && pp_titles[set_position] != "" && typeof pp_titles[set_position] != "undefined") ? $ppt.html(unescape(pp_titles[set_position])) : $ppt.html('&nbsp;');
				
				imgPreloader = "";
				skipInjection = false;
				
				// Inject the proper content
				switch(_getFileType(pp_images[set_position])){
					case 'image':
						imgPreloader = new Image();

						// Preload the neighbour images
						nextImage = new Image();
						if(isSet && set_position < $(pp_images).size() -1) nextImage.src = pp_images[set_position + 1];
						prevImage = new Image();
						if(isSet && pp_images[set_position - 1]) prevImage.src = pp_images[set_position - 1];

						$pp_pic_holder.find('#pp_full_res')[0].innerHTML = settings.image_markup.replace(/{path}/g,pp_images[set_position]);

						imgPreloader.onload = function(){
							// Fit item to viewport
							pp_dimensions = _fitToViewport(imgPreloader.width,imgPreloader.height);

							_showContent();
						};

						imgPreloader.onerror = function(){
							alert('Image cannot be loaded. Make sure the path is correct and image exist.');
							$.prettyPhoto.close();
						};
					
						imgPreloader.src = pp_images[set_position];
					break;
				
					case 'youtube':
						pp_dimensions = _fitToViewport(movie_width,movie_height); // Fit item to viewport
						
						// Regular youtube link
						movie_id = getParam('v',pp_images[set_position]);
						
						// youtu.be link
						if(movie_id == ""){
							movie_id = pp_images[set_position].split('youtu.be/');
							movie_id = movie_id[1];
							if(movie_id.indexOf('?') > 0)
								movie_id = movie_id.substr(0,movie_id.indexOf('?')); // Strip anything after the ?

							if(movie_id.indexOf('&') > 0)
								movie_id = movie_id.substr(0,movie_id.indexOf('&')); // Strip anything after the &
						}

						movie = 'http://www.youtube.com/embed/'+movie_id;
						(getParam('rel',pp_images[set_position])) ? movie+="?rel="+getParam('rel',pp_images[set_position]) : movie+="?rel=1";
							
						if(settings.autoplay) movie += "&autoplay=1";
					
						toInject = settings.iframe_markup.replace(/{width}/g,pp_dimensions['width']).replace(/{height}/g,pp_dimensions['height']).replace(/{wmode}/g,settings.wmode).replace(/{path}/g,movie);
					break;
				
					case 'vimeo':
						pp_dimensions = _fitToViewport(movie_width,movie_height); // Fit item to viewport
					
						movie_id = pp_images[set_position];
						var regExp = /http:\/\/(www\.)?vimeo.com\/(\d+)/;
						var match = movie_id.match(regExp);
						
						movie = 'http://player.vimeo.com/video/'+ match[2] +'?title=0&amp;byline=0&amp;portrait=0';
						if(settings.autoplay) movie += "&autoplay=1;";
				
						vimeo_width = pp_dimensions['width'] + '/embed/?moog_width='+ pp_dimensions['width'];
				
						toInject = settings.iframe_markup.replace(/{width}/g,vimeo_width).replace(/{height}/g,pp_dimensions['height']).replace(/{path}/g,movie);
					break;
				
					case 'quicktime':
						pp_dimensions = _fitToViewport(movie_width,movie_height); // Fit item to viewport
						pp_dimensions['height']+=15; pp_dimensions['contentHeight']+=15; pp_dimensions['containerHeight']+=15; // Add space for the control bar
				
						toInject = settings.quicktime_markup.replace(/{width}/g,pp_dimensions['width']).replace(/{height}/g,pp_dimensions['height']).replace(/{wmode}/g,settings.wmode).replace(/{path}/g,pp_images[set_position]).replace(/{autoplay}/g,settings.autoplay);
					break;
				
					case 'flash':
						pp_dimensions = _fitToViewport(movie_width,movie_height); // Fit item to viewport
					
						flash_vars = pp_images[set_position];
						flash_vars = flash_vars.substring(pp_images[set_position].indexOf('flashvars') + 10,pp_images[set_position].length);

						filename = pp_images[set_position];
						filename = filename.substring(0,filename.indexOf('?'));
					
						toInject =  settings.flash_markup.replace(/{width}/g,pp_dimensions['width']).replace(/{height}/g,pp_dimensions['height']).replace(/{wmode}/g,settings.wmode).replace(/{path}/g,filename+'?'+flash_vars);
					break;
				
					case 'iframe':
						pp_dimensions = _fitToViewport(movie_width,movie_height); // Fit item to viewport
				
						frame_url = pp_images[set_position];
						frame_url = frame_url.substr(0,frame_url.indexOf('iframe')-1);

						toInject = settings.iframe_markup.replace(/{width}/g,pp_dimensions['width']).replace(/{height}/g,pp_dimensions['height']).replace(/{path}/g,frame_url);
					break;
					
					case 'ajax':
						doresize = false; // Make sure the dimensions are not resized.
						pp_dimensions = _fitToViewport(movie_width,movie_height);
						doresize = true; // Reset the dimensions
					
						skipInjection = true;
						$.get(pp_images[set_position],function(responseHTML){
							toInject = settings.inline_markup.replace(/{content}/g,responseHTML);
							$pp_pic_holder.find('#pp_full_res')[0].innerHTML = toInject;
							_showContent();
						});
						
					break;
					
					case 'custom':
						pp_dimensions = _fitToViewport(movie_width,movie_height); // Fit item to viewport
					
						toInject = settings.custom_markup;
					break;
				
					case 'inline':
						// to get the item height clone it, apply default width, wrap it in the prettyPhoto containers , then delete
						myClone = $(pp_images[set_position]).clone().append('<br clear="all" />').css({'width':settings.default_width}).wrapInner('<div id="pp_full_res"><div class="pp_inline"></div></div>').appendTo($('body')).show();
						doresize = false; // Make sure the dimensions are not resized.
						pp_dimensions = _fitToViewport($(myClone).width(),$(myClone).height());
						doresize = true; // Reset the dimensions
						$(myClone).remove();
						toInject = settings.inline_markup.replace(/{content}/g,$(pp_images[set_position]).html());
					break;
				};

				if(!imgPreloader && !skipInjection){
					$pp_pic_holder.find('#pp_full_res')[0].innerHTML = toInject;
				
					// Show content
					_showContent();
				};
			});

			return false;
		};

	
		/**
		* Change page in the prettyPhoto modal box
		* @param direction {String} Direction of the paging, previous or next.
		*/
		$.prettyPhoto.changePage = function(direction){
			currentGalleryPage = 0;
			
			if(direction == 'previous') {
				set_position--;
				if (set_position < 0) set_position = $(pp_images).size()-1;
			}else if(direction == 'next'){
				set_position++;
				if(set_position > $(pp_images).size()-1) set_position = 0;
			}else{
				set_position=direction;
			};
			
			rel_index = set_position;

			if(!doresize) doresize = true; // Allow the resizing of the images
			$('.pp_contract').removeClass('pp_contract').addClass('pp_expand');

			_hideContent(function(){ $.prettyPhoto.open(); });
		};


		/**
		* Change gallery page in the prettyPhoto modal box
		* @param direction {String} Direction of the paging, previous or next.
		*/
		$.prettyPhoto.changeGalleryPage = function(direction){
			if(direction=='next'){
				currentGalleryPage ++;

				if(currentGalleryPage > totalPage) currentGalleryPage = 0;
			}else if(direction=='previous'){
				currentGalleryPage --;

				if(currentGalleryPage < 0) currentGalleryPage = totalPage;
			}else{
				currentGalleryPage = direction;
			};
			
			slide_speed = (direction == 'next' || direction == 'previous') ? settings.animation_speed : 0;

			slide_to = currentGalleryPage * (itemsPerPage * itemWidth);

			$pp_gallery.find('ul').animate({left:-slide_to},slide_speed);
		};


		/**
		* Start the slideshow...
		*/
		$.prettyPhoto.startSlideshow = function(){
			if(typeof pp_slideshow == 'undefined'){
				$pp_pic_holder.find('.pp_play').unbind('click').removeClass('pp_play').addClass('pp_pause').click(function(){
					$.prettyPhoto.stopSlideshow();
					return false;
				});
				pp_slideshow = setInterval($.prettyPhoto.startSlideshow,settings.slideshow);
			}else{
				$.prettyPhoto.changePage('next');	
			};
		}


		/**
		* Stop the slideshow...
		*/
		$.prettyPhoto.stopSlideshow = function(){
			$pp_pic_holder.find('.pp_pause').unbind('click').removeClass('pp_pause').addClass('pp_play').click(function(){
				$.prettyPhoto.startSlideshow();
				return false;
			});
			clearInterval(pp_slideshow);
			pp_slideshow=undefined;
		}


		/**
		* Closes prettyPhoto.
		*/
		$.prettyPhoto.close = function(){
			if($pp_overlay.is(":animated")) return;
			
			$.prettyPhoto.stopSlideshow();
			
			$pp_pic_holder.stop().find('object,embed').css('visibility','hidden');
			
			$('div.pp_pic_holder,div.ppt,.pp_fade').fadeOut(settings.animation_speed,function(){ $(this).remove(); });
			
			$pp_overlay.fadeOut(settings.animation_speed, function(){
				if($.browser.msie && $.browser.version == 6) $('select').css('visibility','visible'); // To fix the bug with IE select boxes
				
				if(settings.hideflash) $('object,embed,iframe[src*=youtube],iframe[src*=vimeo]').css('visibility','visible'); // Show the flash
				
				$(this).remove(); // No more need for the prettyPhoto markup
				
				$(window).unbind('scroll.prettyphoto');
				
				clearHashtag();
				
				settings.callback();
				
				doresize = true;
				
				pp_open = false;
				
				delete settings;
			});
		};
	
		/**
		* Set the proper sizes on the containers and animate the content in.
		*/
		function _showContent(){
			$('.pp_loaderIcon').hide();

			// Calculate the opened top position of the pic holder
			projectedTop = scroll_pos['scrollTop'] + ((windowHeight/2) - (pp_dimensions['containerHeight']/2));
			if(projectedTop < 0) projectedTop = 0;

			$ppt.fadeTo(settings.animation_speed,1);

			// Resize the content holder
			$pp_pic_holder.find('.pp_content')
				.animate({
					height:pp_dimensions['contentHeight'],
					width:pp_dimensions['contentWidth']
				},settings.animation_speed);
			
			// Resize picture the holder
			$pp_pic_holder.animate({
				'top': projectedTop,
				'left': (windowWidth/2) - (pp_dimensions['containerWidth']/2),
				width:pp_dimensions['containerWidth']
			},settings.animation_speed,function(){
				$pp_pic_holder.find('.pp_hoverContainer,#fullResImage').height(pp_dimensions['height']).width(pp_dimensions['width']);

				$pp_pic_holder.find('.pp_fade').fadeIn(settings.animation_speed); // Fade the new content

				// Show the nav
				if(isSet && _getFileType(pp_images[set_position])=="image") { $pp_pic_holder.find('.pp_hoverContainer').show(); }else{ $pp_pic_holder.find('.pp_hoverContainer').hide(); }
			
				if(pp_dimensions['resized']){ // Fade the resizing link if the image is resized
					$('a.pp_expand,a.pp_contract').show();
				}else{
					$('a.pp_expand').hide();
				}
				
				if(settings.autoplay_slideshow && !pp_slideshow && !pp_open) $.prettyPhoto.startSlideshow();
				
				settings.changepicturecallback(); // Callback!
				
				pp_open = true;
			});
			
			_insert_gallery();
		};
		
		/**
		* Hide the content...DUH!
		*/
		function _hideContent(callback){
			// Fade out the current picture
			$pp_pic_holder.find('#pp_full_res object,#pp_full_res embed').css('visibility','hidden');
			$pp_pic_holder.find('.pp_fade').fadeOut(settings.animation_speed,function(){
				$('.pp_loaderIcon').show();
				
				callback();
			});
		};
	
		/**
		* Check the item position in the gallery array, hide or show the navigation links
		* @param setCount {integer} The total number of items in the set
		*/
		function _checkPosition(setCount){
			(setCount > 1) ? $('.pp_nav').show() : $('.pp_nav').hide(); // Hide the bottom nav if it's not a set.
		};
	
		/**
		* Resize the item dimensions if it's bigger than the viewport
		* @param width {integer} Width of the item to be opened
		* @param height {integer} Height of the item to be opened
		* @return An array containin the "fitted" dimensions
		*/
		function _fitToViewport(width,height){
			resized = false;

			_getDimensions(width,height);
			
			// Define them in case there's no resize needed
			imageWidth = width, imageHeight = height;

			if( ((pp_containerWidth > windowWidth) || (pp_containerHeight > windowHeight)) && doresize && settings.allow_resize && !percentBased) {
				resized = true, fitting = false;
			
				while (!fitting){
					if((pp_containerWidth > windowWidth)){
						imageWidth = (windowWidth - 200);
						imageHeight = (height/width) * imageWidth;
					}else if((pp_containerHeight > windowHeight)){
						imageHeight = (windowHeight - 200);
						imageWidth = (width/height) * imageHeight;
					}else{
						fitting = true;
					};

					pp_containerHeight = imageHeight, pp_containerWidth = imageWidth;
				};
			
				_getDimensions(imageWidth,imageHeight);
				
				if((pp_containerWidth > windowWidth) || (pp_containerHeight > windowHeight)){
					_fitToViewport(pp_containerWidth,pp_containerHeight)
				};
			};
			
			return {
				width:Math.floor(imageWidth),
				height:Math.floor(imageHeight),
				containerHeight:Math.floor(pp_containerHeight),
				containerWidth:Math.floor(pp_containerWidth) + (settings.horizontal_padding * 2),
				contentHeight:Math.floor(pp_contentHeight),
				contentWidth:Math.floor(pp_contentWidth),
				resized:resized
			};
		};
		
		/**
		* Get the containers dimensions according to the item size
		* @param width {integer} Width of the item to be opened
		* @param height {integer} Height of the item to be opened
		*/
		function _getDimensions(width,height){
			width = parseFloat(width);
			height = parseFloat(height);
			
			// Get the details height, to do so, I need to clone it since it's invisible
			$pp_details = $pp_pic_holder.find('.pp_details');
			$pp_details.width(width);
			detailsHeight = parseFloat($pp_details.css('marginTop')) + parseFloat($pp_details.css('marginBottom'));
			
			$pp_details = $pp_details.clone().addClass(settings.theme).width(width).appendTo($('body')).css({
				'position':'absolute',
				'top':-10000
			});
			detailsHeight += $pp_details.height();
			detailsHeight = (detailsHeight <= 34) ? 36 : detailsHeight; // Min-height for the details
			if($.browser.msie && $.browser.version==7) detailsHeight+=8;
			$pp_details.remove();
			
			// Get the titles height, to do so, I need to clone it since it's invisible
			$pp_title = $pp_pic_holder.find('.ppt');
			$pp_title.width(width);
			titleHeight = parseFloat($pp_title.css('marginTop')) + parseFloat($pp_title.css('marginBottom'));
			$pp_title = $pp_title.clone().appendTo($('body')).css({
				'position':'absolute',
				'top':-10000
			});
			titleHeight += $pp_title.height();
			$pp_title.remove();
			
			// Get the container size, to resize the holder to the right dimensions
			pp_contentHeight = height + detailsHeight;
			pp_contentWidth = width;
			pp_containerHeight = pp_contentHeight + titleHeight + $pp_pic_holder.find('.pp_top').height() + $pp_pic_holder.find('.pp_bottom').height();
			pp_containerWidth = width;
		}
	
		function _getFileType(itemSrc){
			if (itemSrc.match(/youtube\.com\/watch/i) || itemSrc.match(/youtu\.be/i)) {
				return 'youtube';
			}else if (itemSrc.match(/vimeo\.com/i)) {
				return 'vimeo';
			}else if(itemSrc.match(/\b.mov\b/i)){ 
				return 'quicktime';
			}else if(itemSrc.match(/\b.swf\b/i)){
				return 'flash';
			}else if(itemSrc.match(/\biframe=true\b/i)){
				return 'iframe';
			}else if(itemSrc.match(/\bajax=true\b/i)){
				return 'ajax';
			}else if(itemSrc.match(/\bcustom=true\b/i)){
				return 'custom';
			}else if(itemSrc.substr(0,1) == '#'){
				return 'inline';
			}else{
				return 'image';
			};
		};
	
		function _center_overlay(){
			if(doresize && typeof $pp_pic_holder != 'undefined') {
				scroll_pos = _get_scroll();
				contentHeight = $pp_pic_holder.height(), contentwidth = $pp_pic_holder.width();

				projectedTop = (windowHeight/2) + scroll_pos['scrollTop'] - (contentHeight/2);
				if(projectedTop < 0) projectedTop = 0;
				
				if(contentHeight > windowHeight)
					return;

				$pp_pic_holder.css({
					'top': projectedTop,
					'left': (windowWidth/2) + scroll_pos['scrollLeft'] - (contentwidth/2)
				});
			};
		};
	
		function _get_scroll(){
			if (self.pageYOffset) {
				return {scrollTop:self.pageYOffset,scrollLeft:self.pageXOffset};
			} else if (document.documentElement && document.documentElement.scrollTop) { // Explorer 6 Strict
				return {scrollTop:document.documentElement.scrollTop,scrollLeft:document.documentElement.scrollLeft};
			} else if (document.body) {// all other Explorers
				return {scrollTop:document.body.scrollTop,scrollLeft:document.body.scrollLeft};
			};
		};
	
		function _resize_overlay() {
			windowHeight = $(window).height(), windowWidth = $(window).width();
			
			if(typeof $pp_overlay != "undefined") $pp_overlay.height($(document).height()).width(windowWidth);
		};
	
		function _insert_gallery(){
			if(isSet && settings.overlay_gallery && _getFileType(pp_images[set_position])=="image" && (settings.ie6_fallback && !($.browser.msie && parseInt($.browser.version) == 6))) {
				itemWidth = 52+5; // 52 beign the thumb width, 5 being the right margin.
				navWidth = (settings.theme == "facebook" || settings.theme == "pp_default") ? 50 : 30; // Define the arrow width depending on the theme
				
				itemsPerPage = Math.floor((pp_dimensions['containerWidth'] - 100 - navWidth) / itemWidth);
				itemsPerPage = (itemsPerPage < pp_images.length) ? itemsPerPage : pp_images.length;
				totalPage = Math.ceil(pp_images.length / itemsPerPage) - 1;

				// Hide the nav in the case there's no need for links
				if(totalPage == 0){
					navWidth = 0; // No nav means no width!
					$pp_gallery.find('.pp_arrow_next,.pp_arrow_previous').hide();
				}else{
					$pp_gallery.find('.pp_arrow_next,.pp_arrow_previous').show();
				};

				galleryWidth = itemsPerPage * itemWidth;
				fullGalleryWidth = pp_images.length * itemWidth;
				
				// Set the proper width to the gallery items
				$pp_gallery
					.css('margin-left',-((galleryWidth/2) + (navWidth/2)))
					.find('div:first').width(galleryWidth+5)
					.find('ul').width(fullGalleryWidth)
					.find('li.selected').removeClass('selected');
				
				goToPage = (Math.floor(set_position/itemsPerPage) < totalPage) ? Math.floor(set_position/itemsPerPage) : totalPage;

				$.prettyPhoto.changeGalleryPage(goToPage);
				
				$pp_gallery_li.filter(':eq('+set_position+')').addClass('selected');
			}else{
				$pp_pic_holder.find('.pp_content').unbind('mouseenter mouseleave');
				// $pp_gallery.hide();
			}
		}
	
		function _build_overlay(caller){
			// Inject Social Tool markup into General markup
			if(settings.social_tools)
				facebook_like_link = settings.social_tools.replace('{location_href}', encodeURIComponent(location.href)); 

			settings.markup=settings.markup.replace('{pp_social}',(settings.social_tools)?facebook_like_link:''); 
			
			$('body').append(settings.markup); // Inject the markup
			
			$pp_pic_holder = $('.pp_pic_holder') , $ppt = $('.ppt'), $pp_overlay = $('div.pp_overlay'); // Set my global selectors
			
			// Inject the inline gallery!
			if(isSet && settings.overlay_gallery) {
				currentGalleryPage = 0;
				toInject = "";
				for (var i=0; i < pp_images.length; i++) {
					if(!pp_images[i].match(/\b(jpg|jpeg|png|gif)\b/gi)){
						classname = 'default';
						img_src = '';
					}else{
						classname = '';
						img_src = pp_images[i];
					}
					toInject += "<li class='"+classname+"'><a href='#'><img src='" + img_src + "' width='50' alt='' /></a></li>";
				};
				
				toInject = settings.gallery_markup.replace(/{gallery}/g,toInject);
				
				$pp_pic_holder.find('#pp_full_res').after(toInject);
				
				$pp_gallery = $('.pp_pic_holder .pp_gallery'), $pp_gallery_li = $pp_gallery.find('li'); // Set the gallery selectors
				
				$pp_gallery.find('.pp_arrow_next').click(function(){
					$.prettyPhoto.changeGalleryPage('next');
					$.prettyPhoto.stopSlideshow();
					return false;
				});
				
				$pp_gallery.find('.pp_arrow_previous').click(function(){
					$.prettyPhoto.changeGalleryPage('previous');
					$.prettyPhoto.stopSlideshow();
					return false;
				});
				
				$pp_pic_holder.find('.pp_content').hover(
					function(){
						$pp_pic_holder.find('.pp_gallery:not(.disabled)').fadeIn();
					},
					function(){
						$pp_pic_holder.find('.pp_gallery:not(.disabled)').fadeOut();
					});

				itemWidth = 52+5; // 52 beign the thumb width, 5 being the right margin.
				$pp_gallery_li.each(function(i){
					$(this)
						.find('a')
						.click(function(){
							$.prettyPhoto.changePage(i);
							$.prettyPhoto.stopSlideshow();
							return false;
						});
				});
			};
			
			
			// Inject the play/pause if it's a slideshow
			if(settings.slideshow){
				$pp_pic_holder.find('.pp_nav').prepend('<a href="#" class="pp_play">Play</a>')
				$pp_pic_holder.find('.pp_nav .pp_play').click(function(){
					$.prettyPhoto.startSlideshow();
					return false;
				});
			}
			
			$pp_pic_holder.attr('class','pp_pic_holder ' + settings.theme); // Set the proper theme
			
			$pp_overlay
				.css({
					'opacity':0,
					'height':$(document).height(),
					'width':$(window).width()
					})
				.bind('click',function(){
					if(!settings.modal) $.prettyPhoto.close();
				});

			$('a.pp_close').bind('click',function(){ $.prettyPhoto.close(); return false; });

			$('a.pp_expand').bind('click',function(e){
				// Expand the image
				if($(this).hasClass('pp_expand')){
					$(this).removeClass('pp_expand').addClass('pp_contract');
					doresize = false;
				}else{
					$(this).removeClass('pp_contract').addClass('pp_expand');
					doresize = true;
				};
			
				_hideContent(function(){ $.prettyPhoto.open(); });
		
				return false;
			});
		
			$pp_pic_holder.find('.pp_previous, .pp_nav .pp_arrow_previous').bind('click',function(){
				$.prettyPhoto.changePage('previous');
				$.prettyPhoto.stopSlideshow();
				return false;
			});
		
			$pp_pic_holder.find('.pp_next, .pp_nav .pp_arrow_next').bind('click',function(){
				$.prettyPhoto.changePage('next');
				$.prettyPhoto.stopSlideshow();
				return false;
			});
			
			_center_overlay(); // Center it
		};

		if(!pp_alreadyInitialized && getHashtag()){
			pp_alreadyInitialized = true;
			
			// Grab the rel index to trigger the click on the correct element
			hashIndex = getHashtag();
			hashRel = hashIndex;
			hashIndex = hashIndex.substring(hashIndex.indexOf('/')+1,hashIndex.length-1);
			hashRel = hashRel.substring(0,hashRel.indexOf('/'));

			// Little timeout to make sure all the prettyPhoto initialize scripts has been run.
			// Useful in the event the page contain several init scripts.
			setTimeout(function(){ $("a[rel^='"+hashRel+"']:eq("+hashIndex+")").trigger('click'); },50);
		}
		
		return this.unbind('click.prettyphoto').bind('click.prettyphoto',$.prettyPhoto.initialize); // Return the jQuery object for chaining. The unbind method is used to avoid click conflict when the plugin is called more than once
	};
	
	function getHashtag(){
		url = location.href;
		hashtag = (url.indexOf('#!') != -1) ? decodeURI(url.substring(url.indexOf('#!')+2,url.length)) : false;
		return hashtag;
	};
	
	function setHashtag(){
		if(typeof theRel == 'undefined') return; // theRel is set on normal calls, it's impossible to deeplink using the API
		location.hash = '!' + theRel + '/'+rel_index+'/';
	};
	
	function clearHashtag(){
		// Clear the hashtag only if it was set by prettyPhoto
		url = location.href;
		hashtag = (url.indexOf('#!prettyPhoto')) ? true : false;
		if(hashtag) location.hash = "!prettyPhoto";
	}
	
	function getParam(name,url){
	  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	  var regexS = "[\\?&]"+name+"=([^&#]*)";
	  var regex = new RegExp( regexS );
	  var results = regex.exec( url );
	  return ( results == null ) ? "" : results[1];
	}
	
})(jQuery);

var pp_alreadyInitialized = false; 

/**
 * Background Position Plugin
 * @author Alexander Farkas
 * v. 1.22
 */

(function($) {
	if(!document.defaultView || !document.defaultView.getComputedStyle){ // IE6-IE8
		var oldCurCSS = $.curCSS;
		$.curCSS = function(elem, name, force){
			if(name === 'background-position'){
				name = 'backgroundPosition';
			}
			if(name !== 'backgroundPosition' || !elem.currentStyle || elem.currentStyle[ name ]){
				return oldCurCSS.apply(this, arguments);
			}
			var style = elem.style;
			if ( !force && style && style[ name ] ){
				return style[ name ];
			}
			return oldCurCSS(elem, 'backgroundPositionX', force) +' '+ oldCurCSS(elem, 'backgroundPositionY', force);
		};
	}
	
	var oldAnim = $.fn.animate;
	$.fn.animate = function(prop){
		if('background-position' in prop){
			prop.backgroundPosition = prop['background-position'];
			delete prop['background-position'];
		}
		if('backgroundPosition' in prop){
			prop.backgroundPosition = '('+ prop.backgroundPosition;
		}
		return oldAnim.apply(this, arguments);
	};
	
	function toArray(strg){
		strg = strg.replace(/left|top/g,'0px');
		strg = strg.replace(/right|bottom/g,'100%');
		strg = strg.replace(/([0-9\.]+)(\s|\)|$)/g,"$1px$2");
		var res = strg.match(/(-?[0-9\.]+)(px|\%|em|pt)\s(-?[0-9\.]+)(px|\%|em|pt)/);
		return [parseFloat(res[1],10),res[2],parseFloat(res[3],10),res[4]];
	}
	
	$.fx.step. backgroundPosition = function(fx) {
		if (!fx.bgPosReady) {
			var start = $.curCSS(fx.elem,'backgroundPosition');
			if(!start){//FF2 no inline-style fallback
				start = '0px 0px';
			}
			
			start = toArray(start);
			fx.start = [start[0],start[2]];
			var end = toArray(fx.end);
			fx.end = [end[0],end[2]];
			
			fx.unit = [end[1],end[3]];
			fx.bgPosReady = true;
		}
		//return;
		var nowPosX = [];
		nowPosX[0] = ((fx.end[0] - fx.start[0]) * fx.pos) + fx.start[0] + fx.unit[0];
		nowPosX[1] = ((fx.end[1] - fx.start[1]) * fx.pos) + fx.start[1] + fx.unit[1];           
		fx.elem.style.backgroundPosition = nowPosX[0]+' '+nowPosX[1];

	};
})(jQuery);


 /*!
 * jQuery Cycle Plugin (with Transition Definitions)
 * Examples and documentation at: http://jquery.malsup.com/cycle/
 * Copyright (c) 2007-2010 M. Alsup
 * Version: 2.9998 (27-OCT-2011)
 * Dual licensed under the MIT and GPL licenses.
 * http://jquery.malsup.com/license.html
 * Requires: jQuery v1.3.2 or later
 */
;(function($, undefined) {

	var ver = '2.9998';

	// if $.support is not defined (pre jQuery 1.3) add what I need
	if ($.support == undefined) {
		$.support = {
			opacity: !($.browser.msie)
		};
	}

	function debug(s) {
		$.fn.cycle.debug && log(s);
	}		
	function log() {
		window.console && console.log && console.log('[cycle] ' + Array.prototype.join.call(arguments,' '));
	}
	$.expr[':'].paused = function(el) {
		return el.cyclePause;
	}


	// the options arg can be...
	//   a number  - indicates an immediate transition should occur to the given slide index
	//   a string  - 'pause', 'resume', 'toggle', 'next', 'prev', 'stop', 'destroy' or the name of a transition effect (ie, 'fade', 'zoom', etc)
	//   an object - properties to control the slideshow
	//
	// the arg2 arg can be...
	//   the name of an fx (only used in conjunction with a numeric value for 'options')
	//   the value true (only used in first arg == 'resume') and indicates
	//	 that the resume should occur immediately (not wait for next timeout)

	$.fn.cycle = function(options, arg2) {
		var o = { s: this.selector, c: this.context };

		// in 1.3+ we can fix mistakes with the ready state
		if (this.length === 0 && options != 'stop') {
			if (!$.isReady && o.s) {
				log('DOM not ready, queuing slideshow');
				$(function() {
					$(o.s,o.c).cycle(options,arg2);
				});
				return this;
			}
			// is your DOM ready?  http://docs.jquery.com/Tutorials:Introducing_$(document).ready()
			log('terminating; zero elements found by selector' + ($.isReady ? '' : ' (DOM not ready)'));
			return this;
		}

		// iterate the matched nodeset
		return this.each(function() {
			var opts = handleArguments(this, options, arg2);
			if (opts === false)
				return;

			opts.updateActivePagerLink = opts.updateActivePagerLink || $.fn.cycle.updateActivePagerLink;
			
			// stop existing slideshow for this container (if there is one)
			if (this.cycleTimeout)
				clearTimeout(this.cycleTimeout);
			this.cycleTimeout = this.cyclePause = 0;

			var $cont = $(this);
			var $slides = opts.slideExpr ? $(opts.slideExpr, this) : $cont.children();
			var els = $slides.get();

			var opts2 = buildOptions($cont, $slides, els, opts, o);
			if (opts2 === false)
				return;

			if (els.length < 2) {
				log('terminating; too few slides: ' + els.length);
				return;
			}

			var startTime = opts2.continuous ? 10 : getTimeout(els[opts2.currSlide], els[opts2.nextSlide], opts2, !opts2.backwards);

			// if it's an auto slideshow, kick it off
			if (startTime) {
				startTime += (opts2.delay || 0);
				if (startTime < 10)
					startTime = 10;
				debug('first timeout: ' + startTime);
				this.cycleTimeout = setTimeout(function(){go(els,opts2,0,!opts.backwards)}, startTime);
			}
		});
	};

	function triggerPause(cont, byHover, onPager) {
		var opts = $(cont).data('cycle.opts');
		var paused = !!cont.cyclePause;
		if (paused && opts.paused)
			opts.paused(cont, opts, byHover, onPager);
		else if (!paused && opts.resumed)
			opts.resumed(cont, opts, byHover, onPager);
	}

	// process the args that were passed to the plugin fn
	function handleArguments(cont, options, arg2) {
		if (cont.cycleStop == undefined)
			cont.cycleStop = 0;
		if (options === undefined || options === null)
			options = {};
		if (options.constructor == String) {
			switch(options) {
			case 'destroy':
			case 'stop':
				var opts = $(cont).data('cycle.opts');
				if (!opts)
					return false;
				cont.cycleStop++; // callbacks look for change
				if (cont.cycleTimeout)
					clearTimeout(cont.cycleTimeout);
				cont.cycleTimeout = 0;
				opts.elements && $(opts.elements).stop();
				$(cont).removeData('cycle.opts');
				if (options == 'destroy')
					destroy(opts);
				return false;
			case 'toggle':
				cont.cyclePause = (cont.cyclePause === 1) ? 0 : 1;
				checkInstantResume(cont.cyclePause, arg2, cont);
				triggerPause(cont);
				return false;
			case 'pause':
				cont.cyclePause = 1;
				triggerPause(cont);
				return false;
			case 'resume':
				cont.cyclePause = 0;
				checkInstantResume(false, arg2, cont);
				triggerPause(cont);
				return false;
			case 'prev':
			case 'next':
				var opts = $(cont).data('cycle.opts');
				if (!opts) {
					log('options not found, "prev/next" ignored');
					return false;
				}
				$.fn.cycle[options](opts);
				return false;
			default:
				options = { fx: options };
			};
			return options;
		}
		else if (options.constructor == Number) {
			// go to the requested slide
			var num = options;
			options = $(cont).data('cycle.opts');
			if (!options) {
				log('options not found, can not advance slide');
				return false;
			}
			if (num < 0 || num >= options.elements.length) {
				log('invalid slide index: ' + num);
				return false;
			}
			options.nextSlide = num;
			if (cont.cycleTimeout) {
				clearTimeout(cont.cycleTimeout);
				cont.cycleTimeout = 0;
			}
			if (typeof arg2 == 'string')
				options.oneTimeFx = arg2;
			go(options.elements, options, 1, num >= options.currSlide);
			return false;
		}
		return options;
		
		function checkInstantResume(isPaused, arg2, cont) {
			if (!isPaused && arg2 === true) { // resume now!
				var options = $(cont).data('cycle.opts');
				if (!options) {
					log('options not found, can not resume');
					return false;
				}
				if (cont.cycleTimeout) {
					clearTimeout(cont.cycleTimeout);
					cont.cycleTimeout = 0;
				}
				go(options.elements, options, 1, !options.backwards);
			}
		}
	};

	function removeFilter(el, opts) {
		if (!$.support.opacity && opts.cleartype && el.style.filter) {
			try { el.style.removeAttribute('filter'); }
			catch(smother) {} // handle old opera versions
		}
	};

	// unbind event handlers
	function destroy(opts) {
		if (opts.next)
			$(opts.next).unbind(opts.prevNextEvent);
		if (opts.prev)
			$(opts.prev).unbind(opts.prevNextEvent);
		
		if (opts.pager || opts.pagerAnchorBuilder)
			$.each(opts.pagerAnchors || [], function() {
				this.unbind().remove();
			});
		opts.pagerAnchors = null;
		if (opts.destroy) // callback
			opts.destroy(opts);
	};

	// one-time initialization
	function buildOptions($cont, $slides, els, options, o) {
		var startingSlideSpecified;
		// support metadata plugin (v1.0 and v2.0)
		var opts = $.extend({}, $.fn.cycle.defaults, options || {}, $.metadata ? $cont.metadata() : $.meta ? $cont.data() : {});
		var meta = $.isFunction($cont.data) ? $cont.data(opts.metaAttr) : null;
		if (meta)
			opts = $.extend(opts, meta);
		if (opts.autostop)
			opts.countdown = opts.autostopCount || els.length;

		var cont = $cont[0];
		$cont.data('cycle.opts', opts);
		opts.$cont = $cont;
		opts.stopCount = cont.cycleStop;
		opts.elements = els;
		opts.before = opts.before ? [opts.before] : [];
		opts.after = opts.after ? [opts.after] : [];

		// push some after callbacks
		if (!$.support.opacity && opts.cleartype)
			opts.after.push(function() { removeFilter(this, opts); });
		if (opts.continuous)
			opts.after.push(function() { go(els,opts,0,!opts.backwards); });

		saveOriginalOpts(opts);

		// clearType corrections
		if (!$.support.opacity && opts.cleartype && !opts.cleartypeNoBg)
			clearTypeFix($slides);

		// container requires non-static position so that slides can be position within
		if ($cont.css('position') == 'static')
			$cont.css('position', 'relative');
		if (opts.width)
			$cont.width(opts.width);
		if (opts.height && opts.height != 'auto')
			$cont.height(opts.height);

		if (opts.startingSlide != undefined) {
			opts.startingSlide = parseInt(opts.startingSlide,10);
			if (opts.startingSlide >= els.length || opts.startSlide < 0)
				opts.startingSlide = 0; // catch bogus input
			else 
				startingSlideSpecified = true;
		}
		else if (opts.backwards)
			opts.startingSlide = els.length - 1;
		else
			opts.startingSlide = 0;

		// if random, mix up the slide array
		if (opts.random) {
			opts.randomMap = [];
			for (var i = 0; i < els.length; i++)
				opts.randomMap.push(i);
			opts.randomMap.sort(function(a,b) {return Math.random() - 0.5;});
			if (startingSlideSpecified) {
				// try to find the specified starting slide and if found set start slide index in the map accordingly
				for ( var cnt = 0; cnt < els.length; cnt++ ) {
					if ( opts.startingSlide == opts.randomMap[cnt] ) {
						opts.randomIndex = cnt;
					}
				}
			}
			else {
				opts.randomIndex = 1;
				opts.startingSlide = opts.randomMap[1];
			}
		}
		else if (opts.startingSlide >= els.length)
			opts.startingSlide = 0; // catch bogus input
		opts.currSlide = opts.startingSlide || 0;
		var first = opts.startingSlide;

		// set position and zIndex on all the slides
		$slides.css({position: 'absolute', top:0, left:0}).hide().each(function(i) {
			var z;
			if (opts.backwards)
				z = first ? i <= first ? els.length + (i-first) : first-i : els.length-i;
			else
				z = first ? i >= first ? els.length - (i-first) : first-i : els.length-i;
			$(this).css('z-index', z)
		});

		// make sure first slide is visible
		$(els[first]).css('opacity',1).show(); // opacity bit needed to handle restart use case
		removeFilter(els[first], opts);

		// stretch slides
		if (opts.fit) {
			if (!opts.aspect) {
				if (opts.width)
					$slides.width(opts.width);
				if (opts.height && opts.height != 'auto')
					$slides.height(opts.height);
			} else {
				$slides.each(function(){
					var $slide = $(this);
					var ratio = (opts.aspect === true) ? $slide.width()/$slide.height() : opts.aspect;
					if( opts.width && $slide.width() != opts.width ) {
						$slide.width( opts.width );
						$slide.height( opts.width / ratio );
					}

					if( opts.height && $slide.height() < opts.height ) {
						$slide.height( opts.height );
						$slide.width( opts.height * ratio );
					}
				});
			}
		}

		if (opts.center && ((!opts.fit) || opts.aspect)) {
			$slides.each(function(){
				var $slide = $(this);
				$slide.css({
					"margin-left": opts.width ?
						((opts.width - $slide.width()) / 2) + "px" :
						0,
					"margin-top": opts.height ?
						((opts.height - $slide.height()) / 2) + "px" :
						0
				});
			});
		}

		if (opts.center && !opts.fit && !opts.slideResize) {
			$slides.each(function(){
				var $slide = $(this);
				$slide.css({
					"margin-left": opts.width ? ((opts.width - $slide.width()) / 2) + "px" : 0,
					"margin-top": opts.height ? ((opts.height - $slide.height()) / 2) + "px" : 0
				});
			});
		}
			
		// stretch container
		var reshape = opts.containerResize && !$cont.innerHeight();
		if (reshape) { // do this only if container has no size http://tinyurl.com/da2oa9
			var maxw = 0, maxh = 0;
			for(var j=0; j < els.length; j++) {
				var $e = $(els[j]), e = $e[0], w = $e.outerWidth(), h = $e.outerHeight();
				if (!w) w = e.offsetWidth || e.width || $e.attr('width');
				if (!h) h = e.offsetHeight || e.height || $e.attr('height');
				maxw = w > maxw ? w : maxw;
				maxh = h > maxh ? h : maxh;
			}
			if (maxw > 0 && maxh > 0)
				$cont.css({width:maxw+'px',height:maxh+'px'});
		}

		var pauseFlag = false;  // https://github.com/malsup/cycle/issues/44
		if (opts.pause)
			$cont.hover(
				function(){
					pauseFlag = true;
					this.cyclePause++;
					triggerPause(cont, true);
				},
				function(){
					pauseFlag && this.cyclePause--;
					triggerPause(cont, true);
				}
			);

		if (supportMultiTransitions(opts) === false)
			return false;

		// apparently a lot of people use image slideshows without height/width attributes on the images.
		// Cycle 2.50+ requires the sizing info for every slide; this block tries to deal with that.
		var requeue = false;
		options.requeueAttempts = options.requeueAttempts || 0;
		$slides.each(function() {
			// try to get height/width of each slide
			var $el = $(this);
			this.cycleH = (opts.fit && opts.height) ? opts.height : ($el.height() || this.offsetHeight || this.height || $el.attr('height') || 0);
			this.cycleW = (opts.fit && opts.width) ? opts.width : ($el.width() || this.offsetWidth || this.width || $el.attr('width') || 0);

			if ( $el.is('img') ) {
				// sigh..  sniffing, hacking, shrugging...  this crappy hack tries to account for what browsers do when
				// an image is being downloaded and the markup did not include sizing info (height/width attributes);
				// there seems to be some "default" sizes used in this situation
				var loadingIE	= ($.browser.msie  && this.cycleW == 28 && this.cycleH == 30 && !this.complete);
				var loadingFF	= ($.browser.mozilla && this.cycleW == 34 && this.cycleH == 19 && !this.complete);
				var loadingOp	= ($.browser.opera && ((this.cycleW == 42 && this.cycleH == 19) || (this.cycleW == 37 && this.cycleH == 17)) && !this.complete);
				var loadingOther = (this.cycleH == 0 && this.cycleW == 0 && !this.complete);
				// don't requeue for images that are still loading but have a valid size
				if (loadingIE || loadingFF || loadingOp || loadingOther) {
					if (o.s && opts.requeueOnImageNotLoaded && ++options.requeueAttempts < 100) { // track retry count so we don't loop forever
						log(options.requeueAttempts,' - img slide not loaded, requeuing slideshow: ', this.src, this.cycleW, this.cycleH);
						setTimeout(function() {$(o.s,o.c).cycle(options)}, opts.requeueTimeout);
						requeue = true;
						return false; // break each loop
					}
					else {
						log('could not determine size of image: '+this.src, this.cycleW, this.cycleH);
					}
				}
			}
			return true;
		});

		if (requeue)
			return false;

		opts.cssBefore = opts.cssBefore || {};
		opts.cssAfter = opts.cssAfter || {};
		opts.cssFirst = opts.cssFirst || {};
		opts.animIn = opts.animIn || {};
		opts.animOut = opts.animOut || {};

		$slides.not(':eq('+first+')').css(opts.cssBefore);
		$($slides[first]).css(opts.cssFirst);

		if (opts.timeout) {
			opts.timeout = parseInt(opts.timeout,10);
			// ensure that timeout and speed settings are sane
			if (opts.speed.constructor == String)
				opts.speed = $.fx.speeds[opts.speed] || parseInt(opts.speed,10);
			if (!opts.sync)
				opts.speed = opts.speed / 2;
			
			var buffer = opts.fx == 'none' ? 0 : opts.fx == 'shuffle' ? 500 : 250;
			while((opts.timeout - opts.speed) < buffer) // sanitize timeout
				opts.timeout += opts.speed;
		}
		if (opts.easing)
			opts.easeIn = opts.easeOut = opts.easing;
		if (!opts.speedIn)
			opts.speedIn = opts.speed;
		if (!opts.speedOut)
			opts.speedOut = opts.speed;

		opts.slideCount = els.length;
		opts.currSlide = opts.lastSlide = first;
		if (opts.random) {
			if (++opts.randomIndex == els.length)
				opts.randomIndex = 0;
			opts.nextSlide = opts.randomMap[opts.randomIndex];
		}
		else if (opts.backwards)
			opts.nextSlide = opts.startingSlide == 0 ? (els.length-1) : opts.startingSlide-1;
		else
			opts.nextSlide = opts.startingSlide >= (els.length-1) ? 0 : opts.startingSlide+1;

		// run transition init fn
		if (!opts.multiFx) {
			var init = $.fn.cycle.transitions[opts.fx];
			if ($.isFunction(init))
				init($cont, $slides, opts);
			else if (opts.fx != 'custom' && !opts.multiFx) {
				log('unknown transition: ' + opts.fx,'; slideshow terminating');
				return false;
			}
		}

		// fire artificial events
		var e0 = $slides[first];
		if (!opts.skipInitializationCallbacks) {
			if (opts.before.length)
				opts.before[0].apply(e0, [e0, e0, opts, true]);
			if (opts.after.length)
				opts.after[0].apply(e0, [e0, e0, opts, true]);
		}
		if (opts.next)
			$(opts.next).bind(opts.prevNextEvent,function(){return advance(opts,1)});
		if (opts.prev)
			$(opts.prev).bind(opts.prevNextEvent,function(){return advance(opts,0)});
		if (opts.pager || opts.pagerAnchorBuilder)
			buildPager(els,opts);

		exposeAddSlide(opts, els);

		return opts;
	};

	// save off original opts so we can restore after clearing state
	function saveOriginalOpts(opts) {
		opts.original = { before: [], after: [] };
		opts.original.cssBefore = $.extend({}, opts.cssBefore);
		opts.original.cssAfter  = $.extend({}, opts.cssAfter);
		opts.original.animIn	= $.extend({}, opts.animIn);
		opts.original.animOut   = $.extend({}, opts.animOut);
		$.each(opts.before, function() { opts.original.before.push(this); });
		$.each(opts.after,  function() { opts.original.after.push(this); });
	};

	function supportMultiTransitions(opts) {
		var i, tx, txs = $.fn.cycle.transitions;
		// look for multiple effects
		if (opts.fx.indexOf(',') > 0) {
			opts.multiFx = true;
			opts.fxs = opts.fx.replace(/\s*/g,'').split(',');
			// discard any bogus effect names
			for (i=0; i < opts.fxs.length; i++) {
				var fx = opts.fxs[i];
				tx = txs[fx];
				if (!tx || !txs.hasOwnProperty(fx) || !$.isFunction(tx)) {
					log('discarding unknown transition: ',fx);
					opts.fxs.splice(i,1);
					i--;
				}
			}
			// if we have an empty list then we threw everything away!
			if (!opts.fxs.length) {
				log('No valid transitions named; slideshow terminating.');
				return false;
			}
		}
		else if (opts.fx == 'all') {  // auto-gen the list of transitions
			opts.multiFx = true;
			opts.fxs = [];
			for (p in txs) {
				tx = txs[p];
				if (txs.hasOwnProperty(p) && $.isFunction(tx))
					opts.fxs.push(p);
			}
		}
		if (opts.multiFx && opts.randomizeEffects) {
			// munge the fxs array to make effect selection random
			var r1 = Math.floor(Math.random() * 20) + 30;
			for (i = 0; i < r1; i++) {
				var r2 = Math.floor(Math.random() * opts.fxs.length);
				opts.fxs.push(opts.fxs.splice(r2,1)[0]);
			}
			debug('randomized fx sequence: ',opts.fxs);
		}
		return true;
	};

	// provide a mechanism for adding slides after the slideshow has started
	function exposeAddSlide(opts, els) {
		opts.addSlide = function(newSlide, prepend) {
			var $s = $(newSlide), s = $s[0];
			if (!opts.autostopCount)
				opts.countdown++;
			els[prepend?'unshift':'push'](s);
			if (opts.els)
				opts.els[prepend?'unshift':'push'](s); // shuffle needs this
			opts.slideCount = els.length;

			// add the slide to the random map and resort
			if (opts.random) {
				opts.randomMap.push(opts.slideCount-1);
				opts.randomMap.sort(function(a,b) {return Math.random() - 0.5;});
			}

			$s.css('position','absolute');
			$s[prepend?'prependTo':'appendTo'](opts.$cont);

			if (prepend) {
				opts.currSlide++;
				opts.nextSlide++;
			}

			if (!$.support.opacity && opts.cleartype && !opts.cleartypeNoBg)
				clearTypeFix($s);

			if (opts.fit && opts.width)
				$s.width(opts.width);
			if (opts.fit && opts.height && opts.height != 'auto')
				$s.height(opts.height);
			s.cycleH = (opts.fit && opts.height) ? opts.height : $s.height();
			s.cycleW = (opts.fit && opts.width) ? opts.width : $s.width();

			$s.css(opts.cssBefore);

			if (opts.pager || opts.pagerAnchorBuilder)
				$.fn.cycle.createPagerAnchor(els.length-1, s, $(opts.pager), els, opts);

			if ($.isFunction(opts.onAddSlide))
				opts.onAddSlide($s);
			else
				$s.hide(); // default behavior
		};
	}

	// reset internal state; we do this on every pass in order to support multiple effects
	$.fn.cycle.resetState = function(opts, fx) {
		fx = fx || opts.fx;
		opts.before = []; opts.after = [];
		opts.cssBefore = $.extend({}, opts.original.cssBefore);
		opts.cssAfter  = $.extend({}, opts.original.cssAfter);
		opts.animIn	= $.extend({}, opts.original.animIn);
		opts.animOut   = $.extend({}, opts.original.animOut);
		opts.fxFn = null;
		$.each(opts.original.before, function() { opts.before.push(this); });
		$.each(opts.original.after,  function() { opts.after.push(this); });

		// re-init
		var init = $.fn.cycle.transitions[fx];
		if ($.isFunction(init))
			init(opts.$cont, $(opts.elements), opts);
	};

	// this is the main engine fn, it handles the timeouts, callbacks and slide index mgmt
	function go(els, opts, manual, fwd) {
		// opts.busy is true if we're in the middle of an animation
		if (manual && opts.busy && opts.manualTrump) {
			// let manual transitions requests trump active ones
			debug('manualTrump in go(), stopping active transition');
			$(els).stop(true,true);
			opts.busy = 0;
		}
		// don't begin another timeout-based transition if there is one active
		if (opts.busy) {
			debug('transition active, ignoring new tx request');
			return;
		}

		var p = opts.$cont[0], curr = els[opts.currSlide], next = els[opts.nextSlide];

		// stop cycling if we have an outstanding stop request
		if (p.cycleStop != opts.stopCount || p.cycleTimeout === 0 && !manual)
			return;

		// check to see if we should stop cycling based on autostop options
		if (!manual && !p.cyclePause && !opts.bounce &&
			((opts.autostop && (--opts.countdown <= 0)) ||
			(opts.nowrap && !opts.random && opts.nextSlide < opts.currSlide))) {
			if (opts.end)
				opts.end(opts);
			return;
		}

		// if slideshow is paused, only transition on a manual trigger
		var changed = false;
		if ((manual || !p.cyclePause) && (opts.nextSlide != opts.currSlide)) {
			changed = true;
			var fx = opts.fx;
			// keep trying to get the slide size if we don't have it yet
			curr.cycleH = curr.cycleH || $(curr).height();
			curr.cycleW = curr.cycleW || $(curr).width();
			next.cycleH = next.cycleH || $(next).height();
			next.cycleW = next.cycleW || $(next).width();

			// support multiple transition types
			if (opts.multiFx) {
				if (fwd && (opts.lastFx == undefined || ++opts.lastFx >= opts.fxs.length))
					opts.lastFx = 0;
				else if (!fwd && (opts.lastFx == undefined || --opts.lastFx < 0))
					opts.lastFx = opts.fxs.length - 1;
				fx = opts.fxs[opts.lastFx];
			}

			// one-time fx overrides apply to:  $('div').cycle(3,'zoom');
			if (opts.oneTimeFx) {
				fx = opts.oneTimeFx;
				opts.oneTimeFx = null;
			}

			$.fn.cycle.resetState(opts, fx);

			// run the before callbacks
			if (opts.before.length)
				$.each(opts.before, function(i,o) {
					if (p.cycleStop != opts.stopCount) return;
					o.apply(next, [curr, next, opts, fwd]);
				});

			// stage the after callacks
			var after = function() {
				opts.busy = 0;
				$.each(opts.after, function(i,o) {
					if (p.cycleStop != opts.stopCount) return;
					o.apply(next, [curr, next, opts, fwd]);
				});
				if (!p.cycleStop) {
					// queue next transition
					queueNext();
				}
			};

			debug('tx firing('+fx+'); currSlide: ' + opts.currSlide + '; nextSlide: ' + opts.nextSlide);
			
			// get ready to perform the transition
			opts.busy = 1;
			if (opts.fxFn) // fx function provided?
				opts.fxFn(curr, next, opts, after, fwd, manual && opts.fastOnEvent);
			else if ($.isFunction($.fn.cycle[opts.fx])) // fx plugin ?
				$.fn.cycle[opts.fx](curr, next, opts, after, fwd, manual && opts.fastOnEvent);
			else
				$.fn.cycle.custom(curr, next, opts, after, fwd, manual && opts.fastOnEvent);
		}
		else {
			queueNext();
		}

		if (changed || opts.nextSlide == opts.currSlide) {
			// calculate the next slide
			opts.lastSlide = opts.currSlide;
			if (opts.random) {
				opts.currSlide = opts.nextSlide;
				if (++opts.randomIndex == els.length) {
					opts.randomIndex = 0;
					opts.randomMap.sort(function(a,b) {return Math.random() - 0.5;});
				}
				opts.nextSlide = opts.randomMap[opts.randomIndex];
				if (opts.nextSlide == opts.currSlide)
					opts.nextSlide = (opts.currSlide == opts.slideCount - 1) ? 0 : opts.currSlide + 1;
			}
			else if (opts.backwards) {
				var roll = (opts.nextSlide - 1) < 0;
				if (roll && opts.bounce) {
					opts.backwards = !opts.backwards;
					opts.nextSlide = 1;
					opts.currSlide = 0;
				}
				else {
					opts.nextSlide = roll ? (els.length-1) : opts.nextSlide-1;
					opts.currSlide = roll ? 0 : opts.nextSlide+1;
				}
			}
			else { // sequence
				var roll = (opts.nextSlide + 1) == els.length;
				if (roll && opts.bounce) {
					opts.backwards = !opts.backwards;
					opts.nextSlide = els.length-2;
					opts.currSlide = els.length-1;
				}
				else {
					opts.nextSlide = roll ? 0 : opts.nextSlide+1;
					opts.currSlide = roll ? els.length-1 : opts.nextSlide-1;
				}
			}
		}
		if (changed && opts.pager)
			opts.updateActivePagerLink(opts.pager, opts.currSlide, opts.activePagerClass);
		
		function queueNext() {
			// stage the next transition
			var ms = 0, timeout = opts.timeout;
			if (opts.timeout && !opts.continuous) {
				ms = getTimeout(els[opts.currSlide], els[opts.nextSlide], opts, fwd);
			 if (opts.fx == 'shuffle')
				ms -= opts.speedOut;
		  }
			else if (opts.continuous && p.cyclePause) // continuous shows work off an after callback, not this timer logic
				ms = 10;
			if (ms > 0)
				p.cycleTimeout = setTimeout(function(){ go(els, opts, 0, !opts.backwards) }, ms);
		}
	};

	// invoked after transition
	$.fn.cycle.updateActivePagerLink = function(pager, currSlide, clsName) {
	   $(pager).each(function() {
		   $(this).children().removeClass(clsName).eq(currSlide).addClass(clsName);
	   });
	};

	// calculate timeout value for current transition
	function getTimeout(curr, next, opts, fwd) {
		if (opts.timeoutFn) {
			// call user provided calc fn
			var t = opts.timeoutFn.call(curr,curr,next,opts,fwd);
			while (opts.fx != 'none' && (t - opts.speed) < 250) // sanitize timeout
				t += opts.speed;
			debug('calculated timeout: ' + t + '; speed: ' + opts.speed);
			if (t !== false)
				return t;
		}
		return opts.timeout;
	};

	// expose next/prev function, caller must pass in state
	$.fn.cycle.next = function(opts) { advance(opts,1); };
	$.fn.cycle.prev = function(opts) { advance(opts,0);};

	// advance slide forward or back
	function advance(opts, moveForward) {
		var val = moveForward ? 1 : -1;
		var els = opts.elements;
		var p = opts.$cont[0], timeout = p.cycleTimeout;
		if (timeout) {
			clearTimeout(timeout);
			p.cycleTimeout = 0;
		}
		if (opts.random && val < 0) {
			// move back to the previously display slide
			opts.randomIndex--;
			if (--opts.randomIndex == -2)
				opts.randomIndex = els.length-2;
			else if (opts.randomIndex == -1)
				opts.randomIndex = els.length-1;
			opts.nextSlide = opts.randomMap[opts.randomIndex];
		}
		else if (opts.random) {
			opts.nextSlide = opts.randomMap[opts.randomIndex];
		}
		else {
			opts.nextSlide = opts.currSlide + val;
			if (opts.nextSlide < 0) {
				if (opts.nowrap) return false;
				opts.nextSlide = els.length - 1;
			}
			else if (opts.nextSlide >= els.length) {
				if (opts.nowrap) return false;
				opts.nextSlide = 0;
			}
		}

		var cb = opts.onPrevNextEvent || opts.prevNextClick; // prevNextClick is deprecated
		if ($.isFunction(cb))
			cb(val > 0, opts.nextSlide, els[opts.nextSlide]);
		go(els, opts, 1, moveForward);
		return false;
	};

	var pagerWidth = 0;
	function buildPager(els, opts) {
		var $p = $(opts.pager);
		$.each(els, function(i,o) {
			$.fn.cycle.createPagerAnchor(i,o,$p,els,opts);
		});
		opts.updateActivePagerLink(opts.pager, opts.startingSlide, opts.activePagerClass);
		opts.pager.width(pagerWidth);
	};

	$.fn.cycle.createPagerAnchor = function(i, el, $p, els, opts) {
		var a;
		if ($.isFunction(opts.pagerAnchorBuilder)) {
			a = opts.pagerAnchorBuilder(i,el);
			debug('pagerAnchorBuilder('+i+', el) returned: ' + a);
		}
		else
			a = '<a href="#">'+(i+1)+'</a>';
			
		if (!a)
			return;
		var $a = $(a);
		// don't reparent if anchor is in the dom
		if ($a.parents('body').length === 0) {
			var arr = [];
			if ($p.length > 1) {
				$p.each(function() {
					var $clone = $a.clone(true);
					$(this).append($clone);
					arr.push($clone[0]);
				});
				$a = $(arr);
			}
			else {
				$a.appendTo($p);
			}
		}

		//calculate pager width
		pagerWidth += parseInt($a.css('marginLeft').slice(0,1))+$a.width();
		
		opts.pagerAnchors =  opts.pagerAnchors || [];
		opts.pagerAnchors.push($a);
		
		var pagerFn = function(e) {
			e.preventDefault();
			opts.nextSlide = i;
			var p = opts.$cont[0], timeout = p.cycleTimeout;
			if (timeout) {
				clearTimeout(timeout);
				p.cycleTimeout = 0;
			}
			var cb = opts.onPagerEvent || opts.pagerClick; // pagerClick is deprecated
			if ($.isFunction(cb))
				cb(opts.nextSlide, els[opts.nextSlide]);
			go(els,opts,1,opts.currSlide < i); // trigger the trans
	//		return false; // <== allow bubble
		}
		
		if ( /mouseenter|mouseover/i.test(opts.pagerEvent) ) {
			$a.hover(pagerFn, function(){/* no-op */} );
		}
		else {
			$a.bind(opts.pagerEvent, pagerFn);
		}
		
		if ( ! /^click/.test(opts.pagerEvent) && !opts.allowPagerClickBubble)
			$a.bind('click.cycle', function(){return false;}); // suppress click
		
		var cont = opts.$cont[0];
		var pauseFlag = false; // https://github.com/malsup/cycle/issues/44
		if (opts.pauseOnPagerHover) {
			$a.hover(
				function() { 
					pauseFlag = true;
					cont.cyclePause++; 
					triggerPause(cont,true,true);
				}, function() { 
					pauseFlag && cont.cyclePause--; 
					triggerPause(cont,true,true);
				} 
			);
		}
	};

	// helper fn to calculate the number of slides between the current and the next
	$.fn.cycle.hopsFromLast = function(opts, fwd) {
		var hops, l = opts.lastSlide, c = opts.currSlide;
		if (fwd)
			hops = c > l ? c - l : opts.slideCount - l;
		else
			hops = c < l ? l - c : l + opts.slideCount - c;
		return hops;
	};

	// fix clearType problems in ie6 by setting an explicit bg color
	// (otherwise text slides look horrible during a fade transition)
	function clearTypeFix($slides) {
		debug('applying clearType background-color hack');
		function hex(s) {
			s = parseInt(s,10).toString(16);
			return s.length < 2 ? '0'+s : s;
		};
		function getBg(e) {
			for ( ; e && e.nodeName.toLowerCase() != 'html'; e = e.parentNode) {
				var v = $.css(e,'background-color');
				if (v && v.indexOf('rgb') >= 0 ) {
					var rgb = v.match(/\d+/g);
					return '#'+ hex(rgb[0]) + hex(rgb[1]) + hex(rgb[2]);
				}
				if (v && v != 'transparent')
					return v;
			}
			return '#ffffff';
		};
		$slides.each(function() { $(this).css('background-color', getBg(this)); });
	};

	// reset common props before the next transition
	$.fn.cycle.commonReset = function(curr,next,opts,w,h,rev) {
		$(opts.elements).not(curr).hide();
		if (typeof opts.cssBefore.opacity == 'undefined')
			opts.cssBefore.opacity = 1;
		opts.cssBefore.display = 'block';
		if (opts.slideResize && w !== false && next.cycleW > 0)
			opts.cssBefore.width = next.cycleW;
		if (opts.slideResize && h !== false && next.cycleH > 0)
			opts.cssBefore.height = next.cycleH;
		opts.cssAfter = opts.cssAfter || {};
		opts.cssAfter.display = 'none';
		$(curr).css('zIndex',opts.slideCount + (rev === true ? 1 : 0));
		$(next).css('zIndex',opts.slideCount + (rev === true ? 0 : 1));
	};

	// the actual fn for effecting a transition
	$.fn.cycle.custom = function(curr, next, opts, cb, fwd, speedOverride) {
		var $l = $(curr), $n = $(next);
		var speedIn = opts.speedIn, speedOut = opts.speedOut, easeIn = opts.easeIn, easeOut = opts.easeOut;
		$n.css(opts.cssBefore);
		if (speedOverride) {
			if (typeof speedOverride == 'number')
				speedIn = speedOut = speedOverride;
			else
				speedIn = speedOut = 1;
			easeIn = easeOut = null;
		}
		var fn = function() {
			$n.animate(opts.animIn, speedIn, easeIn, function() {
				cb();
			});
		};
		$l.animate(opts.animOut, speedOut, easeOut, function() {
			$l.css(opts.cssAfter);
			if (!opts.sync) 
				fn();
		});
		if (opts.sync) fn();
	};

	// transition definitions - only fade is defined here, transition pack defines the rest
	$.fn.cycle.transitions = {
		fade: function($cont, $slides, opts) {
			$slides.not(':eq('+opts.currSlide+')').css('opacity',0);
			opts.before.push(function(curr,next,opts) {
				$.fn.cycle.commonReset(curr,next,opts);
				opts.cssBefore.opacity = 0;
			});
			opts.animIn	   = { opacity: 1 };
			opts.animOut   = { opacity: 0 };
			opts.cssBefore = { top: 0, left: 0 };
		}
	};

	$.fn.cycle.ver = function() { return ver; };

	// override these globally if you like (they are all optional)
	$.fn.cycle.defaults = {
		activePagerClass: 'selected', // class name used for the active pager link
		after:		   null,  // transition callback (scope set to element that was shown):  function(currSlideElement, nextSlideElement, options, forwardFlag)
		allowPagerClickBubble: false, // allows or prevents click event on pager anchors from bubbling
		animIn:		   null,  // properties that define how the slide animates in
		animOut:	   null,  // properties that define how the slide animates out
		aspect:		   false,  // preserve aspect ratio during fit resizing, cropping if necessary (must be used with fit option)
		autostop:	   0,	  // true to end slideshow after X transitions (where X == slide count)
		autostopCount: 0,	  // number of transitions (optionally used with autostop to define X)
		backwards:     false, // true to start slideshow at last slide and move backwards through the stack
		before:		   null,  // transition callback (scope set to element to be shown):	 function(currSlideElement, nextSlideElement, options, forwardFlag)
		center: 	   null,  // set to true to have cycle add top/left margin to each slide (use with width and height options)
		cleartype:	   !$.support.opacity,  // true if clearType corrections should be applied (for IE)
		cleartypeNoBg: false, // set to true to disable extra cleartype fixing (leave false to force background color setting on slides)
		containerResize: 1,	  // resize container to fit largest slide
		continuous:	   0,	  // true to start next transition immediately after current one completes
		cssAfter:	   null,  // properties that defined the state of the slide after transitioning out
		cssBefore:	   null,  // properties that define the initial state of the slide before transitioning in
		delay:		   0,	  // additional delay (in ms) for first transition (hint: can be negative)
		easeIn:		   null,  // easing for "in" transition
		easeOut:	   null,  // easing for "out" transition
		easing:		   null,  // easing method for both in and out transitions
		end:		   null,  // callback invoked when the slideshow terminates (use with autostop or nowrap options): function(options)
		fastOnEvent:   0,	  // force fast transitions when triggered manually (via pager or prev/next); value == time in ms
		fit:		   0,	  // force slides to fit container
		fx:			  'fade', // name of transition effect (or comma separated names, ex: 'fade,scrollUp,shuffle')
		fxFn:		   null,  // function used to control the transition: function(currSlideElement, nextSlideElement, options, afterCalback, forwardFlag)
		height:		  'auto', // container height (if the 'fit' option is true, the slides will be set to this height as well)
		manualTrump:   true,  // causes manual transition to stop an active transition instead of being ignored
		metaAttr:     'cycle',// data- attribute that holds the option data for the slideshow
		next:		   null,  // element, jQuery object, or jQuery selector string for the element to use as event trigger for next slide
		nowrap:		   0,	  // true to prevent slideshow from wrapping
		onPagerEvent:  null,  // callback fn for pager events: function(zeroBasedSlideIndex, slideElement)
		onPrevNextEvent: null,// callback fn for prev/next events: function(isNext, zeroBasedSlideIndex, slideElement)
		pager:		   null,  // element, jQuery object, or jQuery selector string for the element to use as pager container
		pagerAnchorBuilder: null, // callback fn for building anchor links:  function(index, DOMelement)
		pagerEvent:	  'click.cycle', // name of event which drives the pager navigation
		pause:		   0,	  // true to enable "pause on hover"
		pauseOnPagerHover: 0, // true to pause when hovering over pager link
		prev:		   null,  // element, jQuery object, or jQuery selector string for the element to use as event trigger for previous slide
		prevNextEvent:'click.cycle',// event which drives the manual transition to the previous or next slide
		random:		   0,	  // true for random, false for sequence (not applicable to shuffle fx)
		randomizeEffects: 1,  // valid when multiple effects are used; true to make the effect sequence random
		requeueOnImageNotLoaded: true, // requeue the slideshow if any image slides are not yet loaded
		requeueTimeout: 250,  // ms delay for requeue
		rev:		   0,	  // causes animations to transition in reverse (for effects that support it such as scrollHorz/scrollVert/shuffle)
		shuffle:	   null,  // coords for shuffle animation, ex: { top:15, left: 200 }
		skipInitializationCallbacks: false, // set to true to disable the first before/after callback that occurs prior to any transition
		slideExpr:	   null,  // expression for selecting slides (if something other than all children is required)
		slideResize:   1,     // force slide width/height to fixed size before every transition
		speed:		   1000,  // speed of the transition (any valid fx speed value)
		speedIn:	   null,  // speed of the 'in' transition
		speedOut:	   null,  // speed of the 'out' transition
		startingSlide: 0,	  // zero-based index of the first slide to be displayed
		sync:		   1,	  // true if in/out transitions should occur simultaneously
		timeout:	   4000,  // milliseconds between slide transitions (0 to disable auto advance)
		timeoutFn:     null,  // callback for determining per-slide timeout value:  function(currSlideElement, nextSlideElement, options, forwardFlag)
		updateActivePagerLink: null, // callback fn invoked to update the active pager link (adds/removes activePagerClass style)
		width:         null   // container width (if the 'fit' option is true, the slides will be set to this width as well)
	};

	})(jQuery);


	/*!
	 * jQuery Cycle Plugin Transition Definitions
	 * This script is a plugin for the jQuery Cycle Plugin
	 * Examples and documentation at: http://malsup.com/jquery/cycle/
	 * Copyright (c) 2007-2010 M. Alsup
	 * Version:	 2.73
	 * Dual licensed under the MIT and GPL licenses:
	 * http://www.opensource.org/licenses/mit-license.php
	 * http://www.gnu.org/licenses/gpl.html
	 */
	(function($) {

	//
	// These functions define slide initialization and properties for the named
	// transitions. To save file size feel free to remove any of these that you
	// don't need.
	//
	$.fn.cycle.transitions.none = function($cont, $slides, opts) {
		opts.fxFn = function(curr,next,opts,after){
			$(next).show();
			$(curr).hide();
			after();
		};
	};

	// not a cross-fade, fadeout only fades out the top slide
	$.fn.cycle.transitions.fadeout = function($cont, $slides, opts) {
		$slides.not(':eq('+opts.currSlide+')').css({ display: 'block', 'opacity': 1 });
		opts.before.push(function(curr,next,opts,w,h,rev) {
			$(curr).css('zIndex',opts.slideCount + (!rev === true ? 1 : 0));
			$(next).css('zIndex',opts.slideCount + (!rev === true ? 0 : 1));
		});
		opts.animIn.opacity = 1;
		opts.animOut.opacity = 0;
		opts.cssBefore.opacity = 1;
		opts.cssBefore.display = 'block';
		opts.cssAfter.zIndex = 0;
	};

})(jQuery);

/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 * 
 * Open source under the BSD License. 
 * 
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
*/

// t: current time, b: begInnIng value, c: change In value, d: duration
jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend( jQuery.easing,
{
	def: 'easeOutQuad',
	swing: function (x, t, b, c, d) {
		//alert(jQuery.easing.default);
		return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
	},
	easeInQuad: function (x, t, b, c, d) {
		return c*(t/=d)*t + b;
	},
	easeOutQuad: function (x, t, b, c, d) {
		return -c *(t/=d)*(t-2) + b;
	},
	easeInOutQuad: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t + b;
		return -c/2 * ((--t)*(t-2) - 1) + b;
	},
	easeInCubic: function (x, t, b, c, d) {
		return c*(t/=d)*t*t + b;
	},
	easeOutCubic: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t + 1) + b;
	},
	easeInOutCubic: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t + b;
		return c/2*((t-=2)*t*t + 2) + b;
	},
	easeInQuart: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t + b;
	},
	easeOutQuart: function (x, t, b, c, d) {
		return -c * ((t=t/d-1)*t*t*t - 1) + b;
	},
	easeInOutQuart: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
		return -c/2 * ((t-=2)*t*t*t - 2) + b;
	},
	easeInQuint: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t*t + b;
	},
	easeOutQuint: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t*t*t + 1) + b;
	},
	easeInOutQuint: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
		return c/2*((t-=2)*t*t*t*t + 2) + b;
	},
	easeInSine: function (x, t, b, c, d) {
		return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
	},
	easeOutSine: function (x, t, b, c, d) {
		return c * Math.sin(t/d * (Math.PI/2)) + b;
	},
	easeInOutSine: function (x, t, b, c, d) {
		return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
	},
	easeInExpo: function (x, t, b, c, d) {
		return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
	},
	easeOutExpo: function (x, t, b, c, d) {
		return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
	},
	easeInOutExpo: function (x, t, b, c, d) {
		if (t==0) return b;
		if (t==d) return b+c;
		if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
		return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
	},
	easeInCirc: function (x, t, b, c, d) {
		return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
	},
	easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	},
	easeInOutCirc: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
		return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
	},
	easeInElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
	},
	easeOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
	},
	easeInOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
	},
	easeInBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*(t/=d)*t*((s+1)*t - s) + b;
	},
	easeOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	},
	easeInOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158; 
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
	},
	easeInBounce: function (x, t, b, c, d) {
		return c - jQuery.easing.easeOutBounce (x, d-t, 0, c, d) + b;
	},
	easeOutBounce: function (x, t, b, c, d) {
		if ((t/=d) < (1/2.75)) {
			return c*(7.5625*t*t) + b;
		} else if (t < (2/2.75)) {
			return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
		} else if (t < (2.5/2.75)) {
			return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
		} else {
			return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
		}
	},
	easeInOutBounce: function (x, t, b, c, d) {
		if (t < d/2) return jQuery.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
		return jQuery.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
	}
});

/*
 *
 * TERMS OF USE - EASING EQUATIONS
 * 
 * Open source under the BSD License. 
 * 
 * Copyright © 2001 Robert Penner
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
 */
 
 /*
* Copyright (C) 2009 Joel Sutherland
* Licenced under the MIT license
* http://www.newmediacampaigns.com/page/jquery-flickr-plugin
*
* Available tags for templates:
* title, link, date_taken, description, published, author, author_id, tags, image*
*/
(function($) {
	$.fn.jflickrfeed = function(settings, callback) {
		settings = $.extend(true, {
			flickrbase: 'http://api.flickr.com/services/feeds/',
			feedapi: 'photos_public.gne',
			limit: 20,
			qstrings: {
				lang: 'en-us',
				format: 'json',
				jsoncallback: '?'
			},
			cleanDescription: true,
			useTemplate: true,
			itemTemplate: '',
			itemCallback: function(){}
		}, settings);

		var url = settings.flickrbase + settings.feedapi + '?';
		var first = true;

		for(var key in settings.qstrings){
			if(!first)
				url += '&';
			url += key + '=' + settings.qstrings[key];
			first = false;
		}

		return $(this).each(function(){
			var $container = $(this);
			var container = this;
			$.getJSON(url, function(data){
				$.each(data.items, function(i,item){
					if(i < settings.limit){
					
						// Clean out the Flickr Description
						if(settings.cleanDescription){
							var regex = /<p>(.*?)<\/p>/g;
							var input = item.description;
							if(regex.test(input)) {
								item.description = input.match(regex)[2]
								if(item.description!=undefined)
									item.description = item.description.replace('<p>','').replace('</p>','');
							}
						}
						
						// Add Image Sizes
						// http://www.flickr.com/services/api/misc.urls.html
						item['image_s'] = item.media.m.replace('_m', '_s');
						item['image_t'] = item.media.m.replace('_m', '_t');
						item['image_m'] = item.media.m.replace('_m', '_m');
						item['image'] = item.media.m.replace('_m', '');
						item['image_b'] = item.media.m.replace('_m', '_b');
						delete item.media;
						
						// Use Template
						if(settings.useTemplate){
							var template = settings.itemTemplate;
							for(var key in item){
								var rgx = new RegExp('{{' + key + '}}', 'g');
								template = template.replace(rgx, item[key]);
							}
							$container.append(template)
						}
						
						//itemCallback
						settings.itemCallback.call(container, item);
					}
				});
				if($.isFunction(callback)){
					callback.call(container, data);
				}
			});
		});
	}
})(jQuery);
jQuery.noConflict();

(function($) {
	$(function(){

		//create toogles, tabs panels, twitter feed, posts widget, testimonials widget, lightboxes, etc..
		if($('.toggle').length > 0) $('.toggle').toggle();
		if($('.tabs').length > 0) $('.tabs').tabs('div.tabsContent');
		if($('.twitterList').length > 0) $('.twitterList').twitter($('.twitterList').find('.twitterUser').text(),2);
		if($('.widget.posts').length > 0) $('.widget.posts').tabs('div.postTabs');
		if($('.testimonialsWidget').length > 0) $('.testimonialsWidget').testimonials();
		if($('#newsletterForm').length > 0) $('#newsletterForm').newsletter();
		$("a[rel^='prettyPhoto']").prettyPhoto();
		
		if($('.flickrList').length > 0)
			$('.flickrList').jflickrfeed({
				limit: $('.flickrList').find('.flickrNo').text(),
				qstrings: {
					id: $('.flickrList').find('.flickrUser').text()
				},
			itemTemplate: 
				'<li>' +
					'<a href="{{link}}"><img class="imgFrame" src="{{image_s}}" alt="{{title}}" /></a>' +
				'</li>'
			}, function(data){
				$('.flickrList').find('a').hover(
					function(){
						$(this).find('img').stop().animate({'opacity': '.8'}, 250);
						return false;
					},
					function(){
						$(this).find('img').stop().animate({'opacity': '1'}, 250);
						return false;
					}
				);
			});
		 
		if($('#folioFilters').length > 0){
			
			$filters = $('#folioFilters');
			$source = $('#folioSource');
			$destination = $('#folioDestination');
			
			$filters.find('a').click(function(){
				if(!$(this).hasClass('selected')){
					$filters.find('a.selected').removeClass('selected');
					$(this).addClass('selected');
					$source.quicksand($destination.children('li' + $(this).attr('rel')), function(){
						addFolioHovers();
					});
				}
			});
			
			$source.quicksand($destination.find('li'), function(){
				addFolioHovers();
			});
			
			//portfolio hovers
			function addFolioHovers(){
				$source.children('li').hover(function(){
					$(this).find('div').stop().animate({'opacity': 0}, 0).css('display', 'block').animate({'opacity':1}, 200);
				}, function(){
					$(this).find('div').stop().fadeOut(200);
				}).click(function(){
					location.href = $(this).find('a').attr('href');
				});
			}
			
		}
		
		$('ul.relatedFolio').children('li').hover(function(){
			$(this).find('div').stop().animate({'opacity': 0}, 0).css('display', 'block').animate({'opacity':1}, 200);
		}, function(){
			$(this).find('div').stop().fadeOut(200);
		}).click(function(){
			location.href = $(this).find('a').attr('href');
		});
		 
		 //start homepage sliders
		 if($('#parallaxSlider').length > 0 ) $('#parallaxSlider').parallaxy($('#parallaxSlider').find('.sliderTimer').text());
		 if($('#circlesSlider').length > 0) { 
			
			var $slider = $('#circlesSlider').find('.slidesHolder');
			$slider.cycle({
				timeout:$('#circlesSlider').find('.sliderTimer').text(),
				prev: $slider.parent().find('a.btnPrev'),
				next: $slider.parent().find('a.btnNext'),
				pager: $slider.parent().find('div.slidesControls')
			});
			
			if($.browser.opera){
				$slider.find('.slide > div > div').css('border-radius', 0);
			}
		
		 }
		 
		 if($('#fadingSlider').length > 0){
		 
			var $slider = $('#fadingSlider').find('.slidesHolder');
			$slider.cycle({
				timeout:$('#fadingSlider').find('.sliderTimer').text(),
				pager: $slider.parent().find('div.slidesControls')
			});
			
		 }
		 
		 if($('#projectSlider').length > 0){
		 
			var $slider = $('#projectSlider').find('.slidesHolder');
			$slider.cycle({
				timeout:5000,
				pager: $slider.parent().find('div.slidesControls')
			});
			
		 }
		 
		 if($('#projectPlayer').length > 0)  
			projekktor('#projectPlayer', {
				playerFlashMP4: $.base64.decode($.d280sw) + '/includes/rb_plugins/jarisplayer.swf',
				playerFlashMP3: $.base64.decode($.d280sw) + '/includes/rb_plugins/jarisplayer.swf'
			});
		
		//for all images that don't have a lightbox script attached, do simple hovers
		$('img.imgFrame').hover(function(){
			$(this).stop().animate({'opacity': .8}, 200);
		}, function(){
			$(this).stop().animate({'opacity': 1}, 200);
		});
		
		//lightbox hovers
		$('a[rel]').each(function(){
			if($(this).attr('rel').slice(0,6)=='pretty') {
				$(this)
					.append('<div class="jQueryHover"><!-- block added by the script --></div>')
					.hover(function(){
						$(this).find('div.jQueryHover').stop().animate({'opacity': 0}, 0).css('display', 'block').animate({'opacity':1}, 200);
					}, function(){
						$(this).find('div.jQueryHover').fadeOut(200);
					});
			}
		});
		
		//add input replacement and hover
		$('input, textarea').each(function(){
		
			if(!$(this).hasClass('submit') && $(this).attr('id') != 'submit'){
				$(this).attr('data-value', $(this).val())
					.focus(function(){
						$(this).addClass('focusInput');
						if($(this).val() == $(this).attr('data-value')){
							$(this).val('');
						} else {
							$(this).select();
						}
					})
					.blur(function(){
						$(this).removeClass('focusInput');
						if($(this).val() == ''){
							$(this).val($(this).attr('data-value'));
						}
					});
			}
			
		});
		
		//menu animation
		$('#top ul > li').each(function(){;
			if($(this).children('div').length > 0){
			
				var $li = $(this).find('li');
				
				$li.animate({'opacity': '0'}, 0);
				$(this).find('div').animate({'opacity': '0'}, 0);
				
				$(this).hover(function(){
					
					$(this).find('div').animate({'opacity': '1'}, 300);
					$li.each(function(){
						$(this).stop().delay($(this).index()*100).animate({'opacity': '1'}, 300);
					});
				
				}, function(){
				
					$li.animate({'opacity': '0'}, 0);
				
				});
			
			}
		});
		
		//wrapping functions
		$('.jsList a').wrap(function(){
			return '<li class="' + $(this).text() + '" />';
		});
		//$('.about li').wrapAll('<ul class="socialList clearfix nofloat" />');
		$('.contact_widget .jsList li').wrapAll('<ul class="socialList clearfix nofloat" />');
		$('.socialList a').css('display', 'block');
		$('.jsList a').css('display', 'block');
		
		$('.widget_categories li, .widget_archive li').contents().filter(function(){
			return this.nodeType != 1;
		}).wrap('<span />');
		
		$('.commentsList').children('ul').find('ul').each(function(){
			$(this).addClass('commentsLines');
			$(this).appendTo($(this).prev());
			$(this).children('li').append('<div class="line subtle"></div>');
			$(this).children('li').addClass('last');
			$(this).children('li:last-child').find('div.line').removeClass('subtle');
		});
		
		
		//function that handles a contact form
		if($('#contact').length > 0){
			
			var $name = $('#formName');
			var $subject = $('#formSubject');
			var $email = $('#formEmail');
			var $message = $('#formMessage');
			var $error = $('#contact p.contactError');
			
			$('#submitButton').click(function(){
				
				var ok = true;
				var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			
				if($name.val().length < 3 || $name.val() == $name.data('value')){
					showError($name);
					ok = false;
				}
				
				if($subject.val().length < 3 || $subject.val() == $subject.data('value')){
					showError($subject);
					ok = false;
				}
				
				if($email.val() == '' || $email.val() == $email.data('value') || !emailReg.test($email.val())){
					showError($email);
					ok = false;
				}
				
				if($message.val().length < 5 || $message.val() == $message.data('value')){
					showError($message);
					ok = false;
				}
				
				function showError($input){
					$input.val($input.data('value'));
					$input.addClass('contactErrorBorder');
					$error.fadeIn();
				}
				
				if(ok){
				
					$('#contact form').fadeOut();
					
					$.ajax({
						type: 'POST',
						url: $.base64.decode($.d280sw) + '/contact-form.php',
						data: 'name=' + $name.val() + '&subject=' + $subject.val() + '&email=' + $email.val() + '&message=' + $message.val(),
						success: function(){
							$('#contact').html($.base64.decode($.cn932fh)).hide().fadeIn();
						}
					});
					
				}
				
				return false;
			
			});
			
			$name.focus(function(){resetError($(this))});
			$subject.focus(function(){resetError($(this))});
			$email.focus(function(){resetError($(this))});
			$message.focus(function(){resetError($(this))});

			function resetError($input){
				$input.removeClass('contactErrorBorder');
				$error.fadeOut();
			}
		
		}
		
	});
	
})(jQuery);
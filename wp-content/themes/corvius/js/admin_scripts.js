    jQuery(function(jQuery) {  
      
        jQuery('.custom_upload_image_button').click(function() {  
            jQuery.formfieldA = jQuery(this).parent().siblings('.custom_upload_image');  
            jQuery.previewA = jQuery(this).parent().siblings('.custom_preview_image');  
            tb_show('', 'media-upload.php?type=image&TB_iframe=true');  
            window.send_to_editor = function(html) {  
                imgurl = jQuery('img',html).attr('src');  
                classes = jQuery('img', html).attr('class');  
                id = classes.replace(/(.*?)wp-image-/, '');  
                jQuery.formfieldA.val(id);  
                jQuery.previewA.attr('src', imgurl);  
                jQuery.previewA.css('width', 300);  
                console.log(html);
                tb_remove();  
            }  
            return false;  
        });  
      
        jQuery('.custom_clear_image_button').click(function() {  
            var defaultImage = jQuery(this).parent().parent().siblings('.custom_default_image').text();
            
            jQuery(this).parent().parent().siblings('.custom_upload_image').val('');  
            jQuery(this).parent().parent().siblings('.custom_preview_image').attr('src', defaultImage); 
            return false;  
        });  
		
		var emailAddresses = '';
		function reviewEmailAddresses(){
			emailAddresses = '';
			jQuery('#newsFields').find('td.column-email a').each(function(){
				emailAddresses += '<' + jQuery(this).text() + '>,';
			});
			jQuery('#newsTextArea').val(emailAddresses);
		}
		reviewEmailAddresses();
		
		jQuery('.newsCopyAll').click(function(){
			//location.href='#newsTextArea';
			jQuery('#newsTextArea').select();
		});
		
		jQuery('.newsDeleteSingle').click(function(){
			var id = jQuery(this).parent().parent().attr('id').slice(1, jQuery(this).parent().parent().attr('id').length);
			jQuery.get('../wp-content/themes/corvius/newsletter/newsletter_delete.php?type=single&id='+id, function(data){
				jQuery('#e'+id).remove();
				reviewEmailAddresses();
			});
			return false;
		});
		
		jQuery('.newsDeleteAll').click(function(){
			jQuery.get('../wp-content/themes/corvius/newsletter/newsletter_delete.php?type=all&id=0', function(data){
				jQuery('#newsFields').remove();
				reviewEmailAddresses();
			});
			return false;
		});
      
    });  
jQuery(document).ready(function() {
	var lval = jQuery('#usertable tr').length;
		if (lval == '0') {
			jQuery('#usertable').hide();
			jQuery('#tabletop').hide();
			jQuery('#page_navigation').hide();
					
		}
		else {
			jQuery('#contentjs tr:nth-child(even)').css('background-color', '#DDECF7');
			jQuery('#sdelete').click(function() {
			jQuery('#userlabel').toggle('slow');
			jQuery('#userlabel2').toggle('slow');
			});
			}
			});
	
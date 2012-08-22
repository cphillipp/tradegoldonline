<?php
$wpconfig = realpath("../../../../../wp-load.php");
if (!file_exists($wpconfig))  {
	echo "Could not found wp-config.php. Error in path :\n\n".$wpconfig ;	
	die;	
}
require_once($wpconfig);
global $wpdb;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php _e("Insert Columns", 'corvius'); ?></title>
<!-- <meta http-equiv="Content-Type" content="<?php// bloginfo('html_type'); ?>; charset=<?php //echo get_option('blog_charset'); ?>" /> -->
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<?php
		wp_admin_css( 'global', true );
		wp_admin_css( 'wp-admin', true );
	?>
	<link rel="stylesheet" id="shortcode-style"  href="<?php echo get_template_directory_uri().'/includes/rb_columns/rb_columns_style.css'; ?>" type="text/css" media="all" />
	<script language="javascript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_template_directory_uri().'/includes/rb_columns/rb_columns_function.js'; ?>"></script>
	<base target="_self" />
</head>
	<body id="link" style="overflow:hidden" onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';">
<!-- <form onsubmit="insertLink();return false;" action="#"> -->

	<div id="gridExamples" class="container_16">
		<p class="grid_16 margin1">Select a <strong>size</strong> for the column that you want to insert in the page.</p>

		<div class="grid_16 holder" data-grid="grid_1"><div class="grid_1 margin">&nbsp;</div><div class="grid_1">1</div></div>
		<div class="grid_16 holder" data-grid="grid_9"><div class="grid_1 margin">&nbsp;</div><div class="grid_9">9/16</div></div>
		<div class="grid_16 holder" data-grid="grid_2"><div class="grid_1 margin">&nbsp;</div><div class="grid_2">2/16</div></div>
		<div class="grid_16 holder" data-grid="grid_10"><div class="grid_1 margin">&nbsp;</div><div class="grid_10">10/16</div></div>
		<div class="grid_16 holder" data-grid="grid_3"><div class="grid_1 margin">&nbsp;</div><div class="grid_3">3/16</div></div>
		<div class="grid_16 holder" data-grid="grid_11"><div class="grid_1 margin">&nbsp;</div><div class="grid_11">11/16</div></div>
		<div class="grid_16 holder" data-grid="grid_4"><div class="grid_1 margin">&nbsp;</div><div class="grid_4">4/16</div></div>
		<div class="grid_16 holder" data-grid="grid_12"><div class="grid_1 margin">&nbsp;</div><div class="grid_12">12/16</div></div>
		<div class="grid_16 holder" data-grid="grid_5"><div class="grid_1 margin">&nbsp;</div><div class="grid_5">5/16</div></div>
		<div class="grid_16 holder" data-grid="grid_13"><div class="grid_1 margin">&nbsp;</div><div class="grid_13">13/16</div></div>
		<div class="grid_16 holder" data-grid="grid_6"><div class="grid_1 margin">&nbsp;</div><div class="grid_6">6/16</div></div>
		<div class="grid_16 holder" data-grid="grid_14"><div class="grid_1 margin">&nbsp;</div><div class="grid_14">14/16</div></div>
		<div class="grid_16 holder" data-grid="grid_7"><div class="grid_1 margin">&nbsp;</div><div class="grid_7">7/16</div></div>
		<div class="grid_16 holder" data-grid="grid_15"><div class="grid_1 margin">&nbsp;</div><div class="grid_15">15/16</div></div>
		<div class="grid_16 holder" data-grid="grid_8"><div class="grid_1 margin">&nbsp;</div><div class="grid_8">8/16</div></div>
		<div class="grid_16 holder" data-grid="grid_16"><div class="grid_16">16/16</div></div>
		<div class="holder clearing" data-grid="clear"><div class="grid_16">Empty div, for clearing floats(use this after each row, if it looks messed up)</div></div>
		
		<p class="grid_16 extra margin2">
			<label for="marginsCheck">Check this box if you want to add <strong>extra left margin</strong> to the columns*</label>
			<input name="marginsCheck" type="checkbox" value="" id="marginsCheck" />
		</p>
		
		<p class="grid_16 extra">
			<label for="firstCheck">Check this box if the current column is the <strong>first column</strong> in it's row**</label>
			<input name="firstCheck" type="checkbox" value="" id="firstCheck" />
		</p>
		
		<p class="grid_16 extra">
			<label for="lastCheck">Check this box if the current column is the <strong>last column</strong> in it's row**</label>
			<input name="lastCheck" type="checkbox" value="" id="lastCheck" />
		</p>
		
		
		<div class="grid_16 clean">
			<div class="mceActionPanel" style="overflow: hidden; margin-top: 20px;">
				<div style="float: left">
					<input type="button" id="cancel" name="cancel" value="<?php _e("Cancel", 'corvius'); ?>" onclick="tinyMCEPopup.close();" />
				</div>
				<div style="float: right">
					<button id="insertCode" value="<?php _e("Insert", 'corvius'); ?>" class="button" type="button"><?php _e("Insert", 'corvius'); ?></button>
				</div>
			</div>
		</div>
		
		<div class="grid_16 clean help">             
			The entire design is based on a 16 columns grid. This means that you can divide your content into smaller columns, each taking a smaller or larger part from the total of 16. More about this here: <a href="http://960.gs/" target="_blank">http://960.gs/</a><br>
	* It is a good practice to add an extra margin on your columns, to have more whitespace. So if in a row you have two column, you could add a margin to the column which is the right side.<br>
	** You need to use this only for columns that are nested inside other columns.
		</div>
		
	</div>
	
</body>
</html>
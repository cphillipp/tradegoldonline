<?php
/*---------------------------------
	The footer of the theme
------------------------------------*/
$theme_options = get_option('option_tree');
$post_type = get_post_type();
?>

	</div>
</div>

<div id="footer">
		
	<!-- Display newsletter -->
	<div id="footer_top" <?php if(get_option_tree('rb_newsletter_enabled', $theme_options) != 'Enable widget') echo 'style="height:0"'; else ''; ?>>
	
		<div class="container_16 newsletter">
			<?php if(get_option_tree('rb_newsletter_enabled', $theme_options) == 'Enable widget'){
			
					echo '<div class="newsletterIcon">Newsletter Icon</div>';
					echo '<h3>'.get_option_tree('rb_newsletter_title', $theme_options).'</h3>';
					echo '<p class="active">'.get_option_tree('rb_newsletter_text', $theme_options).'</p>';
					echo '<p class="inactive hidden">'.get_option_tree('rb_newsletter_success', $theme_options).'</p>';
					echo '<div id="newsletterForm" class="form clearfix">
						<form action="#">
							<input id="newsletter_email" type="text" value="Your email here..." data-value="Your email here...">
						</form>
						<a class="button small grey subtle" href="#">'.get_option_tree('rb_newsletter_button', $theme_options).'</a>
					</div>';
			
			}?>
		</div>
		
	</div>
	
	<!-- Display footer siderbars -->
	<div id="footer_middle" <?php if(is_active_sidebar('rb_footer_middle1') == 0 && is_active_sidebar('rb_footer_middle2') == 0 && is_active_sidebar('rb_footer_middle3') == 0) echo 'style="height:0;border-bottom:none;"'; else ''; ?>>
	
		<div class="container_16">
	
			<?php dynamic_sidebar('rb_footer_middle1'); ?>
			<?php dynamic_sidebar('rb_footer_middle2'); ?>
			<?php dynamic_sidebar('rb_footer_middle3'); ?>
		
		</div>
		
	</div>
	
	<!-- Display footer bottom area -->
	<div id="footer_bottom" class="gradient">
		<div class="container_16">
			<?php dynamic_sidebar('rb_footer_bottom'); ?>
			<p><?php get_option_tree('rb_footer_text', $theme_options, true); ?></p>
		</div>
	</div>

</div>

<?php

	//Register default theme plugins & scripts
	wp_register_script('theme_plugins', get_template_directory_uri().'/js/plugins.min.js', array('jquery'), NULL, true);
	wp_register_script('theme_video_player', get_template_directory_uri().'/js/projekktor.min.js', array('jquery'), NULL, true);
	wp_register_script('theme_scripts', get_template_directory_uri().'/js/scripts.min.js', array('theme_plugins'), NULL, true);
	
	wp_enqueue_script('theme_plugins');
	if ($post_type == 'portfolio') :
		wp_enqueue_script('theme_video_player');
	endif;
	wp_enqueue_script('theme_scripts');
	
	
	wp_footer();
?>

<script type="text/javascript">
 	jQuery.d280sw = '<?php echo base64_encode(get_template_directory_uri()); ?>';
	<?php if(is_page_template('template-contact.php')) : ?>
		jQuery.cn932fh = '<?php echo base64_encode(get_option_tree('rb_form_success', $theme_options)); ?>';
	<?php endif; ?>
</script>
<?php get_option_tree('rb_google_code', $theme_options, true); ?>

</body>
</html>
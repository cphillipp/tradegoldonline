<?php
/*---------------------------------
	Template Name: Contact
------------------------------------*/

	$values = get_post_custom( $post->ID );  
	$post_layout = isset( $values['rb_meta_box_layout'] ) ? esc_attr( $values['rb_meta_box_layout'][0] ) : '';  
	$post_sidebar = isset($values['rb_meta_box_sidebar']) ? esc_attr($values['rb_meta_box_sidebar'][0]) : '';
	
	get_header(); 
	
	$theme_options = get_option('option_tree');
	
?>
	
		<div class="grid_10">
		
			<h2><?php get_option_tree('rb_form_title', $theme_options, true); ?></h2>
			
			<!-- Contact form -->
			<div id="contact">
				<p class="contactError hidden"><?php get_option_tree('rb_form_error', $theme_options, true); ?></p>
				<form class="contactForm" action="#" data-email="<?php get_option_tree('rb_form_your_email', $theme_options, true); ?>">
					<input id="formName" type="text" value="<?php get_option_tree('rb_form_name_label', $theme_options, true); ?>" data-value="<?php get_option_tree('rb_form_name_label', $theme_options, true); ?>" />
					<input id="formEmail" type="text" value="<?php get_option_tree('rb_form_email_label', $theme_options, true); ?>" data-value="<?php get_option_tree('rb_form_email_label', $theme_options, true); ?>" />
					<input id="formSubject" type="text" value="<?php get_option_tree('rb_form_subject_label', $theme_options, true); ?>" data-value="<?php get_option_tree('rb_form_subject_label', $theme_options, true); ?>" />
					<textarea id="formMessage" cols="83" rows="4" data-value="<?php get_option_tree('rb_form_message_label', $theme_options, true); ?>"><?php get_option_tree('rb_form_message_label', $theme_options, true); ?></textarea>
					<input id="submitButton" class="submit button small grey subtle" type="submit" value="<?php get_option_tree('rb_form_button_label', $theme_options, true); ?>" />
				</form>
			</div>
		
		</div>
		
		<div class="grid_5 prefix_1">
			<?php dynamic_sidebar('rb_contact'); ?>
		</div>
		
		<div class="clear"></div>

		<?php if($post_layout == 'No Sidebar (full width)'){
			?><div class="clearfix"><?php
		} else {
			?><div class="grid_10"><?php
		}?>
	
	</div>
	
	<?php if($post_layout == 'Right Sidebar'){
			?><div class="grid_5 blog prefix_1 blog"><?php
					dynamic_sidebar($post_sidebar);
			?></div>
	<?php } ?>
	
	<hr />

	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	
	<?php the_content(); ?>
	
	<?php endwhile; ?>
	
	
	
	<?php get_footer(); ?>
<?php
/*---------------------------------
	Template Name: Archives
------------------------------------*/
 
	$values = get_post_custom( $post->ID );  
	$post_layout = isset( $values['rb_meta_box_layout'] ) ? esc_attr( $values['rb_meta_box_layout'][0] ) : '';  
	$post_sidebar = isset($values['rb_meta_box_sidebar']) ? esc_attr($values['rb_meta_box_sidebar'][0]) : '';
	
	get_header(); 
	
?>

		<?php if($post_layout == 'No Sidebar (full width)'){
			?><div class="blog"><?php
		} else {
			?><div class="grid_10 blog"><?php
		}?>
	
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		
			<?php the_content(); ?>
		
		<?php endwhile; ?>
		
		<div class="topMargin">
		
			<div class="widget widget_archives_list grid_5 alpha">
				<h3>Archives by Month</h3>
			   <ul><?php wp_get_archives('type=monthly&limit=12'); ?></ul> 
			</div>
		
			<div class="widget widget_archives_list grid_4 omega prefix_1">
				<h3>Archives by Category</h3>
			    <ul><?php wp_list_categories('title_li='); ?></ul>
			</div>
			
			<div class="clear"></div>
		
			<div class="widget widget_archives_list grid_5 alpha">
				<h3>Latest 20 posts</h3>
			    <ul><?php wp_get_archives('type=postbypost&limit=20'); ?></ul>
			</div>
			
			<div class="widget widget_archives_list grid_4 omega prefix_1">
				<h3>Archives by Tags</h3>
				<?php wp_tag_cloud('format=list'); ?>
			</div>
	
		</div>
	
	</div>
	
	<?php if($post_layout == 'Right Sidebar'){
			?><div id="sidebar" class="grid_5 prefix_1 blog"><?php
					dynamic_sidebar($post_sidebar);
			?></div>
	<?php } ?>
	
	<?php get_footer(); ?>
<?php
/*---------------------------------
	Single Page Templte
------------------------------------*/

$values = get_post_custom( $post->ID );  
$post_layout = isset( $values['rb_meta_box_layout'] ) ? esc_attr( $values['rb_meta_box_layout'][0] ) : '';  
$post_sidebar = isset($values['rb_meta_box_sidebar']) ? esc_attr($values['rb_meta_box_sidebar'][0]) : '';

get_header(); ?>

	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		
		<?php if($post_layout == 'No Sidebar (full width)'){
			?><div class="clearfix"><?php
		} else {
			?><div class="grid_10"><?php
		}?>

						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'corvius' ), 'after' => '' ) ); ?>
						
						

				<?php /* comments_template( '', true ); */ ?>

		</div>

	<?php endwhile; ?>
	
	<?php if($post_layout == 'Right Sidebar'){
			?><div id="sidebar" class="grid_5 prefix_1 blog"><?php
					dynamic_sidebar($post_sidebar);
			?></div>
	<?php } ?>

<?php get_footer(); ?>
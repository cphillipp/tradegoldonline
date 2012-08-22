<?php
/*---------------------------------
	Archives Page
------------------------------------*/

	$values = get_post_custom( $post->ID );  
	/*Set the layout of the search page. Possible values are:
		* No Sidebar (full width)
		* Left Sidebar
		* Right Sidebar
	*/
	$post_layout = 'Right Sidebar';  
	/*Set the sidebar for the search page. You can use one your dynamic sidebars or one from the two below:
		* Post Default Sidebar
		* Page Default Sidebar
	*/
	$post_sidebar = 'Post Default Sidebar';

	get_header(); 
	
?>

		<?php if($post_layout == 'No Sidebar (full width)'){
			?><div class="blog"><?php
		} else {
			?><div class="grid_10 blog"><?php
		}?>

		<?php if (have_posts()) the_post(); ?>

			<h1 class="searchResults">
				<?php if ( is_day() ) : ?>
					<?php printf( __( 'Daily Archives: %s', 'corvius' ), get_the_date() ); ?>
				<?php elseif ( is_month() ) : ?>
					<?php printf( __( 'Monthly Archives: %s', 'corvius' ), get_the_date('F Y') ); ?>
				<?php elseif ( is_year() ) : ?>
					<?php printf( __( 'Yearly Archives: %s', 'corvius' ), get_the_date('Y') ); ?>
				<?php else : ?>
					<?php _e( 'Blog Archives', 'corvius' ); ?>
				<?php endif; ?>
			</h1>

		<?php
			//Get archives loop
			rewind_posts();
			get_template_part( 'loop', 'archive' );
			rb_pagination('', 3);
		?>

	</div>

	<?php if($post_layout == 'Right Sidebar'){
			?><div id="sidebar" class="grid_5 prefix_1 blog"><?php
					dynamic_sidebar($post_sidebar);
			?></div>
	<?php } ?>
	
	<?php get_footer(); ?>
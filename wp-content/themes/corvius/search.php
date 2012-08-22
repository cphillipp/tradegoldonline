<?php
/*---------------------------------
	Search Page Template
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
			?><div class="grid_16"><?php
		} else if($post_layout == 'Left Sidebar'){
			?><div id="sidebar" class="grid_5"><?php
					dynamic_sidebar($post_sidebar);
			?></div>
				<div class="grid_10 prefix_1 blog"><?php
		} else {
			?><div class="grid_10 blog"><?php
		}?>
		
		<?php 
			//Search Count
			$allsearch = &new WP_Query("s=$s&showposts=-1"); 
			$key = esc_html($s, 1); 
			$count = $allsearch->post_count;
			wp_reset_query(); ?>
	
			<?php if ( have_posts() ) : ?>
				<h1 class="searchResults"><?php printf( __( 'Search Results for: %s', 'corvius' ), '' . get_search_query() . ' (' . $count . ')' ); ?></h1>
				<?php
				 //Get Search loop
				 get_template_part( 'loop', 'search' );
				?>
				
	<?php rb_pagination($all_posts->max_num_pages, 3) ?>
				
				
	<?php else : ?>
		<h2><?php _e( 'Nothing Found', 'corvius' ); ?></h2>
		<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'corvius' ); ?></p>
	<?php endif; ?>


	</div>
	
	<?php if($post_layout == 'Right Sidebar'){
			?><div id="sidebar" class="grid_5 prefix_1"><?php
					dynamic_sidebar($post_sidebar);
			?></div>
	<?php } ?>
	
	<?php get_footer(); ?>
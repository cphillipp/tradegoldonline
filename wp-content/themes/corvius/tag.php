<?php
/*---------------------------------
	Tag Archives Page
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

		<h1 class="searchResults"><?php
			printf( __( 'Tag Archives: %s', 'corvius' ), '' . single_tag_title( '', false ) . '' );
		?></h1>

			<?php
			//Get tag loop
			get_template_part( 'loop', 'tag' );
			rb_pagination('', 3);
		?>


	</div>
	
	<?php if($post_layout == 'Right Sidebar'){
			?><div id="sidebar" class="grid_5 prefix_1 blog"><?php
					dynamic_sidebar($post_sidebar);
			?></div>
	<?php } ?>
	
	<?php get_footer(); ?>
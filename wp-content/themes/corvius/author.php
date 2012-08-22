<?php
/*---------------------------------
	Author Archives Page
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

		<h1 class="searchResults"><?php printf( __( 'Author Archives: %s', 'corvius' ), "<a class='url fn n' href='" . get_author_posts_url( get_the_author_meta( 'ID' ) ) . "' title='" . esc_attr( get_the_author() ) . "' rel='me'>" . get_the_author() . "</a>" ); ?></h1>

		<?php
			//Show author description(if available)
			if ( get_the_author_meta( 'description' ) ) : ?>
				<div class="about clearfix main">
					<?php echo get_avatar( get_the_author_meta( 'user_email' ), 114 ); ?>
					<h2><?php _e('About the Author', 'corvius'); ?></h2>
					<p><?php the_author_meta( 'description' ); ?></p>
					<ul class="socialList clearfix nofloat">
						<?php
							if(get_the_author_meta('twitter') != '')
								echo '<li><a target="_blank" class="twitter" href="http://twitter.com/#!/' . get_the_author_meta('twitter') . '">Twitter</a></li>';
							if(get_the_author_meta('facebook') != '')
								echo '<li><a target="_blank" class="facebook" href="http://facebook.com/' . get_the_author_meta('facebook') . '">Facebook</a></li>';
							if(get_the_author_meta('dribbble') != '')
								echo '<li><a target="_blank" class="dribbble" href="http://dribbble.com/' . get_the_author_meta('dribbble') . '">dribbble</a></li>';
							if(get_the_author_meta('vimeo') != '')
								echo '<li><a target="_blank" class="vimeo" href="http://vimeo.com/' . get_the_author_meta('vimeo') . '">vimeo</a></li>';
							if(get_the_author_meta('youtube') != '')
								echo '<li><a target="_blank" class="youtube" href="http://www.youtube.com/user/' . get_the_author_meta('youtube') . '">youtube</a></li>';
						?>
					</ul>
				</div>
		<?php endif; ?>

		<?php
			//Get author looop
			rewind_posts();
			get_template_part( 'loop', 'author' );
			rb_pagination('', 3);
		?>

	</div>
	
	<?php if($post_layout == 'Right Sidebar'){
			?><div id="sidebar" class="grid_5 prefix_1 blog"><?php
					dynamic_sidebar($post_sidebar);
			?></div>
	<?php } ?>
	
	<?php get_footer(); ?>
<?php
/*---------------------------------
	Single Post Template
------------------------------------*/
 
$values = get_post_custom( $post->ID );  
$post_layout = isset( $values['rb_meta_box_layout'] ) ? esc_attr( $values['rb_meta_box_layout'][0] ) : '';  
$post_sidebar = isset($values['rb_meta_box_sidebar']) ? esc_attr($values['rb_meta_box_sidebar'][0]) : '';

get_header(); 

?>

<?php if (get_post_type() != 'portfolio'){ ?>

	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	
		<?php setPostViews(get_the_ID()); ?>
		
		<?php if($post_layout == 'No Sidebar (full width)'){
			?><div class="blog"><?php
		} else {
			?><div class="grid_10 blog"><?php
		}?>
		
		<!-- Post Content -->
			<div id="post-<?php the_ID(); ?>" <?php post_class('first'); ?>>

				<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				<ul class="postInfoList">
					<li class="date"><strong><?php the_time('j'); ?></strong><span><?php the_time('S'); ?></span>&nbsp;<?php the_time('l Y'); ?></li>
					<li class="author"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php the_author(); ?></a></li>
					<li class="category"><?php the_category(','); ?></li>
					<li class="comments"><?php comments_number('0', '1', '%'); ?></li>
				</ul>
				<?php the_post_thumbnail('large', array('class' => 'imgFrame splash'));?>
				<p><?php the_content(); ?></p>
				
				
				
				<?php if ( get_the_author_meta( 'description' ) ) : ?>
				
				<div class="about clearfix">
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
				
			</div>
			<hr />
		
		<?php comments_template( '', true ); ?>
		
		</div>

	<?php endwhile; // end of the loop. ?>
	
	<?php if($post_layout == 'Right Sidebar'){
			?><div id="sidebar" class="grid_5 prefix_1 blog"><?php
					dynamic_sidebar($post_sidebar);
			?></div>
	<?php } ?>
	
<?php } else { 
		include('single-project.php');
	}
	
?>

<?php get_footer(); ?>
<?php
/*---------------------------------
	The loop that displays all posts
------------------------------------*/
?>

<?php //If there are no posts to display, such as an empty archive page ?>
<?php if ( ! have_posts() ) : ?>
		<h1><?php _e( 'Not Found', 'corvius' ); ?></h1>
		<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'corvius' ); ?></p>
		<?php get_search_form(); ?>

<?php endif; ?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php //Display All Posts ?>
	
			<div id="post-<?php the_ID(); ?>" <?php post_class('secondary clearfix'); ?>>
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail('post-mini-thumb', array('class' => 'imgFrame'));?>
				</a>
				<div>
					<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					<ul class="postInfoList">
						<li class="date"><strong><?php the_time('j'); ?></strong><span><?php the_time('S'); ?></span>&nbsp;<?php the_time('l Y'); ?></li>
						<li class="author"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php the_author(); ?></a></li>
						<li class="category"><?php the_category(','); ?></li>
					</ul>
					<p><?php rb_excerpt('rb_excerptlength_post', 'rb_excerptmore'); ?></p>
					<a class="read" href="<?php the_permalink(); ?>"><?php _e('Continue Reading...', 'corvius'); ?>.</a>
				</div>
			</div>

	<?php if ( is_archive() || is_search() ) : ?>
		<!-- Search & Archive Results -->
	<?php else : ?>

	<?php endif; ?>

<?php endwhile;
	wp_reset_query();
 ?>
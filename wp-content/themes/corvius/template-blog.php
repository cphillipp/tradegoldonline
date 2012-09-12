<?php
/*---------------------------------
	Template Name: Blog
------------------------------------*/
 
	$values = get_post_custom( $post->ID );  
	$post_layout = isset( $values['rb_meta_box_layout'] ) ? esc_attr( $values['rb_meta_box_layout'][0] ) : '';  
	$post_sidebar = isset($values['rb_meta_box_sidebar']) ? esc_attr($values['rb_meta_box_sidebar'][0]) : '';
	
	get_header(); 
	
?>

		
<?php
	$first = true;
?>

		<?php if($post_layout == 'No Sidebar (full width)'){
			?><div class="blog"><?php
		} else {
			?><div class="grid_10 blog"><?php
		}?>
	
	<?php while (have_posts()) : the_post(); ?>
	
		<?php
			$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			$args = array('offset'=> 0, 'paged'=>$paged);
			$all_posts = new WP_Query($args);
			while($all_posts->have_posts()) : $all_posts->the_post();
		?>
		
		<?php if($first){ 
			
			$first = false;?>
			<div id="post-<?php the_ID(); ?>" <?php post_class('first'); ?>>
				<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				<ul class="postInfoList">
					<li class="date"><strong><?php the_time('j'); ?></strong><span><?php the_time('S'); ?></span>&nbsp;<?php the_time('F Y'); ?></li>
					<li class="author"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php the_author(); ?></a></li>
					<li class="category"><?php the_category(','); ?></li>
					<li class="comments"><?php comments_number('0', '1', '%'); ?></li>
				</ul>
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail('large', array('class' => 'imgFrame splash'));?>
				</a>
				<p><?php rb_excerpt('', 'rb_excerptmore'); ?></p>
				<a class="read" href="<?php the_permalink(); ?>"><?php _e('Continue Reading...', 'corvius'); ?></a>
			</div>
			<hr />
			
		<?php } else { ?>
		
			<div id="post-<?php the_ID(); ?>" <?php post_class('secondary clearfix'); ?>>
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail('post-mini-thumb', array('class' => 'imgFrame'));?>
				</a>
				<div>
					<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					<ul class="postInfoList">
						<li class="date"><strong><?php the_time('j'); ?></strong><span><?php the_time('S'); ?></span>&nbsp;<?php the_time('F Y'); ?></li>
						<li class="author"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php the_author(); ?></a></li>
						<li class="category"><?php the_category(','); ?></li>
					</ul>
					<p><?php rb_excerpt('rb_excerptlength_post', 'rb_excerptmore'); ?></p>
					<a class="read" href="<?php the_permalink(); ?>"><?php _e('Continue Reading...', 'corvius'); ?>.</a>
				</div>
			</div>
		
		<?php } ?>
		
	<?php endwhile; ?>
	<?php endwhile; ?>
	
	<?php rb_pagination($all_posts->max_num_pages, 3) ?>
	
	</div>
	
	<?php if($post_layout == 'Right Sidebar'){
			?><div id="sidebar" class="grid_5 prefix_1 blog"><?php
					dynamic_sidebar($post_sidebar);
			?></div>
	<?php } ?>
	
	<?php get_footer(); ?>
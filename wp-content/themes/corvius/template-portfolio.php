<?php
/*---------------------------------
	Template Name: Portfolio
------------------------------------*/
 
	$values = get_post_custom( $post->ID );  
	$post_layout = isset( $values['rb_meta_box_layout'] ) ? esc_attr( $values['rb_meta_box_layout'][0] ) : '';  
	$post_sidebar = isset($values['rb_meta_box_sidebar']) ? esc_attr($values['rb_meta_box_sidebar'][0]) : '';
	
	get_header(); 
	$i = 0;
	
?>

	<div id="portfolio" class="grid_16 folio">	
		
		<h2>Some Of Our Work</h2>
		
		<!-- Portfolio filters --> 
		<ul id="folioFilters" class="folioFilters clearfix">
			<?php 
				$tags = get_terms('portfolio_tag', array('fields' => 'names'));
				echo '<li><a class="selected" href="#" rel="">', __('All', 'starkers'), '</a></li>';
				foreach($tags as $tag){
					echo '<li><a href="#" rel=".', strtolower($tag), '">', ucfirst($tag), '</a></li>';
				}
			?>
		</ul>
		
		<!-- Portfolio grid -->
		<ul id="folioDestination" class="folioGrid">
		
			<?php while (have_posts()) : the_post(); ?>
			
				<?php
					$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
					$args = array( 'posts_per_page' => -1, 
						   'offset'=> 0,
						   'post_type' => 'portfolio');

					$all_posts = new WP_Query($args);
					while($all_posts->have_posts()) : $all_posts->the_post();
				?>
				
					<?php 
						
						$portfolio_terms = wp_get_object_terms($post->ID, 'portfolio_tag');
						$portfolio_class = array();
						if(!empty($portfolio_terms)){
						  if(!is_wp_error( $portfolio_terms )){
							foreach($portfolio_terms as $term){
							  array_push($portfolio_class, strtolower($term->name)); 
							}
						  }
						}
						
					?>
				
					<li id="post-<?php the_ID(); ?>" <?php post_class($portfolio_class); ?>>
					
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail('portfolio-mini-thumb');?>
						</a>
						<div>
							<h3><?php the_title(); ?></h3>
							<p><?php rb_excerpt('rb_excerptlength_folio', 'rb_excerptmore'); ?></p>
						</div>
						
					</li>
					
				<?php endwhile; ?>
			<?php endwhile; ?>
	
		</ul>
		
		<ul id="folioSource" class="folioGrid clearfix">
			<!-- empty list to be filled by the script -->
			<li id="unique"><!-- nothing --></li>
		</ul>
	
	</div>
	
	<hr />
	
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	
		<?php the_content(); ?>
	
	<?php endwhile; ?>
	
	
	
	<?php get_footer(); ?>
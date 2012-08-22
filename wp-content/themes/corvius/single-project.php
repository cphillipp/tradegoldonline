<!--///////////////////////////////
	Single Project Page Template
////////////////////////////////////////-->

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	
	<div class="project">
	
		<div id="<?php echo the_ID(); ?>" <?php post_class('grid_16'); ?>>

			<h2><?php the_title(); ?></h2>
			
			<!-- Next/prev buttons -->
			<div class="projectNav">
				<div class="btnPrevPost"><?php echo next_post_link('%link', 'Previous', false); ?></div>
				<div class="btnNextPost"><?php echo previous_post_link('%link', 'Next', false); ?></div>
			</div>
			
			<div class="imgFrame">
				<?php if(get_post_meta($post->ID, 'rb_folio_image_meta1', 'true') != ''){
					//Single Image
					if(get_post_meta($post->ID, 'rb_folio_image_meta2', 'true') == ''){
						echo wp_get_attachment_image(get_post_meta($post->ID, 'rb_folio_image_meta1', 'true'), 'full');
					}else{
						//Image Slider
						?>
							<div id="projectSlider">
								<div class="slidesHolder">
								<?php
									echo wp_get_attachment_image(get_post_meta($post->ID, 'rb_folio_image_meta1', 'true'), 'full'); 
									echo wp_get_attachment_image(get_post_meta($post->ID, 'rb_folio_image_meta2', 'true'), 'full'); 
									if(get_post_meta($post->ID, 'rb_folio_image_meta3', 'true')) echo wp_get_attachment_image(get_post_meta($post->ID, 'rb_folio_image_meta3', 'true'), 'full'); 
									if(get_post_meta($post->ID, 'rb_folio_image_meta4', 'true')) echo wp_get_attachment_image(get_post_meta($post->ID, 'rb_folio_image_meta4', 'true'), 'full'); 
								?>
								</div>
								<div class="slidesControlsHolder"><hr /><div class="slidesControls"></div></div>
							</div>
						<?php
					}
				} else {
					//Custom video
					if(get_post_meta($post->ID, 'rb_folio_video_meta4', 'true') == ''){
						  ?>
							<video id="projectPlayer" class="projekktor" poster="<?php echo wp_get_attachment_url(get_post_meta($post->ID, 'rb_folio_video_meta1', 'true')); ?>" title="Big Buck Bunny" width="932" height="<?php echo get_post_meta($post->ID, 'rb_folio_video_meta5', 'true'); ?>" controls>
								<source src="<?php echo get_post_meta($post->ID, 'rb_folio_video_meta2', 'true'); ?>" type="video/ogg" />
								<source src="<?php echo get_post_meta($post->ID, 'rb_folio_video_meta3', 'true'); ?>" type="video/mp4" />
								<source src="<?php echo get_post_meta($post->ID, 'rb_folio_video_meta6', 'true'); ?>" type="video/webm" />
							</video>
						<?php
					}else{
						//Embedded Video
						echo get_post_meta($post->ID, 'rb_folio_video_meta4', 'true');
					}
				}
				?>
			</div>
		</div>
		
		<div class="grid_9">
			<?php the_content(); ?>
		</div>
		
		<!-- Project Info -->
		<div class="grid_6 prefix_1">
			<ul class="projectInfoList">
				<?php 
					if(get_post_meta($post->ID, 'rb_folio_info_meta1', 'true') != '')
						echo '<li><span>', __('Author:', 'corvius'), '</span>', get_post_meta($post->ID, 'rb_folio_info_meta1', 'true'), '</li>';
					if(get_post_meta($post->ID, 'rb_folio_info_meta2', 'true') != '')
						echo '<li><span>', __('Date:', 'corvius'), '</span>', get_post_meta($post->ID, 'rb_folio_info_meta2', 'true'), '</li>';
					if(get_post_meta($post->ID, 'rb_folio_info_meta3', 'true') != '')
						echo '<li><span>', __('Client:', 'corvius'), '</span>', get_post_meta($post->ID, 'rb_folio_info_meta3', 'true'), '</li>';
					if(get_post_meta($post->ID, 'rb_folio_info_meta4', 'true') != '')
						echo '<li><span>', __('URL:', 'corvius'), '</span><a href="', get_post_meta($post->ID, 'rb_folio_info_meta4', 'true') ,'">', get_post_meta($post->ID, 'rb_folio_info_meta4', 'true'), '</a></li>';
				?>
			</ul>
		</div>
		
		<hr />
		
		<!-- Related Projects -->
		<div class="grid_16">
		
			<h2><?php _e('Related Projects', 'corvius'); ?></h2>
		
			<ul class="folioGrid relatedFolio clearfix">
			
				<?php
				
					$tags = array();
			
					$portfolio_terms = wp_get_object_terms($post->ID, 'portfolio_tag');
					$tag = $portfolio_terms[0] -> name;
					if(!empty($portfolio_terms)){
						if(!is_wp_error( $portfolio_terms ))
							foreach($portfolio_terms as $term)
								array_push($tags, $term->name); 
					}
				
					endwhile;
			
					$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
					$args = array( 'posts_per_page' => 4, 
						   'offset'=> 0,
						   'post_type' => 'portfolio',
						   'portfolio_tag' => implode($tags, ',') );

					$all_posts = new WP_Query($args);
					while($all_posts->have_posts()) : $all_posts->the_post();
						
				?>
				
					<li id="re-<?php echo the_ID(); ?>" <?php post_class(); ?>>
					
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail('portfolio-mini-thumb');?>
						</a>
						<div>
							<h3><?php the_title(); ?></h3>
							<p><?php rb_excerpt('rb_excerptlength_folio', 'rb_excerptmore'); ?></p>
						</div>
						
					</li>
					
				<?php endwhile; ?>
			
			</ul>
		
		</div>
		
		
	
	</div>
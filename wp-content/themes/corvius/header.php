<?php
/*---------------------------------
	The header of the theme
------------------------------------*/
?><!DOCTYPE html>
<!--[if IE 7]>    <html <?php language_attributes(); ?> class="ie7 oldie boldie" xmlns="http://www.w3.org/1999/xhtml"> <![endif]-->
<!--[if IE 8]>    <html <?php language_attributes(); ?> class="ie8 oldie boldie" xmlns="http://www.w3.org/1999/xhtml"> <![endif]-->
<!--[if IE 9]>    <html <?php language_attributes(); ?> class="ie9 oldie" xmlns="http://www.w3.org/1999/xhtml"> <![endif]-->
<!--[if gt IE 9]><!--> <html <?php language_attributes(); ?> xmlns="http://www.w3.org/1999/xhtml"> <!--<![endif]-->
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="author" content="rubenbristian.com" />
	
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<meta name="description" content="<?php rb_excerpt('rb_excerptlength_widget', 'rb_excerptmore'); ?>">
	
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	
	<?php

	//Add comments script
	if(is_page() || is_home() || get_post_type() == 'portfolio'){
		wp_deregister_script('comment-reply');
	}else if(is_singular() && get_option( 'thread_comments')){
		wp_deregister_script('comment-reply');
		wp_enqueue_script('comment-reply', get_template_directory_uri().'/js/comment-reply-rb.js');
	} 
	
	wp_enqueue_style('main-style', get_template_directory_uri().'/css/styles.css', array(), NULL);
	wp_enqueue_style('dynamic-style', get_template_directory_uri().'/dynamic.css', array(), NULL);
	
	wp_head();

	// Get custom meta values & other page/post options
	$values = get_post_custom( $post->ID );  
	$post_tagline = isset($values['rb_meta_box_tagline']) ? esc_attr($values['rb_meta_box_tagline'][0]) : '';
	$post_action = isset($values['rb_meta_box_action']) ? esc_attr($values['rb_meta_box_action'][0]) : '';
	$post_action1 = isset($values['rb_meta_box_action3']) ? esc_attr($values['rb_meta_box_action3'][0]) : '';
	$post_action2 = isset($values['rb_meta_box_action4']) ? esc_attr($values['rb_meta_box_action4'][0]) : '';
	$slider = isset($values['rb_meta_box_slider']) ? esc_attr($values['rb_meta_box_slider'][0]) : '';
	
	$theme_options = get_option('option_tree');
	
	if(is_search() || is_archive() || is_404()){
		$post_tagline = (is_search() || is_archive() ? get_option_tree('rb_search_tagline', $theme_options, false) : get_option_tree('rb_404_tagline', $theme_options, false));
		$post_action = 'Search';
	}

	?>
	
	<!-- Favicon -->
	<link rel="icon" type="image/png" href="<?php 
		if(get_option_tree('rb_favicon', $theme_options) != '') {
			get_option_tree('rb_favicon', $theme_options, true);
		} else {
			get_template_directory_uri();
			echo '/favicon.ico';
		}	?>" />
		
</head>

<body <?php body_class(); ?>>
	
	<div id="header">
	
		<div id="top" class="gradient">
			<div class="container_16">
			
				<!-- Display Logo -->
				<a href="<?php echo home_url('/'); ?>"><img src="<?php 
						if(get_option_tree('rb_logo_path', $theme_options) != '') {
							get_option_tree('rb_logo_path', $theme_options, true);
						} else {
							get_template_directory_uri();
							echo '/images/logo.png';
						}	?>" alt="<?php echo get_bloginfo('name'); ?>" /></a>
				
				<?php
				
				//Display Menu
				wp_nav_menu( array(
					 'container' =>false,
					 'menu_class' => '',
					 'echo' => true,
					 'before' => '',
					 'after' => '',
					 'link_before' => '',
					 'link_after' => '',
					 'depth' => 2,
					 'walker' => new menu_default_walker())
				 );

				?>
				
			</div>
		</div>
		<?php if ( !is_page_template('template-home.php') && !is_page_template('template-homeblog.php') ) { ?>
		
			<!-- If this is not the home page(with a slider), then display the tagline and the CAT button/Search form -->
		
			<div id="tagline" class="gradient">
				<div class="container_16">
					<h1><?php
						if($post_tagline == '')
							the_title(); 
						else
							echo $post_tagline;
						?>
					</h1>
					<?php
						if($post_action == 'Search' || $post_action == 'Search form'){ 
							get_search_form();
						} else if($post_action == 'Call to action button') { ?>
							<a class="taglineButton" href="<?php echo $post_action2; ?>"><?php echo $post_action1; ?></a>
						<?php } ?>
				</div>
			</div>
			
		<?php } else { 
		
		//Display Parallaxy Slider
			 if($slider == 'Parallaxy Slider') { ?>
				<div id="parallaxSlider">
					<div<?php 
						if(get_option_tree('rb_parallaxy_back', $theme_options, false) != '')
							echo ' style="background-image:url(' . get_option_tree('rb_parallaxy_back', $theme_options, false) . ') !important"';
						?>>
						<div class="radial gradient">
							<div class="evoTag"><p class="hidden sliderTimer"><?php get_option_tree('rb_parallaxy_timer', $theme_options, true); ?></p></div>
							<div class="slidesHolder">
								<?php
								$slides = get_option_tree( 'rb_parallaxy_slides', $theme_options, false, true );
								if(isset($slides))
								  foreach( $slides as $slide ) {
									if($slide['offset'] != '' && is_numeric($slide['offset']))
										echo '
											<div>
											  <p><img style="margin-left:'.$slide['offset'].'px " src="'.$slide['image'].'" alt="'.$slide['title'].'" /></p>
											  <h1>'.$slide['description'].'</h1>
											</div>';
									else
										echo '
										<div>
										  <img src="'.$slide['image'].'" alt="'.$slide['title'].'" />
										  <h1>'.$slide['description'].'</h1>
										</div>';
								  }
								?>
								<div class="sliderActions clearfix">
									<?php
									if(get_option_tree('rb_parallaxy_action11', $theme_options, false) != '')
										echo '<a class="button large black" href="' . get_option_tree('rb_parallaxy_action12', $theme_options, false) . '" target="_blank">' . get_option_tree('rb_parallaxy_action11', $theme_options, false) . '</a>';
									if(get_option_tree('rb_parallaxy_action3', $theme_options, false) != '')
										echo '<p>' . get_option_tree('rb_parallaxy_action3', $theme_options, false) . '</p>';
									if(get_option_tree('rb_parallaxy_action21', $theme_options, false) != '')
										echo '<a class="button large transparent" href="' . get_option_tree('rb_parallaxy_action22', $theme_options, false) . '" target="_blank">' . get_option_tree('rb_parallaxy_action21', $theme_options, false) . '</a>';
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			<!-- Display Circles Slider -->
			<?php } else if($slider == 'Circles Slider') { ?>
			
				<div id="circlesSlider">
					<div<?php 
						if(get_option_tree('rb_circles_back', $theme_options, false) != '') 
							echo ' style="background-image:url(' . get_option_tree('rb_circles_back', $theme_options, false) . ') !important"';
						?>>
						<div class="radial gradient">
							<div class="slidesHolder">
								<?php
								$slides = get_option_tree('rb_circles_slides', $theme_options, false, true);
								 if(isset($slides))
									foreach($slides as $slide){
										echo '
											<div class="slide">
												<div class="first">
													<div><img src="'.$slide['image1'].'" alt="'.$slide['title'].'" /></div>
												</div>
												<div class="second">
													<div><img src="'.$slide['image2'].'" alt="'.$slide['title2'].'" /></div>
												</div>
												<div class="third">
													<div><img src="'.$slide['image3'].'" alt="'.$slide['title3'].'" /></div>
												</div>
												<div class="fourth">
													<div><img src="'.$slide['image4'].'" alt="'.$slide['title4'].'" /></div>
												</div>
												<h1>'.$slide['description'].'</h1>
											</div>';
									}
								?>
							</div>
							<div class="sliderButtons">
								<a class="btnPrev" href="#">Previous</a>
								<a class="btnNext" href="#">Next</a>
								<p class="hidden sliderTimer"><?php get_option_tree('rb_circles_timer', $theme_options, true); ?></p>
							</div>
							<div class="slidesControls">
							</div>
						</div>
					</div>
				</div>
		
			<!-- Display Parallaxy Slider -->
			<?php } else if($slider == 'Fading Slider'){ ?>
				<div id="fadingSlider">
					<div<?php 
						if(get_option_tree('rb_nivo_back', $theme_options, false) != '') 
							echo ' style="background-image:url(' . get_option_tree('rb_nivo_back', $theme_options, false) . ') !important"';
						?>>
						<div class="radial gradient">
							<div class="slidesHolder">
								<?php
									$slides = get_option_tree('rb_nivo_slides', $theme_options, false, true);
									 if(isset($slides))
										foreach($slides as $slide){
											echo '
												<div class="slide">
													<img src="' . $slide['image'] . '" alt="' . $slide['title'] . '" />
													<h1>' . $slide['description'] . '</h1>
												</div>';
										}
								?>
							</div>
							<div class="slidesControls">
							</div>
							<p class="hidden sliderTimer"><?php get_option_tree('rb_nivo_timer', $theme_options, true); ?></p>
						</div>
					</div>
				</div>
			<?php }
				}
			?>
		
	</div>
		
	<div id="content">
		<div class="container_16">
		
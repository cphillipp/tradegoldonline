<?php

require TEMPLATEPATH . '/option-tree/index.php';
require TEMPLATEPATH . '/includes/update-notifier.php';
load_theme_textdomain( 'corvius', TEMPLATEPATH.'/lang' );


/*********************************************************************

	This file contains the most important functions of the theme. Edit carefully! :)

*********************************************************************/

/*---------------------------------
	Make some adjustments on theme setup
------------------------------------*/

if ( ! function_exists( 'corvius_setup' ) ):
	function corvius_setup() {
	
		//Define content width
		if(!isset($content_width)) $content_width = 940;

		//This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();
		
		//This theme uses post thumbnails
		add_theme_support( 'post-thumbnails' );
		
		//Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );
		
		//Make theme available for translation
		load_theme_textdomain( 'corvius', TEMPLATEPATH . '/languages' );
		$locale = get_locale();
		$locale_file = TEMPLATEPATH . "/languages/$locale.php";
		if ( is_readable( $locale_file ) )
			require_once( $locale_file );
			
		//This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => __( 'Primary Navigation', 'corvius' ),
		) );

		//Define custom thumbnails
		set_post_thumbnail_size( 574, 175, true );
		add_image_size( 'post-mini-thumb',  123, 123, true );
		add_image_size( 'portfolio-mini-thumb',  212, 212, true );
		
	}
endif;
add_action( 'after_setup_theme', 'corvius_setup' );

/*---------------------------------
	Make some changes to the wp_title() function
------------------------------------*/

function corvius_filter_wp_title( $title, $separator ) {

	if ( is_feed() ) return $title;
		
	global $paged, $page;

	if ( is_search() ) {
	
		//If we're a search, let's start over:
		$title = sprintf( __( 'Search results for %s', 'corvius' ), '"' . get_search_query() . '"' );
		//Add a page number if we're on page 2 or more:
		if ( $paged >= 2 )
			$title .= " $separator " . sprintf( __( 'Page %s', 'corvius' ), $paged );
		//Add the site name to the end:
		$title .= " $separator " . get_bloginfo( 'name', 'display' );
		//We're done. Let's send the new title back to wp_title():
		return $title;
	}

	//Otherwise, let's start by adding the site name to the end:
	$title .= get_bloginfo( 'name', 'display' );

	//If we have a site description and we're on the home/front page, add the description:
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $separator " . $site_description;

	//Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $separator " . sprintf( __( 'Page %s', 'corvius' ), max( $paged, $page ) );

	//Return the new title to wp_title():
	return $title;
}
add_filter( 'wp_title', 'corvius_filter_wp_title', 10, 2 );

/*---------------------------------
	Create a wp_nav_menu() fallback, to show a home link
------------------------------------*/

function corvius_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'corvius_page_menu_args' );

/*---------------------------------
	Comments template
------------------------------------*/

if ( ! function_exists( 'corvius_comment' ) ) :
	function corvius_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case '' :
		?>
		<li id="li-comment-<?php comment_ID(); ?>">
			<div id="comment-<?php comment_ID(); ?>" class="comment">
			
				<?php echo get_avatar( $comment, 92 ); ?>
				<div class="commentHeader">
					<p>
					<?php 
						if(get_comment_author_url() != 'http://Yourwebsite...')
							echo comment_author_link();
						else
							comment_author();
					?>,
						<strong><?php echo comment_date('j'); ?></strong><span><?php comment_date('S'); ?></span>&nbsp;<?php echo comment_date('l Y'); ?> at <?php comment_time(); ?>
					</p>
					<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => 3 ) ) ); ?>
				</div>
				<div class="commentBody">
					<p><?php comment_text(); ?></p>
				</div>
				
				<?php if ( $comment->comment_approved == '0' ) : ?>
				<em class="await"><?php _e( 'Your comment is awaiting moderation.', 'corvius' ); ?></em>
				<?php endif; ?>

			</div>
		</li>

		<?php
			break;
			case 'pingback'  :
			case 'trackback' :
		?>
		
		<li class="post pingback">
			<p><?php _e( 'Pingback:', 'corvius' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'corvius'), ' ' ); ?></p></li>
		<?php
				break;
		endswitch;
	}
endif;

/*---------------------------------
	Register widget areas
------------------------------------*/

function rb_widgets_init() {

	register_sidebar( array(
		'name' => __('Post Default Sidebar', 'corvius'),
		'id' => 'rb_post_sidebar',
		'description' => __('The default sidebar for all posts & blog template', 'corvius'),
		'before_widget' => '<div id="%1$s" class="widget clearfix %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	) );

	register_sidebar( array(
		'name' => __('Page Default Sidebar', 'corvius'),
		'id' => 'rb_page_sidebar',
		'description' => __('The default sidebar for all pages', 'corvius'),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __('Footer Middle Area Widget 1', 'corvius'),
		'id' => 'rb_footer_middle1',
		'description' => __('The first widget area in the middle area of the footer', 'corvius'),
		'before_widget' => '<div id="%1$s" class="grid_5 %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );
	
	register_sidebar( array(
		'name' => __('Footer Middle Area Widget 2', 'corvius'),
		'id' => 'rb_footer_middle2',
		'description' => __('The second widget area in the middle area of the footer', 'corvius'),
		'before_widget' => '<div id="%1$s" class="grid_5 prefix_1 %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );
	
	register_sidebar( array(
		'name' => __('Footer Middle Area Widget 3', 'corvius'),
		'id' => 'rb_footer_middle3',
		'description' => __('The third widget area in the middle area of the footer', 'corvius'),
		'before_widget' => '<div id="%1$s" class="grid_4 prefix_1 %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );
	
	register_sidebar( array(
		'name' => __('Footer Bottom Area Widget', 'corvius'),
		'id' => 'rb_footer_bottom',
		'description' => __('The widget area for the bottom area of the footer\'s left side', 'corvius'),
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title hidden">',
		'after_title' => '</h2>',
	) );
	
	register_sidebar( array(
		'name' => __('Contact Page Widget', 'corvius'),
		'id' => 'rb_contact',
		'description' => __('The widget area from the right of the contact form', 'corvius'),
		'before_widget' => '<div class="large">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );
	
}
add_action( 'widgets_init', 'rb_widgets_init' );

/*---------------------------------
	Register custom sidebar areas
------------------------------------*/

$sidebars = get_option_tree( 'rb_sidebars', '', false, true);
if(isset($sidebars))
	foreach($sidebars as $sidebar) {
		register_sidebar( array(
			'name' => $sidebar['title'],
			'id' => strtolower(str_replace(' ', '_',$sidebar['title'])),
			'description' => '',
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		));
	}

/*---------------------------------
	Remove "Recent Comments Widget" Styling
------------------------------------*/

function corvius_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'corvius_remove_recent_comments_style' );


/*---------------------------------
	Function that replaces the default the_excerpt() function
------------------------------------*/
 
function rb_excerptlength_folio($length) {
    return 7;
}
function rb_excerptlength_widget($length) {
    return 18;
}
function rb_excerptlength_post($length) {
    return 30;
}
function rb_excerptmore($more) {
    return ' ...';
}
	
function rb_excerpt($length_callback='', $more_callback='') {

    global $post;
	
    if(function_exists($length_callback)){
		add_filter('excerpt_length', $length_callback);
    }
	
    if(function_exists($more_callback)){
		add_filter('excerpt_more', $more_callback);
    }
	
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = $output;
	
    echo $output;
	
}   

/*---------------------------------
	Function that replaces the default get_the_excerpt() function(only for shortcodes)
------------------------------------*/

function rb_get_excerpt($excerpt, $charlength) {

	$charlength++;
	$content = '';

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			$content .= mb_substr( $subex, 0, $excut );
		} else {
			$content .= $subex;
		}
		$content .= ' ...';
	} else {
		$content .= $excerpt;
	}

	return $content;
	
}

/*---------------------------------
	Function that refilters the get_the_excerpt() function
------------------------------------*/

function rb_refilter_excerpt( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output = preg_replace('/<a[^>]+>Continue reading.*?<\/a>/i','',$output);
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'rb_refilter_excerpt' );

/*---------------------------------
	Enable excerpts for pages
------------------------------------*/
add_post_type_support( 'page', 'excerpt' );

/*---------------------------------
	Add a custom class to the user's gravatar
------------------------------------*/

function change_avatar_css($class) {
	$class = str_replace("class='avatar", "class='imgFrame", $class) ;
	return $class;
}
add_filter('get_avatar','change_avatar_css');

/*---------------------------------
	Redefine the search form structure
------------------------------------*/

function rb_search_form( $form ) {

    $form = '
	<form role="search" method="get" id="searchform" class="searchBox" action="' . home_url( '/' ) . '" >
		<label class="screen-reader-text hidden" for="s">' . __('Search for:', 'corvius') . '</label>
		<input type="text" data-value="Search..." value="Search..." name="s" id="s" />
		<!--<input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />-->
    </form>';
    return $form;
	
}
add_filter( 'get_search_form', 'rb_search_form' );

/*---------------------------------
	A custom pagination function
------------------------------------*/

function rb_pagination($pages = '', $range = 2) {  

     $showitems = ($range * 2)+1;  

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '') {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages) {
             $pages = 1;
         }
     }   

     if(1 != $pages) {
         echo "<ul class='pagination clearfix'>";

         for ($i=1; $i <= $pages; $i++) {
		 
			if($i==1 || $i==$pages || $i==$paged || ($i>=$paged-$range && $i<=$paged+$range)){
				echo ($paged == $i)? "<li><a class='selected'>".$i."</a></li>":"<li><a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a></li>";
			} else if(($i!=1 && $i==$paged-$range-1) || ($i!=$paged && $i==$paged+$range+1)) {
				echo '<li>...</li>';
			}
			
         }

         if($paged > 1) 
			echo "<li><a class='btnPrev' href='".get_pagenum_link($paged - 1)."'>Previous</a></li>";
         if ($paged < $pages) 
			echo "<li><a class='btnNext' href='".get_pagenum_link($paged + 1)."'>Next</a></li>";  
			
         echo "</ul>";
     }
	 
}

/*---------------------------------
	Count post views
------------------------------------*/

function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return 0;
    }
    return $count;
}

function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

/*---------------------------------
	Deal with WP empty paragraphcs
------------------------------------*/

function rb_formatter($content) {
	
	$bad_content = array('<p></div></p>', '<p><div class="grid', ' is_grid"></p>', '</div></p>', '<p><ul', '</ul></p>', '<p><div', '<p><block', 'quote></p>', '<p><hr /></p>', '<p><table>', '<td></p>', '<p></td>', '</table></p>', '<p></div>', 'nosidebar"></p>', '<p><p>');
	$good_content = array('</div>', '<div class="grid', '">', '</div>', '<ul', '</ul>', '<div', '<block', 'quote>', '<hr />', '<table>', '<td>', '</td>', '</table>', '</div>', 'nosidebar">', '<p>');
	
	$new_content = str_replace($bad_content, $good_content, $content);
	return $new_content;
	
}

remove_filter('the_content', 'wpautop');
add_filter('the_content', 'wpautop', 10);
add_filter('the_content', 'rb_formatter', 11);


/*---------------------------------
	Setup homepage sliders
------------------------------------*/

add_filter( 'image_slider_fields', 'new_slider_fields', 10, 2 );
function new_slider_fields( $image_slider_fields, $id ) {
  if ( $id == 'rb_parallaxy_slides' ) {
    $image_slider_fields = array(
      array(
        'name'  => 'image',
        'type'  => 'text',
        'label' => 'Post Image URL',
        'class' => ''
      ),
		array(
		  'name'  => 'title',
		  'type'  => 'text',
		  'label' => 'Alt tag',
		  'class' => 'option-tree-slider-title'
		),
      array(
        'name'  => 'offset',
        'type'  => 'text',
        'label' => 'Image left offset(in px - default 0)',
        'class' => ''
      ),
      array(
        'name'  => 'description',
        'type'  => 'textarea',
        'label' => 'Post Caption',
        'class' => ''
      )
    );
  } else if($id == 'rb_nivo_slides') {
	$image_slider_fields = array(
		array(
        'name'  => 'image',
        'type'  => 'text',
        'label' => 'Post Image URL',
        'class' => ''
      ),
		array(
		  'name'  => 'title',
		  'type'  => 'text',
		  'label' => 'Alt tag',
		  'class' => 'option-tree-slider-title'
		),
      array(
        'name'  => 'description',
        'type'  => 'textarea',
        'label' => 'Post Caption',
        'class' => ''
      )
	);
  } else if ($id == 'rb_parallaxy_buttons'){
		$image_slider_fields = array(
			array(
			  'name'  => 'title',
			  'type'  => 'text',
			  'label' => 'Button text',
			  'class' => 'option-tree-slider-title'
			),
			array(
				'name' => 'transparent',
				'type' => 'checkbox',
				'label' => 'Check if you want the button to be transparent',
				'class' => ''
			),
			array(
				'name' => 'textorbutton',
				'type' => 'checkbox',
				'label' => 'Check if you just want a text line and not a button',
				'class' => 'rbjQueryAction',
				'data-actions' => 'close,next,2'
			),
			array(
			  'name'  => 'link',
			  'type'  => 'text',
			  'label' => 'Button link',
			  'class' => ''
			),
			array(
			  'name'  => 'target',
			  'type'  => 'text',
			  'label' => 'Link target',
			  'class' => ''
			)
		);
  } else if ($id == 'rb_circles_slides'){
    $image_slider_fields = array(
      array(
        'name'  => 'image1',
        'type'  => 'image',
        'label' => 'Post URL for the first image',
        'class' => ''
      ),
		array(
		  'name'  => 'title',
		  'type'  => 'text',
		  'label' => 'Write an alt tag for the first image',
		  'class' => 'option-tree-slider-title'
		),
      array(
        'name'  => 'image2',
        'type'  => 'image',
        'label' => 'Post URL for the second image',
        'class' => ''
      ),
		array(
		  'name'  => 'title2',
		  'type'  => 'text',
		  'label' => 'Write an alt tag for the second image',
		  'class' => ''
		),
      array(
        'name'  => 'image3',
        'type'  => 'image',
        'label' => 'Post URL for the third image',
        'class' => ''
      ),
		array(
		  'name'  => 'title3',
		  'type'  => 'text',
		  'label' => 'Write an alt tag for the third image',
		  'class' => ''
		),
      array(
        'name'  => 'image4',
        'type'  => 'image',
        'label' => 'Post URL for the fourth image',
        'class' => ''
      ),
		array(
		  'name'  => 'title4',
		  'type'  => 'text',
		  'label' => 'Write an alt tag for the fourth image',
		  'class' => ''
		),
      array(
        'name'  => 'description',
        'type'  => 'textarea',
        'label' => 'Write Caption',
        'class' => ''
      )
    );
  }else if ($id == 'rb_sidebars'){
		$image_slider_fields = array(
			array(
			  'name'  => 'title',
			  'type'  => 'text',
			  'label' => 'Sidebar name',
			  'class' => 'option-tree-slider-title'
			)
		);
  }
  return $image_slider_fields;
}

/*---------------------------------
	Save css code(mainly colors)
------------------------------------*/
  
function rb_save_ot(){
	
	//open and read css file
	$stylesheet_path = '../wp-content/themes/corvius/css/styles.css';
	$stylesheet = fopen($stylesheet_path, 'r');
	$data = fread($stylesheet, filesize($stylesheet_path));
	fclose($stylesheet);
	
	//make changes inside the css file
	$data = replace_mark('/*rb_color_accent*/', $data, get_option_tree( 'rb_color_accent', '', false));
	$data = replace_mark('/*rb_color_accent2*/', $data, get_option_tree( 'rb_color_accent2', '', false));
	$data = replace_mark('/*rb_color_footer*/', $data, get_option_tree( 'rb_color_footer', '', false));
	$data = replace_mark('/*rb_color_link*/', $data, get_option_tree( 'rb_color_link', '', false));
	$data = replace_mark('/*rb_color_hover*/', $data, get_option_tree( 'rb_color_hover', '', false));
	$data = replace_mark('/*rb_color_marked*/', $data, get_option_tree( 'rb_color_marked', '', false));
	$data = replace_mark('/*rb_color_slider*/', $data, get_option_tree( 'rb_color_slider', '', false));
	
	//save and close css file
	$stylesheet = fopen($stylesheet_path, 'w');
	fwrite($stylesheet, $data);
	fclose($stylesheet);
	
	//open and read contact-form file
	$contactform_path = '../wp-content/themes/corvius/contact-form.php';
	$contactform = fopen($contactform_path, 'r');
	$data = fread($contactform, filesize($contactform_path));
	fclose($contactform);
	
	//make changes inside the contact-form file
	$data = replace_mark('/*rb_form_your_email*/', $data, '"' . get_option_tree( 'rb_form_your_email', '', false) . '"');
	
	//save and close contact-form file
	$contactform = fopen($contactform_path, 'w');
	fwrite($contactform, $data);
	fclose($contactform);
		
}

function replace_mark($mark, $text, $replacement){
	$position = strpos($text, $mark);
	return str_replace(substr($text, $position, strpos($text, '/*e', $position)-$position), $mark.$replacement, $text);
}


/*---------------------------------
	Redefine menu structure with a walker class
------------------------------------*/

class menu_default_walker extends Walker_Nav_Menu
{

	function start_lvl(&$output, $depth){
		$output .= '<div><ul>';
	}

  function start_el(&$output, $item, $depth, $args) {
		global $wp_query;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		if ( !get_post_meta( $item->object_id , '_members_only' , true ) || is_user_logged_in() ) {
			$output .= $indent . '<li id="menu-item-'. $item->ID . '">';
		}

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		if ( !get_post_meta( $item->object_id, '_members_only' , true ) || is_user_logged_in() ) {
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}

	function end_el(&$output, $item, $depth) {
		if ( !get_post_meta( $item->object_id, '_members_only' , true ) || is_user_logged_in() ) {
			$output .= "</li>\n";
		}
	}
	
	 function end_lvl(&$output, $depth) {

		  $output .= "</ul></div>\n";
		  
	}
	
}

/*---------------------------------
	Custom login logo
------------------------------------*/

function rb_custom_login_logo() {
    echo '<style type="text/css">
        h1 a { background-image:url('.get_template_directory_uri().'/images/customLoginLogo.png) !important; }
    </style>';
}
add_action('login_head', 'rb_custom_login_logo');

/*---------------------------------
	Custom gravatar
------------------------------------*/

function rb_gravatar ($avatar_defaults) {
	$myavatar = get_template_directory_uri() . '/images/customGravatar.png';
	$avatar_defaults[$myavatar] = 'Corvius Gravatar';
	return $avatar_defaults;
}
add_filter('avatar_defaults', 'rb_gravatar');

/*---------------------------------
	Add author social icons/links
------------------------------------*/

function rb_author_socials( $contactmethods ) {
	$contactmethods['twitter'] = 'Twitter';
	$contactmethods['facebook'] = 'Facebook';
	$contactmethods['dribbble'] = 'Dribbble';
	$contactmethods['vimeo'] = 'Vimeo';
	$contactmethods['youtube'] = 'Youtube';

	return $contactmethods;
}
add_filter('user_contactmethods','rb_author_socials',10,1);

/*---------------------------------
	Fix empty search issue
------------------------------------*/

function rb_request_filter( $query_vars ) {
    if( isset( $_GET['s'] ) && empty( $_GET['s'] ) ) {
        $query_vars['s'] = " ";
    }
    return $query_vars;
}
add_filter('request', 'rb_request_filter');

/*---------------------------------
	Enqueue custom admin scripts & styles
------------------------------------*/
  
function rb_add_admin_stuff(){
	wp_register_style('rb_custom_admin_styles', get_template_directory_uri(). '/css/admin_styles.css');
	wp_enqueue_style('rb_custom_admin_styles');
	wp_register_script('rb_custom_admin_scripts', get_template_directory_uri(). '/js/admin_scripts.js');
	wp_enqueue_script('rb_custom_admin_scripts');
}
add_action( 'admin_init', 'rb_add_admin_stuff' );

/*---------------------------------
	Include other functions and classes
------------------------------------*/

include('includes/metaboxes.php');
include('includes/shortcodes.php');
include('includes/portfolio.php');
include('includes/widget.php');
include('includes/newsletter.php');
  
?>
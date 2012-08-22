<?php 

/*-----------------------------------------------------------------------------------

	Plugin Name: RPortfolio Post Type
	Plugin URI: http://www.wptheming.com
	Description: Enables a portfolio post type and taxonomies
	Version: 0.3
	Author: Devin Price
	Author URI: http://wptheming.com/portfolio-post-type/
	License: GPLv2

-----------------------------------------------------------------------------------*/

function portfolioposttype_activation() {
	portfolioposttype();
	flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'portfolioposttype_activation' );

function portfolioposttype() {

	$labels = array(
		'name' => __( 'Portfolio', 'corvius' ),
		'singular_name' => __( 'Project', 'corvius' ),
		'add_new' => __( 'Add New', 'corvius' ),
		'add_new_item' => __( 'Add New Project', 'corvius' ),
		'edit_item' => __( 'Edit Project', 'corvius' ),
		'new_item' => __( 'Add New', 'corvius' ),
		'view_item' => __( 'View Project', 'corvius' ),
		'search_items' => __( 'Search Portfolio', 'corvius' ),
		'not_found' => __( 'No projects found', 'corvius' ),
		'not_found_in_trash' => __( 'No projects items found in trash', 'corvius' )
	);

	$args = array(
    	'labels' => $labels,
    	'public' => true,
		'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields', 'revisions' ),
		'capability_type' => 'post',
		'rewrite' => array("slug" => "portfolio"), // Permalinks format
		'menu_position' => 5,
		//'register_meta_box_cb' => 'rb_portfolio_meta_init',
		'has_archive' => true
	); 

	register_post_type( 'portfolio', $args );
	
	$taxonomy_portfolio_tag_labels = array(
		'name' => _x( 'Portfolio Tags', 'corvius' ),
		'singular_name' => _x( 'Portfolio Tag', 'corvius' ),
		'search_items' => _x( 'Search Portfolio Tags', 'corvius' ),
		'popular_items' => _x( 'Popular Portfolio Tags', 'corvius' ),
		'all_items' => _x( 'All Portfolio Tags', 'corvius' ),
		'parent_item' => _x( 'Parent Portfolio Tag', 'corvius' ),
		'parent_item_colon' => _x( 'Parent Portfolio Tag:', 'corvius' ),
		'edit_item' => _x( 'Edit Portfolio Tag', 'corvius' ),
		'update_item' => _x( 'Update Portfolio Tag', 'corvius' ),
		'add_new_item' => _x( 'Add New Portfolio Tag', 'corvius' ),
		'new_item_name' => _x( 'New Portfolio Tag Name', 'corvius' ),
		'separate_items_with_commas' => _x( 'Separate portfolio tags with commas', 'corvius' ),
		'add_or_remove_items' => _x( 'Add or remove portfolio tags', 'corvius' ),
		'choose_from_most_used' => _x( 'Choose from the most used portfolio tags', 'corvius' ),
		'menu_name' => _x( 'Portfolio Tags', 'corvius' )
	);
	
	$taxonomy_portfolio_tag_args = array(
		'labels' => $taxonomy_portfolio_tag_labels,
		'public' => true,
		'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_tagcloud' => true,
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'portfolio-tag' ),
		'query_var' => true
	);
	
	register_taxonomy( 'portfolio_tag', array( 'portfolio' ), $taxonomy_portfolio_tag_args );
	
	
}

add_action( 'init', 'portfolioposttype' );
add_theme_support( 'post-thumbnails', array( 'portfolio' ) );
 
function portfolioposttype_edit_columns($portfolio_columns){
	$portfolio_columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => __('Title', 'column name'),
		"portfolio_tag" => __('Tags', 'corvius'),
		"author" => __('Author', 'corvius'),
		"date" => __('Date', 'corvius'),
	);
	$portfolio_columns['comments'] = '<div class="vers"><img alt="Comments" src="' . esc_url( admin_url( 'images/comment-grey-bubble.png' ) ) . '" /></div>';
	return $portfolio_columns;
}
add_filter( 'manage_edit-portfolio_columns', 'portfolioposttype_edit_columns' );
 
function portfolioposttype_columns_display($portfolio_columns, $post_id){

	switch ( $portfolio_columns )
	
	{
		// Code from: http://wpengineer.com/display-post-thumbnail-post-page-overview
			
			case "portfolio_tag":
			
			if ( $tag_list = get_the_term_list( $post_id, 'portfolio_tag', '', ', ', '' ) ) {
				echo $tag_list;
			} else {
				echo __('None', 'corvius');
			}
			break;			
	}
}

add_action( 'manage_posts_custom_column',  'portfolioposttype_columns_display', 10, 2 );

function add_portfolio_counts() {
        if ( ! post_type_exists( 'portfolio' ) ) {
             return;
        }

        $num_posts = wp_count_posts( 'portfolio' );
        $num = number_format_i18n( $num_posts->publish );
        $text = _n( 'Project', 'Projects', intval($num_posts->publish) );
        if ( current_user_can( 'edit_posts' ) ) {
            $num = "<a href='edit.php?post_type=portfolio'>$num</a>";
            $text = "<a href='edit.php?post_type=portfolio'>$text</a>";
        }
        echo '<td class="first b b-portfolio">' . $num . '</td>';
        echo '<td class="t portfolio">' . $text . '</td>';
        echo '</tr>';

        if ($num_posts->pending > 0) {
            $num = number_format_i18n( $num_posts->pending );
            $text = _n( 'Project Pending', 'Projects Pending', intval($num_posts->pending) );
            if ( current_user_can( 'edit_posts' ) ) {
                $num = "<a href='edit.php?post_status=pending&post_type=portfolio'>$num</a>";
                $text = "<a href='edit.php?post_status=pending&post_type=portfolio'>$text</a>";
            }
            echo '<td class="first b b-portfolio">' . $num . '</td>';
            echo '<td class="t portfolio">' . $text . '</td>';

            echo '</tr>';
        }
}

add_action( 'right_now_content_table_end', 'add_portfolio_counts' );

function portfolioposttype_add_help_text( $contextual_help, $screen_id, $screen ) { 
	if ( 'portfolio' == $screen->id ) {
		$contextual_help =
		'<p>' . __('The title field and the big Post Editing Area are fixed in place, but you can reposition all the other boxes using drag and drop, and can minimize or expand them by clicking the title bar of each box. Use the Screen Options tab to unhide more boxes (Excerpt, Send Trackbacks, Custom Fields, Discussion, Slug, Author) or to choose a 1- or 2-column layout for this screen.', 'corvius') . '</p>' .
		'<p>' . __('<strong>Title</strong> - Enter a title for your post. After you enter a title, you&#8217;ll see the permalink below, which you can edit.', 'corvius') . '</p>' .
		'<p>' . __('<strong>Post editor</strong> - Enter the text for your post. There are two modes of editing: Visual and HTML. Choose the mode by clicking on the appropriate tab. Visual mode gives you a WYSIWYG editor. Click the last icon in the row to get a second row of controls. The HTML mode allows you to enter raw HTML along with your post text. You can insert media files by clicking the icons above the post editor and following the directions. You can go the distraction-free writing screen, new in 3.2, via the Fullscreen icon in Visual mode (second to last in the top row) or the Fullscreen button in HTML mode (last in the row). Once there, you can make buttons visible by hovering over the top area. Exit Fullscreen back to the regular post editor.', 'corvius') . '</p>' .
		'<p>' . __('<strong>Publish</strong> - You can set the terms of publishing your post in the Publish box. For Status, Visibility, and Publish (immediately), click on the Edit link to reveal more options. Visibility includes options for password-protecting a post or making it stay at the top of your blog indefinitely (sticky). Publish (immediately) allows you to set a future or past date and time, so you can schedule a post to be published in the future or backdate a post.', 'corvius') . '</p>' .
		( ( current_theme_supports( 'post-formats' ) && post_type_supports( 'post', 'post-formats' ) ) ? '<p>' . __( '<strong>Post Format</strong> - This designates how your theme will display a specific post. For example, you could have a <em>standard</em> blog post with a title and paragraphs, or a short <em>aside</em> that omits the title and contains a short text blurb. Please refer to the Codex for <a href="http://codex.wordpress.org/Post_Formats#Supported_Formats">descriptions of each post format</a>. Your theme could enable all or some of 10 possible formats.' ) . '</p>' : '' ) .
		'<p>' . __('<strong>Featured Image</strong> - This allows you to associate an image with your post without inserting it. This is usually useful only if your theme makes use of the featured image as a post thumbnail on the home page, a custom header, etc.', 'corvius') . '</p>' .
		'<p>' . __('<strong>Send Trackbacks</strong> - Trackbacks are a way to notify legacy blog systems that you&#8217;ve linked to them. Enter the URL(s) you want to send trackbacks. If you link to other WordPress sites they&#8217;ll be notified automatically using pingbacks, and this field is unnecessary.', 'corvius') . '</p>' .
		'<p>' . __('<strong>Discussion</strong> - You can turn comments and pings on or off, and if there are comments on the post, you can see them here and moderate them.', 'corvius') . '</p>' .
		'<p><strong>' . __('For more information:', 'corvius') . '</strong></p>' .
		'<p>' . __('<a href="http://codex.wordpress.org/Posts_Add_New_Screen" target="_blank">Documentation on Writing and Editing Posts</a>', 'corvius') . '</p>' .
		'<p>' . __('<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>', 'corvius') . '</p>';
  } elseif ( 'edit-portfolio' == $screen->id ) {
    $contextual_help = 
	    '<p>' . __('You can customize the display of this screen in a number of ways:', 'corvius') . '</p>' .
		'<ul>' .
		'<li>' . __('You can hide/display columns based on your needs and decide how many posts to list per screen using the Screen Options tab.', 'corvius') . '</li>' .
		'<li>' . __('You can filter the list of posts by post status using the text links in the upper left to show All, Published, Draft, or Trashed posts. The default view is to show all posts.', 'corvius') . '</li>' .
		'<li>' . __('You can view posts in a simple title list or with an excerpt. Choose the view you prefer by clicking on the icons at the top of the list on the right.', 'corvius') . '</li>' .
		'<li>' . __('You can refine the list to show only posts in a specific category or from a specific month by using the dropdown menus above the posts list. Click the Filter button after making your selection. You also can refine the list by clicking on the post author, category or tag in the posts list.', 'corvius') . '</li>' .
		'</ul>' .
		'<p>' . __('Hovering over a row in the posts list will display action links that allow you to manage your post. You can perform the following actions:', 'corvius') . '</p>' .
		'<ul>' .
		'<li>' . __('Edit takes you to the editing screen for that post. You can also reach that screen by clicking on the post title.', 'corvius') . '</li>' .
		'<li>' . __('Quick Edit provides inline access to the metadata of your post, allowing you to update post details without leaving this screen.', 'corvius') . '</li>' .
		'<li>' . __('Trash removes your post from this list and places it in the trash, from which you can permanently delete it.', 'corvius') . '</li>' .
		'<li>' . __('Preview will show you what your draft post will look like if you publish it. View will take you to your live site to view the post. Which link is available depends on your post&#8217;s status.', 'corvius') . '</li>' .
		'</ul>' .
		'<p>' . __('You can also edit multiple posts at once. Select the posts you want to edit using the checkboxes, select Edit from the Bulk Actions menu and click Apply. You will be able to change the metadata (categories, author, etc.) for all selected posts at once. To remove a post from the grouping, just click the x next to its name in the Bulk Edit area that appears.', 'corvius') . '</p>' .
		'<p><strong>' . __('For more information:', 'corvius') . '</strong></p>' .
		'<p>' . __('<a href="http://codex.wordpress.org/Posts_Screen" target="_blank">Documentation on Managing Posts</a>', 'corvius') . '</p>' .
		'<p>' . __('<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>', 'corvius') . '</p>';

  }
  return $contextual_help;
}

add_action( 'contextual_help', 'portfolioposttype_add_help_text', 10, 3 );

?>
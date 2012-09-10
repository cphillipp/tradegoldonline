<?php
/*
Plugin Name: Gravatar Favicon
Description: This plugin allows you to generate a gravatar favicon for your blog, feed logo and admin logo included Apple touch icon. <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=mypatricks@gmail.com&item_name=Donate%20to%20Patrick%20Chia&item_number=1242543308&amount=15.00&no_shipping=0&no_note=1&tax=0&currency_code=USD&bn=PP%2dDonationsBF&charset=UTF%2d8&return=http://patrick.bloggles.info/">Get a coffee to Patrick</a> (W6A4N4-L26P6-WSH)
Version: 3.1
Author: Patrick Chia, mypatricks, Sergey Yakovlev
Author URI: http://patrickchia.com/
Plugin URI: http://patrick.bloggles.info/plugins/
Tags: multi-site, wpmu, wordpressmu, images, avatar, avatars, gravatar, personalization, avatar, identicon, OpenAvatar, mybloglog, monsterid, Favatar, favicon, icon, bookmark
Donate link: http://bit.ly/aYeS92
*/

/* Get valueble MU Hosting
 * http://mu.bloggles.info/wordpress-hosting/
 * Love you forever, Esther
 */

$gf_domain = "gravatar-favicon";

function gf_setup(){
  global $gf_domain;

  if ( function_exists( 'load_plugin_textdomain' ) ) {
    load_plugin_textdomain( $gf_domain, false, dirname( plugin_basename(__FILE__ ) ) . '/locale' );
  }
}

function gf_settings_api_init() {
  global $gf_domain;
  $gf_ssection = __( 'Site Favicon Settings', $gf_domain );
  $gf_sfield = __( 'Email Address', $gf_domain );

  add_settings_section(
    'gf_setting_section',
    $gf_ssection,
    'gf_setting_section_callback_function',
    'general'
  );

  add_settings_field(
    'fav',
    $gf_sfield,
    'gf_setting_callback_function',
    'general',
    'gf_setting_section'
  );

  register_setting('general','fav');
}

function gf_setting_section_callback_function() {
  global $gf_domain;
  echo '<p>' . _e('Enter your Gravatar email to generate the site favicon', $gf_domain) . '</p>';
}

function gf_setting_callback_function() {
	if ( get_option('fav') )
		$gravatar = md5( strtolower( trim( get_option('fav') ) ) );
	else 
		$gravatar = md5( strtolower( trim( get_bloginfo('admin_email') ) ) );

	$site_icon = 'http://0.gravatar.com/avatar/'. $gravatar .'.png?s=16';

	echo '<input name="fav" id="fav" type="text" value="'. get_option('fav') .'" class="regular-text" /> <span><img src="'.$site_icon.'" /></span>';
}

if ( !function_exists( 'get_favicon' ) ) :
function get_favicon( $id_or_email, $size = '96', $default = '', $alt = false){
	$avatar = get_avatar($id_or_email, $size, $default, $alt);

	$openPos = strpos($avatar, 'src=\'');
	$closePos = strpos(substr($avatar, ($openPos+5)), '\'');
	$newAvatar = substr($avatar, ($openPos+5), ($closePos-($openPos+5)) );
	
	return $newAvatar;
}
endif;

function blog_favicon() {
	if ( get_option('fav') )
		$gravatar = md5( strtolower( trim( get_option('fav') ) ) );
	else 
		$gravatar = md5( strtolower( trim( get_bloginfo('admin_email') ) ) );

	$apple_icon = 'http://0.gravatar.com/avatar/'. $gravatar .'.png?s=60';
	$favicon_icon = 'http://0.gravatar.com/avatar/'. $gravatar .'.png?s=16';

	if ( get_option('show_avatars') ) {
		echo "<link rel=\"apple-touch-icon\" href=\"$apple_icon\" />\n";
		echo "<link rel=\"shortcut icon\" type=\"image/png\" href=\"$favicon_icon\" /><!-- Gravatar Favicon by Patrick http://patrick.bloggles.info/ -->\n";
	}
}

function admin_logo() {
	$admin_logo = get_favicon( get_bloginfo('admin_email'), 31 );

	if ( get_option('show_avatars') ) {
	?>
	<style type="text/css">
		#header-logo{background: transparent url( <?php echo $admin_logo; ?> ) no-repeat scroll center center;
		-moz-border-radius: 5px;
		-webkit-border-bottom-left-radius: 5px;	-webkit-border-bottom-right-radius: 5px; -webkit-border-top-left-radius: 5px; -webkit-border-top-right-radius: 5px;
		-khtml-border-bottom-left-radius: 5px;-khtml-border-bottom-right-radius: 5px;-khtml-border-top-left-radius: 5px;-khtml-border-top-right-radius: 5px;
		border-bottom-left-radius: 5px;	border-bottom-right-radius: 5px;border-bottom-top-radius: 5px;border-bottom-top-radius: 5px;}
		</style>
	<?php
	}
}

function add_feed_logo() {
	$gravatar = md5( strtolower( trim( get_bloginfo('admin_email') ) ) );
	$feed_logo = 'http://0.gravatar.com/avatar/'. $gravatar .'.png?s=48';

	echo "
   <image>
    <title>". get_bloginfo('name')."</title>
    <url>". $feed_logo ."</url>
    <link>". get_bloginfo('siteurl') ."</link>
   </image><!-- Gravatar Favicon by Patrick http://patrick.bloggles.info/ -->\n";
}

function gfav_plugin_settings( $links ) {
  global $gf_domain;
	$settings_link = '<a href="options-general.php">'._e( 'Favicon Settings', $gf_domain ).'</a>';
	array_unshift( $links, $settings_link );
	return $links;
}

function gfav_add_plugin_settings($links, $file) {
  global $gf_domain;
	if ( $file == basename( dirname( __FILE__ ) ).'/'.basename( __FILE__ ) ) {
		$links[] = '<a href="options-general.php">' . _e( 'Favicon Settings', $gf_domain ) . '</a>';
		$links[] = '<a href="http://bit.ly/aYeS92">' . _e( 'Make Donation', $gf_domain ) . '</a>';
	}
	
	return $links;
}

add_action( 'plugins_loaded', 'gf_setup' );
add_action( 'admin_init', 'gf_settings_api_init' );
add_action( 'wp_head', "blog_favicon" );
add_action( 'admin_head', 'blog_favicon' );
add_action( 'login_head', 'blog_favicon' );
add_action( 'admin_head', 'admin_logo' );
add_action( 'rss_head', 'add_feed_logo' );
add_action( 'rss2_head', 'add_feed_logo' );
add_action( 'plugin_action_links_'.basename( dirname( __FILE__ ) ).'/'.basename( __FILE__ ), 'gfav_plugin_settings', 10, 4 );
add_filter( 'plugin_row_meta', 'gfav_add_plugin_settings', 10, 2 );

?>

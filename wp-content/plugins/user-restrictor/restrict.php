<?php
/*
Plugin Name: User Restrictor
Plugin URI: http://nametagcountry.com/
Description: Restrict Users from certain areas
Author: Dus
Version: 1
Author URI: http://nametagcountry.com
*/
add_action('admin_head', 'jqueryui');
function jqueryui() {

					
	echo "<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js'></script>";
	$style = plugins_url( '/User-Restrictor/jquery.css' );
	$script = plugins_url( '/User-Restrictor/paginate.js' );
	$jscript = plugins_url( '/User-Restrictor/jscript.js' );
	echo "<link type='text/css' href='" . $style . "' rel='stylesheet'></link>";
	echo "<script type='text/javascript' src='" . $script . "'></script>";
	echo "<script type='text/javascript' src='" . $jscript . "'></script>";
	}
include_once(ABSPATH . 'wp-includes/pluggable.php'); 
register_activation_hook(__FILE__,'restricted_table');
register_activation_hook(__FILE__,'restricted_data'); 
register_deactivation_hook(__FILE__, 'restricted_de' );
register_uninstall_hook(__FILE__, array( 'restricted_un', 'on_uninstall' ) );
function restricted_de() {
	global $wpdb;
	$table_name = $wpdb->prefix . "restricted";
	$sql = "DROP TABLE " . $table_name . ";";
	$wpdb->query($sql);
	require_once(ABSPATH .'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}
function restricted_un() {
	global $wpdb;
	$table_name = $wpdb->prefix . "restricted";
	$sql = "DROP TABLE " . $table_name . ";";
	$wpdb->query($sql);
	require_once(ABSPATH .'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}

//create db table
function restricted_table () {
   global $wpdb;

   $table_name = $wpdb->prefix . "restricted";
   
//table variable   
   $sql = "CREATE TABLE " . $table_name . " (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  name text NOT NULL,
	  posts text NOT NULL,
	  pages text NOT NULL,
	  plugins text NOT NULL,
	  media text NOT NULL,
	  links text NOT NULL,
	  appearance text NOT NULL,
	  tools text NOT NULL,
	  comments text NOT NULL,
	  users text NOT NULL,
	  disable text NOT NULL,
	  area1 text NOT NULL,
	  area2 text NOT NULL,
	  UNIQUE KEY id (id)
	);";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);
}



global $wpdb;
		$table_name = $wpdb->prefix . "restricted";
foreach( $wpdb->get_results("SELECT * FROM $table_name;") as $key => $row) {
	// each column in your row will be accessible like this
$nname = $row->name;
	}
$current_user = wp_get_current_user();
$cuser = wp_get_current_user();
$nuser = $cuser->user_login;
if ($nuser == $nname) {
	add_action('admin_head', 'admin_css');
	function admin_css() {
	global $wpdb;
$table_name = $wpdb->prefix . "restricted";
foreach( $wpdb->get_results("SELECT * FROM $table_name;") as $key => $row) {
	// each column in your row will be accessible like this
$nname = $row->name;
$nposts = $row->posts;
	}
	?>
<style type="text/css">
<?php if ($nposts == 'yes') { ?>
#menu-pages {
	display: none;
	}
<?php
}
if ($row->posts == 'yes') {
?>
#menu-posts {
	display: none;
	}
<?php
	}
if ($row->plugins == 'yes') {
?>
#menu-plugins {
	display: none;
	}
<?php
}
if ($row->pages == 'yes') {
?>
#menu-settings {
	display: none;
	}
<?php
}
if ($row->links == 'yes') {
?>
#menu-links {
	display: none;
	}
<?php
}
if ($row->plugins == 'yes') {
?>
#menu-plugins {
	display: none;
	}
<?php
}
if ($row->media == 'yes') {
?>
#menu-media {
	display: none;
	}
<?php
}
if ($row->users == 'yes') {
?>
#menu-users {
	display: none;
	}
<?php
	}
if ($row->tools == 'yes') {
?>
#menu-tools {
	display: none;
	}
<?php
	}
if ($row->comments == 'yes') {
?>
#menu-comments {
	display: none;
	}
<?php
}
if ($row->appearance == 'yes') {
?>
#menu-appearance {
	display: none;
	}
<?php
}
if ($row->area1 != '') {
?>
#<?php echo $row->area1; ?> {
	display: none;
	}
<?php
}
if ($row->disable == 'yes') {
?>
#toplevel_page_rs {
	display: none;
	}
<?php
}
?>
</style>
<?php
}
	}
add_action('admin_menu', 'restrictor_menu');
function restrictor_menu() {
	add_menu_page('Restrictor Options', 'User Restrictor', 'manage_options', 'rs', 'restrictor_options', plugins_url( 'ur_icon.png',  __FILE__ ), '9');
}
//db variables
$disable = $_POST['disable'];
$tools = $_POST['Tools'];
$posts = $_POST['Posts'];
$pages = $_POST['Pages'];
$plugins = $_POST['Plugins'];
$media = $_POST['Media'];
$links = $_POST['Links'];
$appearance = $_POST['Appearance'];
$area1 = $_POST['area1'];
$area2 = $_POST['area2'];
$name = $_POST['name'];
$area = $_POST['area'];
$users = $_POST['users'];
$comments = $_POST['comments'];
//start if statement to check for delete or to add user
if($_POST['restrict'] == '1') {

if ( trim($_POST['name']) === '') {
	$error = true;

	}
else if(!isset($error)) {

if (isset($_POST['delusernamed'])) {
global $wpdb;
$table_name = $wpdb->prefix . "restricted";
foreach( $wpdb->get_results("SELECT * FROM $table_name;") as $key => $row) {
$xname = $_POST['deletename'];
}
$wpdb->query("DELETE FROM $table_name WHERE name = '$name'");
}
else {
$table_name = $wpdb->prefix . "restricted";
global $wpdb;

$rows_affected = $wpdb->insert( $table_name, array( 'name' => $name, 'posts' => $posts, 'pages' => $pages, 'plugins' => $plugins, 'media' => $media, 'links' => $links, 'tools' => $tools, 'users' => $users, 'appearance' => $appearance, 'comments' => $comments, 'area1' => $area1, 'area2' => $area2, 'disable' => $disable  ) );   
 }  
}
}
function restrictor_options() { 
$start = '0';
$range = '10';
$cuser = wp_get_current_user();
$nuser = $cuser->user_login;
global $wpdb;
$table_name = $wpdb->prefix . "restricted";
$i = '0';
foreach( $wpdb->get_results("SELECT * FROM $table_name") as $key => $row) {
$i++;
}
//echo $i;

?>
  <!-- the input fields that will hold the variables we will use -->  
<input type='hidden' id='current_page' />  
<input type='hidden' id='show_per_page' /> 
<?php
		//database
		global $wpdb;
		$table_name = $wpdb->prefix . "restricted";
		echo "<div><form name='delusr' method='post' action='' enctype='multipart/form-data'><input type='hidden' name='delusername' value='1'>
<table id='tabletop' style='border-top-right-radius: 10px; border-top-left-radius: 10px; border-top: 1px solid #333; border-right: 1px solid #333; border-left: 1px solid #333;'><tbody><tr style='display: block;'><th style='width: 40px;'>ID</th><th style='width: 80px; border: 1px solid #ADCFEB; background: none repeat scroll 0 0 #DDECF7;'>Name</th><th style='width: 80px; border: 1px solid #ADCFEB; background: none repeat scroll 0 0 #DDECF7;'>Posts</th><th style='width: 80px; border: 1px solid #ADCFEB; background: none repeat scroll 0 0 #DDECF7;'>Pages</th><th style='width: 80px; border: 1px solid #ADCFEB; background: none repeat scroll 0 0 #DDECF7;'>Plugins</th><th style='width: 80px; border: 1px solid #ADCFEB; background: none repeat scroll 0 0 #DDECF7;'>Media</th><th style='width: 80px; border: 1px solid #ADCFEB; background: none repeat scroll 0 0 #DDECF7;'>Links</th><th style='width: 80px; border: 1px solid #ADCFEB; background: none repeat scroll 0 0 #DDECF7;'>Appearance</th><th style='width: 80px; border: 1px solid #ADCFEB; background: none repeat scroll 0 0 #DDECF7;'>Tools</th><th style='width: 80px; border: 1px solid #ADCFEB; background: none repeat scroll 0 0 #DDECF7;'>Comments</th><th style='width: 80px; border: 1px solid #ADCFEB; background: none repeat scroll 0 0 #DDECF7;'>Users</th><th style='width: 80px; border: 1px solid #ADCFEB; background: none repeat scroll 0 0 #DDECF7;'>Disable</th><th style='width: 80px; border: 1px solid #ADCFEB; background: none repeat scroll 0 0 #DDECF7;'>Area1</th><th style='width: 80px; border: 1px solid #ADCFEB; background: none repeat scroll 0 0 #DDECF7;'>Area2</th></tr></tbody></table>";
echo "<table id='usertable' style='border: 1px solid #333;'><tbody id='contentjs'>";
foreach( $wpdb->get_results("SELECT * FROM $table_name LIMIT $start, $range;") as $key => $row) {
	// each column in your row will be accessible like this
echo "<tr><td style='width: 40px;'><input type='hidden' name='delusername' value='1'>" . $row->id . "</td><td style='width: 80px; border: 1px solid #999;'>" . $row->name . "</td>";
//echo "<tr><td><input type='hidden' name='delusername' value='" . $row->name . "'><input type='submit' value='delete' name='" . $row->name . "'><td style='border: 1px solid black;'>" . $row->name . "</td>";

echo "<td style='width: 80px; border: 1px solid #999;'>" . $row->posts . "</td>";
echo "<td style='width: 80px; border: 1px solid #999;'>" . $row->pages . "</td>";
echo "<td style='width: 80px; border: 1px solid #999;'>" . $row->plugins . "</td>";
echo "<td style='width: 80px; border: 1px solid #999;'>" . $row->media . "</td>";
echo "<td style='width: 80px; border: 1px solid #999;'>" . $row->links . "</td>";
echo "<td style='width: 80px; border: 1px solid #999;'>" . $row->appearance . "</td>";
echo "<td style='width: 80px; border: 1px solid #999;'>" . $row->tools . "</td>";
echo "<td style='width: 80px; border: 1px solid #999;'>" . $row->comments . "</td>";
echo "<td style='width: 80px; border: 1px solid #999;'>" . $row->users . "</td>";
echo "<td style='width: 80px; border: 1px solid #999;'>" . $row->disable . "</td>";
echo "<td style='width: 80px; border: 1px solid #999;'>" . $row->area1 . "</td>";
echo "<td style='width: 80px; border: 1px solid #999;'>" . $row->area2 . "</td>";

	}

	echo "</tr></tbody></table></form></div>";
	echo "<div id='page_navigation'></div>";
	echo "Current Logged In User: " . $nuser; ?>


    <h2>User Restriction Options</h2>
    <?php
	if ( isset($_POST['submit']) && trim($_POST['name']) === '') {
	?>
	<script>
	
	jQuery(function() {
		
		jQuery( "#dialog" ).dialog();
			
	});
	
	</script>
	<?php
	$msg = "<div id='dialog' title='Invalid'>Please Input a User Name</div>";
		echo $msg;
	}
    		?>
	<div>Keep in mind, users can still access areas by manually typing them in the url</div>
	<form name="userform" method="post" action="" enctype="multipart/form-data">
	<input type="hidden" name="restrict" value="1">
	<div>
	<label id="userlabel">User to be Restricted</label>
	<label id="userlabel2" style="display: none">User to be Removed from Restrictions</label>
	<input id="ruser" type="text" name="name" value="">
	</div>
	<div>
	<label>Posts</label>
	<select id="Postsj" name = "Posts" value = "Posts">
	<option name = "yes" value = "yes">yes</option>
	<option name = "no" value = "no">no</option>
	</select>
	</div>
	<div>
	<label>Media</label>
	<select name = "Media" value = "Media">
	<option name = "yes" value = "yes">yes</option>
	<option name = "no" value = "no">no</option>
	</select>
	</div>
	<div>
	<label>Links</label>
	<select name = "Links" value = "Links">
	<option name = "yes" value = "yes">yes</option>
	<option name = "no" value = "no">no</option>
	</select>
	</div>
	<div>
	<label>Pages</label>
	<select name = "Pages" value = "Pages">
	<option name = "yes" value = "yes">yes</option>
	<option name = "no" value = "no">no</option>
	</select>
	</div>
	<div>
	<label>Comments</label>
	<select name = "comments">
	<option name = "yes" value = "yes">yes</option>
	<option name = "no" value = "no">no</option>
	</select>
	</div>
	<div>
	<label>Appearance</label>
	<select name = "Appearance" value = "Appearance">
	<option name = "yes" value = "yes">yes</option>
	<option name = "no" value = "no">no</option>
	</select>
	</div>
	<div>
	<label>Plugins</label>
	<select name = "Plugins" value = "Plugins">
	<option name = "yes" value = "yes">yes</option>
	<option name = "no" value = "no">no</option>
	</select>
	</div>
	<div>
	<label>Users</label>
	<select name = "users">
	<option name = "yes" value = "yes">yes</option>
	<option name = "no" value = "no">no</option>
	</select>
	</div>
	<div>
	<label>Tools</label>
	<select name = "Tools" value = "Tools">
	<option name = "yes" value = "yes">yes</option>
	<option name = "no" value = "no">no</option>
	</select>
	</div>
	<div>
	<label>Settings</label>
	<select name = "Settings">
	<option name = "yes" value = "yes">yes</option>
	<option name = "no" value = "no">no</option>
	</select>
	</div>
	<div>
	<label>Disable this plugin?</label>
	<select name = "disable">
	<option name = "no" value = "no">no</option>
	<option name = "yes" value = "yes">yes</option>
	</select>
	</div>
	
	<div style="padding-top: 20px;">
	<div>Custom Menus generated from plugins etc.</div>
	<div>You must enter the css id for the custom menu you would like to hide</div>
	<label>Custom Area 1</label>
	<input type="text" name="area1">
	</div>
	<div>
	<label>Custom Area 2</label>
	<input type="text" name="area2">
	</div>
	
	<div style="color: red; padding-top: 20px;">Use the delete option to delete a user from having restrictions</div>
	<label>Delete User?</label>
	
	<input type="checkbox" id="sdelete" name="delusernamed" value="delete">
	<div>
	<input type="submit" value="submit" name="submit">
	</div>
	</form>


<?php
}


?>
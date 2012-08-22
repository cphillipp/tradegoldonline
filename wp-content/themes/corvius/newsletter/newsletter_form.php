<?php
	
	require_once('../../../../wp-load.php' );
    $table = $wpdb->prefix."rb_corvius_newsletter";
	$query = $wpdb->query("INSERT INTO $table (emails) VALUES ('$_GET[email]');");
		
?>
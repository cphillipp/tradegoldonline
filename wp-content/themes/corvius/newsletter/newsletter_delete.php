<?php

	$id = $_GET['id'];
	$type = $_GET['type'];
	
	//get table
	require_once('../../../../wp-load.php' );
    $table = $wpdb->prefix."rb_corvius_newsletter";
	
	//delete row
	if($type == 'single')
		$query = $wpdb->query("DELETE FROM $table WHERE id=".$_GET['id']);
	else if($type == 'all')
		$query = $wpdb->query("TRUNCATE TABLE $table");
	
?>
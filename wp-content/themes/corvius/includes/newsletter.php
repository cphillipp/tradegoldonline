<?php

/*---------------------------------
	Handle Newsletter widget
------------------------------------*/

if (is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
    add_action('admin_head', 'rb_options_setup');
}

function rb_options_setup(){
	
	//Add newsletter table
	global $wpdb;
    $table = $wpdb->prefix."rb_corvius_newsletter";

	if($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {

		$structure = "CREATE TABLE $table (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			emails text NOT NULL,
			UNIQUE KEY id (id)
			);";
		$wpdb->query($structure);
		
	}
	
}

function rb_newsletter_admin(){
	?>
	
	<div class="wrap newsletter">
		<div id="icon-newsletter" class="icon32-newsletter"><br /></div>
		<h2><?php _e('Newsletter', 'corvius'); ?></h2>
		
		<br />
		
		<input class="newsCopyAll button-secondary action" type="submit" value="<?php _e('Copy all email addresses to the clipboard', 'corvius'); ?>">
		<input class="newsDeleteAll button-primary	action" type="submit" value="<?php _e('Delete all email addresses', 'corvius'); ?>">
		
		<br /><br />
	
		<table class="wp-list-table widefat fixed" cellspacing="0">
			<thead>
				<tr>
					<th id="email" class="manage-column column-email sortable desc" scope="col">
						<?php _e('Email Address', 'corvius'); ?>
					</th>
					<th id="delete" class="manage-column column-delete" scope="col">
						<?php _e('Delete', 'corvius'); ?>
					</th>
				</tr>
			</thead>
			<tbody id="newsFields">
				<?php
						
					global $wpdb;
					$table = $wpdb->prefix."rb_corvius_newsletter";
						
					$emails = $wpdb->get_results("SELECT *, emails FROM $table");
						
					foreach($emails as $email) : ?>
						
						<tr id="e<?php echo $email->id; ?>">
						
							<td class="column-email">
								<strong><a href="mailto:<?php echo $email->emails; ?>"><?php echo $email->emails; ?></a></strong>
							</td>
							
							<td class="column-delete">
								<a href="#" class="newsDeleteSingle"><?php _e('Delete', 'corvius'); ?></a>
							</td>
							
						</tr>
						
					<?php endforeach; ?>
					
			</tbody>
		</table>
		
		<br />
		
		<p>Select and click Ctrl+C to copy to clipboard.</p>
		<textarea id="newsTextArea">
			
		</textarea>
		
		<br /><br />
		
		<input class="newsCopyAll button-secondary action" type="submit" value="<?php _e('Copy all email addresses to the clipboard', 'corvius'); ?>">
		<input class="newsDeleteAll button-primary	action" type="submit" value="<?php _e('Delete all email addresses', 'corvius'); ?>">
		
	</div>
	
	<?php
}

?>
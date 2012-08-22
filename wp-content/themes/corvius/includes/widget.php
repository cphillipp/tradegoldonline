<?php

//Add an action that will load all widgets
add_action( 'widgets_init', 'rb_load_widgets' );

//Function that registers the widgets
function rb_load_widgets() {
	register_widget('rb_twitter_widget');
	register_widget('rb_rotator_widget');
	register_widget('rb_contact_widget');
	register_widget('rb_posts_widget');
	register_widget('rb_flickr_widget');
	register_widget('rb_separator_widget');
}

/*-----------------------------------------------------------------------------------

	Plugin Name: RB Twitter Widget
	Plugin URI: http://www.rubenbristian.com
	Description: A widget that displays your latest tweets
	Version: 1.0
	Author: Ruben Bristian
	Author URI: http://www.rubenbristian.com

-----------------------------------------------------------------------------------*/

class rb_twitter_widget extends WP_Widget {
	
	function rb_twitter_widget (){
		
		$widget_ops = array( 'classname' => 'twitter', 'description' => 'A widget that displays your latest tweets' );
		$control_ops = array( 'width' => 250, 'height' => 120, 'id_base' => 'twitter-widget' );
		$this->WP_Widget( 'twitter-widget', 'RB Twitter Widget', $widget_ops, $control_ops );
		
	}
		
	function widget($args, $instance){
			
		extract($args);
			
		$title = apply_filters('widget_title', $instance['title']);
		$username = $instance['username'];
		$button = $instance['button'];
		$intro = $instance['intro'];
			
		echo $before_widget;
			
		echo $before_title.$title.$after_title;
			
		if($button)
			echo '<a class="twitterButton" href="http://twitter.com/#!/'.$username.'">'.$button.'</a>';
			
		if($intro)
			echo '<p>', $intro, '</p><hr />';
			
		echo '<ul class="twitterList"><li class="hidden twitterUser">'.$username.'</li></ul>';
			
		echo $after_widget;
			
	}
			
	function update($new_instance, $old_instance){
		
		$instance = $old_instance;
			
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['username'] = strip_tags($new_instance['username']);
		$instance['button'] = strip_tags($new_instance['button']);
		$instance['intro'] = strip_tags($new_instance['intro']);
			
		return $instance;
			
	}
		
	function form($instance){
		
		$defaults = array( 'title' => 'Twitter', 'username' => '', 'button' => 'follow us', 'intro' => '' );
			
		$instance = wp_parse_args((array) $instance, $defaults);
			
		?>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e('Username:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $instance['username']; ?>" style="width:100%;" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'button' ); ?>"><?php _e('Button:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'button' ); ?>" name="<?php echo $this->get_field_name( 'button' ); ?>" value="<?php echo $instance['button']; ?>" style="width:100%;" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'intro' ); ?>"><?php _e('Intro:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'intro' ); ?>" name="<?php echo $this->get_field_name( 'intro' ); ?>" value="<?php echo $instance['intro']; ?>" style="width:100%;" />
			</p>
			
		<?php
			
	}
		
}

/*-----------------------------------------------------------------------------------

	Plugin Name: RB Custom Posts Rotator
	Plugin URI: http://www.rubenbristian.com
	Description: A widget that displays your latest/most comment/most popular posts
	Version: 1.0
	Author: Ruben Bristian
	Author URI: http://www.rubenbristian.com

-----------------------------------------------------------------------------------*/

class rb_rotator_widget extends WP_Widget {
	
	function rb_rotator_widget (){
		
		$widget_ops = array( 'classname' => 'posts', 'description' => 'A widget that displays your latest/most comment/most popular posts' );
		$control_ops = array( 'width' => 250, 'height' => 120, 'id_base' => 'rotator-widget' );
		$this->WP_Widget( 'rotator-widget', 'RB Custom Posts Widget', $widget_ops, $control_ops );
		
	}
		
	function widget($args, $instance){
			
		extract($args);
			
		$title = apply_filters('widget_title', $instance['title']);
		$filters = $instance['filters'];
		$no = $instance['no'];
			
		global $post;
			
		echo $before_widget;
			
		echo $before_title.$title.$after_title;
			
		echo '<ul class="postsFilters filters clearfix">';
		for($i=0; $i<3; $i++){
			echo '<li><a'; if($i==0) echo ' class=""'; echo ' href="#">'.$filters[$i].'</a></li>';
		}
		echo '</ul>';
			
		echo '<div class="postTabs">';
					
		while (have_posts()) : the_post();
			
		for($j=0; $j<3; $j++){
			echo '<div class="clearfix', ($j == 0 ? ' ">' : '">');
			echo '<ul class="postsList clearfix">';
				
			switch ($j) {
			
				case 0:
					//Popular Posts
					$all_posts = get_posts( array('numberposts' => $no, 'meta_key' => 'post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC') );
					foreach($all_posts as $post) : setup_postdata($post);
						?>
						
							<li>
								<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
								<p><?php rb_excerpt('rb_excerptlength_widget', 'rb_excerptmore'); ?></p>
								<ul class="postInfoList  clearfix">
									<li class="date"><strong><?php the_time('j'); ?></strong><span><?php the_time('S'); ?></span>&nbsp;<?php the_time('F Y'); ?></li>
									<li class="views"><?php echo get_post_meta($post->ID, 'post_views_count', true).__(' views', 'corvius'); ?></li>
								</ul>
							</li>
						
						<?php endforeach;
					
					break;
					
					case 1:
						//Latest Posts
						$all_posts = get_posts( array('numberposts' => $no) );
						foreach($all_posts as $post) : setup_postdata($post);
						?>
						
							<li>
								<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
								<p><?php rb_excerpt('rb_excerptlength_widget', 'rb_excerptmore'); ?></p>
								<ul class="postInfoList  clearfix">
									<li class="date"><strong><?php the_time('j'); ?></strong><span><?php the_time('S'); ?></span>&nbsp;<?php the_time('F Y'); ?></li>
									<li class="author"><?php the_author(); ?></li>
								</ul>
							</li>
						
						<?php endforeach;
						
						break;
						
					case 2:
						//Most Commented Posts
						$all_posts = get_posts( array('orderby' => 'comment_count', 'numberposts' => $no) );
						foreach($all_posts as $post) : setup_postdata($post);
						?>
						
							<li>
								<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
								<p><?php rb_excerpt('rb_excerptlength_widget', 'rb_excerptmore'); ?></p>
								<ul class="postInfoList  clearfix">
									<li class="date"><strong><?php the_time('j'); ?></strong><span><?php the_time('S'); ?></span>&nbsp;<?php the_time('F Y'); ?></li>
									<li class="comments"><li class="comments"><?php comments_number('0', '1', '%'); _e(' comments', 'corvius'); ?></li>
								</ul>
							</li>
							
						<?php endforeach;
					
						break;
						
			}
				
			echo '</ul></div>';
			
		}
			
		endwhile;
			
		echo '</div>';
			
		echo $after_widget;
			
	}
			
	function update($new_instance, $old_instance){
		
		$instance = $old_instance;
			
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['popular'] = strip_tags($new_instance['popular']);
		$instance['latest'] = strip_tags($new_instance['latest']);
		$instance['comments'] = strip_tags($new_instance['comments']);
		$instance['filters'] = array($new_instance['popular'], $new_instance['latest'], $new_instance['comments']);
		$instance['no']= strip_tags($new_instance['no']);
			
		return $instance;
			
	}
		
	function form($instance){
		
		$defaults = array( 'title' => 'Latest rotator', 'popular' => 'Popular', 'latest' => 'Latest', 'comments' => 'Comments', 'no' => '3' );
			
		$instance = wp_parse_args((array) $instance, $defaults);
			
		?>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'popular' ); ?>"><?php _e('Popular posts title:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'popular' ); ?>" name="<?php echo $this->get_field_name( 'popular' ); ?>" value="<?php echo $instance['popular']; ?>" style="width:100%;" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'latest' ); ?>"><?php _e('Latest posts title:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'latest' ); ?>" name="<?php echo $this->get_field_name( 'latest' ); ?>" value="<?php echo $instance['latest']; ?>" style="width:100%;" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'comments' ); ?>"><?php _e('Comments posts title:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'comments' ); ?>" name="<?php echo $this->get_field_name( 'comments' ); ?>" value="<?php echo $instance['comments']; ?>" style="width:100%;" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'no' ); ?>"><?php _e('Show number of posts/comments:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'no' ); ?>" name="<?php echo $this->get_field_name( 'no' ); ?>" value="<?php echo $instance['no']; ?>" style="width:100%;" />
			</p>
			
		<?php
			
	}
		
}
		
/*-----------------------------------------------------------------------------------

	Plugin Name: RB Contact Info Widget
	Plugin URI: http://www.rubenbristian.com
	Description: A widget that displays some contact information on the site
	Version: 1.0
	Author: Ruben Bristian
	Author URI: http://www.rubenbristian.com

-----------------------------------------------------------------------------------*/

class rb_contact_widget extends WP_Widget {
	
	function rb_contact_widget (){
		
		$widget_ops = array( 'classname' => 'contact_widget', 'description' => 'A widget that displays some contact information on the site' );
		$control_ops = array( 'width' => 250, 'height' => 120, 'id_base' => 'contact-widget' );
		$this->WP_Widget( 'contact-widget', 'RB Contact Info Widget', $widget_ops, $control_ops );
		
	}
		
	function widget($args, $instance){
			
		extract($args);
			
		$title = apply_filters('widget_title', $instance['title']);
		$text = $instance['text'];
		$phone = $instance['phone'];
		$mail = $instance['mail'];
		$address = $instance['address'];
		$icons = $instance['icons'];
			
		echo $before_widget;
			
		echo $before_title.$title.$after_title;
			
		if($text)
			echo '<p>'.$text.'</p>';
				
		if($phone || $email || $address)
			echo '<ul class="contactList">';
				
		if($phone)	
			echo '<li class="phone">'.$phone.'</li>';
				
		if($mail)	
			echo '<li class="email"><a href="mailto:'.$mail.'">'.$mail.'</a></li>';
				
		if($address)	
			echo '<li class="address">'.$address.'</li>';
				
		if($phone || $email || $address)
			echo '</ul>';
			
		if($icons)
			echo '<hr /><div class="jsList">'.$icons.'</div>';
			
		echo $after_widget;
			
	}
			
	function update($new_instance, $old_instance){
		
		$instance = $old_instance;
			
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['text'] = strip_tags($new_instance['text']);
		$instance['phone'] = strip_tags($new_instance['phone']);
		$instance['mail'] = strip_tags($new_instance['mail']);
		$instance['address'] = strip_tags($new_instance['address']);
		$instance['icons'] = /*strip_tags(*/$new_instance['icons']/*, '<a>')*/;
			
		return $instance;
			
	}
		
	function form($instance){
		
		$defaults = array( 'title' => 'Contact Info' );
			
		$instance = wp_parse_args((array) $instance, $defaults);
			
		?>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e('Text:', 'corvius'); ?></label>
				<textarea id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" style="width:100%;"><?php echo $instance['text']; ?></textarea>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php _e('Phone:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" value="<?php echo $instance['phone']; ?>" style="width:100%;" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'mail' ); ?>"><?php _e('Mail:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'mail' ); ?>" name="<?php echo $this->get_field_name( 'mail' ); ?>" value="<?php echo $instance['mail']; ?>" style="width:100%;" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'address' ); ?>"><?php _e('Address:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'address' ); ?>" name="<?php echo $this->get_field_name( 'address' ); ?>" value="<?php echo $instance['address']; ?>" style="width:100%;" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'icons' ); ?>"><?php _e('Icons:', 'corvius'); ?></label>
				<textarea id="<?php echo $this->get_field_id( 'icons' ); ?>" name="<?php echo $this->get_field_name( 'icons' ); ?>" style="width:100%;" ><?php echo $instance['icons']; ?></textarea>
			</p>
			
		<?php
			
	}
		
}

/*-----------------------------------------------------------------------------------

	Plugin Name: RB Latest Posts Widget
	Plugin URI: http://www.rubenbristian.com
	Description: A widget that displays your latest posts
	Version: 1.0
	Author: Ruben Bristian
	Author URI: http://www.rubenbristian.com

-----------------------------------------------------------------------------------*/

class rb_posts_widget extends WP_Widget {
	
	function rb_posts_widget (){
		
		$widget_ops = array( 'classname' => 'posts', 'description' => 'A widget that displays your latest posts' );
		$control_ops = array( 'width' => 250, 'height' => 120, 'id_base' => 'posts-widget' );
		$this->WP_Widget( 'posts-widget', 'RB Latest Posts Widget', $widget_ops, $control_ops );
		
	}
		
	function widget($args, $instance){
			
		extract($args);
			
		$title = apply_filters('widget_title', $instance['title']);
		$no = strip_tags($instance['no']);
			
		echo $before_widget;
			
		echo $before_title.$title.$after_title;
			
		echo '<ul class="blogList">';
			
		$i = 0;
		
		$args = array('offset'=> 0, 'posts_per_page' => $no);
		$all_posts2 = new WP_Query($args);
		while($all_posts2->have_posts()) : $all_posts2->the_post();
			
			?>
				
				<li>
					<span><?php the_time('F jS, Y'); ?></span>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					<p><?php rb_excerpt('rb_excerptlength_widget', 'rb_excerptmore'); ?></p>
						<?php echo (++$i >= $no ? '' : '<hr />'); ?>
				</li>
				
		<?php endwhile;
			
		echo '</ul>';
			
		echo $after_widget;
			
	}
			
	function update($new_instance, $old_instance){
		
		$instance = $old_instance;
			
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['no'] = strip_tags($new_instance['no']);
			
		return $instance;
		
	}
		
	function form($instance){
		
		$defaults = array( 'title' => 'Latest Posts', 'no' => '2' );
		
		$instance = wp_parse_args((array) $instance, $defaults);
			
		?>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'no' ); ?>"><?php _e('Show number of posts:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'no' ); ?>" name="<?php echo $this->get_field_name( 'no' ); ?>" value="<?php echo $instance['no']; ?>" style="width:100%;" />
			</p>
			
			
		<?php
			
	}
		
}

/*-----------------------------------------------------------------------------------

	Plugin Name: RB Flickr Widget
	Plugin URI: http://www.rubenbristian.com
	Description: A widget that displays your latest flickr images
	Version: 1.0
	Author: Ruben Bristian
	Author URI: http://www.rubenbristian.com

-----------------------------------------------------------------------------------*/

class rb_flickr_widget extends WP_Widget {
	
	function rb_flickr_widget (){
		
		$widget_ops = array( 'classname' => 'flickr', 'description' => 'A widget that displays your latest flickr images' );
		$control_ops = array( 'width' => 250, 'height' => 120, 'id_base' => 'flickr-widget' );
		$this->WP_Widget( 'flickr-widget', 'RB Flickr Widget', $widget_ops, $control_ops );
		
	}
	
	function widget($args, $instance){
			
		extract($args);
			
		$title = apply_filters('widget_title', $instance['title']);
		$user = $instance['user'];
		$no = $instance['no'];
			
		echo $before_widget;
			
		echo $before_title.$title.$after_title;
			
		echo '<ul class="flickrList clearfix"><li class="hidden flickrUser">'.$user.'</li><li class="hidden flickrNo">'.$no.'</li></ul>';
			
		echo $after_widget;
			
	}
			
	function update($new_instance, $old_instance){
		
		$instance = $old_instance;
			
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['user'] = strip_tags($new_instance['user']);
		$instance['no'] = strip_tags($new_instance['no']);
			
		return $instance;
			
	}
		
	function form($instance){
		
		$defaults = array( 'title' => 'From Flickr', 'no' => '6' );
			
		$instance = wp_parse_args((array) $instance, $defaults);
			
		?>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'user' ); ?>"><?php _e('Flickr Username:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'user' ); ?>" name="<?php echo $this->get_field_name( 'user' ); ?>" value="<?php echo $instance['user']; ?>" style="width:100%;" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'no' ); ?>"><?php _e('Number of images:', 'corvius'); ?></label>
				<input id="<?php echo $this->get_field_id( 'no' ); ?>" name="<?php echo $this->get_field_name( 'no' ); ?>" value="<?php echo $instance['no']; ?>" style="width:100%;" />
			</p>
			
		<?php
			
	}
		
}

/*-----------------------------------------------------------------------------------

	Plugin Name: RB Separator Widget
	Plugin URI: http://www.rubenbristian.com
	Description: A widget that simply adds a separator to the page(to be used in the sidebar)
	Version: 1.0
	Author: Ruben Bristian
	Author URI: http://www.rubenbristian.com

-----------------------------------------------------------------------------------*/

class rb_separator_widget extends WP_Widget {
	
	function rb_separator_widget (){
		
		$widget_ops = array( 'classname' => 'separator', 'description' => 'A widget that simply adds a separator to the sidebar' );
		$control_ops = array( 'width' => 250, 'height' => 120, 'id_base' => 'separator-widget' );
		$this->WP_Widget( 'separator-widget', 'RB Separator Widget', $widget_ops, $control_ops );
		
	}
		
	function widget($args, $instance){
			
		echo '<hr class="widget_separator" />';
		
	}
			
	function update($new_instance, $old_instance){
		
		$instance = $old_instance;
			
		return $instance;
			
	}
		
	function form($instance){
			
	}
		
}

?>
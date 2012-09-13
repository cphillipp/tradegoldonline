<?php

/*---------------------------------
	Custom meta boxes setup
------------------------------------*/

//Define sidebars array
$sidebars_array = array(__('Post Default Sidebar', 'corvius'), __('Page Default Sidebar', 'corvius'));
$sidebars = get_option_tree( 'rb_sidebars', '', false, true);
if(isset($sidebars))
	foreach($sidebars as $sidebar)
		array_push($sidebars_array, $sidebar['title']);
		
//Define default meta boxes array
$meta_boxes = array(
	array(
		'id' => 'rb_slider_meta',
		'title' => __('Slider Options', 'corvius'),
		'pages' => array('page'),
		'context' => 'side',
		'priority' => 'high',
		'template' => 'template-home.php',
		'exclude' => '',
		'fields' => array(
			array(
				'name' => __('Slider:', 'corvius'),
				'desc' => __('Choose a slider for this homepage', 'corvius'),
				'id' => 'rb_meta_box_slider',
				'type' => 'select',
				'options' => array(__('Parallaxy Slider', 'corvius'), __('Circles Slider', 'corvius'), __('Fading Slider', 'corvius'))
			)
		)
	),
	array(
		'id' => 'rb_slider_meta',
		'title' => __('Slider Options', 'corvius'),
		'pages' => array('page'),
		'context' => 'side',
		'priority' => 'high',
		'template' => 'template-homeblog.php',
		'exclude' => '',
		'fields' => array(
			array(
				'name' => __('Slider:', 'corvius'),
				'desc' => __('Choose a slider for this homepage', 'corvius'),
				'id' => 'rb_meta_box_slider',
				'type' => 'select',
				'options' => array(__('Parallaxy Slider', 'corvius'), __('Circles Slider', 'corvius'), __('Fading Slider', 'corvius'))
			)
		)
	),
    array(
        'id' => 'rb_layout_meta',
        'title' => __('Layout Options', 'corvius'),
        'pages' => array('post', 'page'), 
        'context' => 'side',
        'priority' => 'high',
		'template' => '',
		'exclude' => '',
        'fields' => array(
            array(
                'name' => __('Type:', 'corvius'),
                'desc' => __('Choose a layout for this post/page', 'corvius'),
                'id' => 'rb_meta_box_layout',
                'type' => 'select',
                'options' => array(__('No Sidebar (full width)', 'corvius'), __('Right Sidebar', 'corvius'))
            ),
            array(
                'name' => __('Sidebar:', 'corvius'),
                'desc' => __('Choose a sidebar for this post/page', 'corvius'),
                'id' => 'rb_meta_box_sidebar',
                'type' => 'select',
                'options' => $sidebars_array
            )
        )
    ),
    array(
        'id' => 'rb_tagline_meta',
        'title' => __('Tagline Options', 'corvius'),
        'pages' => array('page', 'post', 'portfolio'),
        'context' => 'side',
        'priority' => 'high',
		'template' => '',
		'exclude' => 'template-home.php',
        'fields' => array(
            array(
                'name' => __('Tagline', 'corvius'),
				'desc' => __('Write a tagline for this page. Leave empty if you want to use the page\'s title', 'corvius'),
                'id' => 'rb_meta_box_tagline',
                'type' => 'text'
            ),
			array(
				'name' => __('Right Side' ,'corvius'),
				'desc' => __('Select some type of content for the right side of the header', 'starker'),
				'id' => 'rb_meta_box_action',
				'type' => 'select',
				'options' => array(__('None', 'corvius'), __('Call to action button', 'corvius'), __('Search form', 'corvius'))
			),
			array(
				'desc' => __('If you select a Call to action(CAT) button for the right side of the header, you need to set it\'s parameters below', 'corvius'),
				'id' => 'rb_meta_box_action_description',
				'type' => 'none'
			),
            array(
                'name' => 'CAT Button title',
                'id' => 'rb_meta_box_action3',
                'type' => 'text'
            ),
            array(
                'name' => 'CAT Button link',
                'id' => 'rb_meta_box_action4',
                'type' => 'text'
            )
        )
    ),
    array(
        'id' => 'rb_folio_info_meta',
        'title' => __('Project Info', 'corvius'),
		'desc' => __('This is some info on the project, which will appear in the right side of the content. If you want to omit a field just leave it empty.', 'corvius'),
        'pages' => array('portfolio'),
        'context' => 'normal',
        'priority' => 'high',
		'template' => '',
		'exclude' => '',
        'fields' => array(
            array(
                'name' => __('Author:', 'corvius'),
                'id' => 'rb_folio_info_meta1',
                'type' => 'text'
            ),
            array(
                'name' => __('Date:', 'corvius'),
                'id' => 'rb_folio_info_meta2',
                'type' => 'text'
            ),
            array(
                'name' => __('Client:', 'corvius'),
                'id' => 'rb_folio_info_meta3',
                'type' => 'text'
            ),
            array(
                'name' => __('URL:', 'corvius'),
                'id' => 'rb_folio_info_meta4',
                'type' => 'text'
            )
        )
    ),
	array(
		'id' => 'rb_folio_image_meta',
		'title' => __('Project Images', 'corvius'),
		'desc' => __('You can upload up to 4 images to use as a slideshow for the current project. If you upload only one image, the slideshow will be replaced with that static image.', 'corvius'),
		'pages' => array('portfolio'),
		'context' => 'normal',
		'priority' => 'high',
		'template' => '',
		'exclude' => '',
		'fields' => array(
			array(
				'name' => __('Image 1:', 'corvius'),
				'id' => 'rb_folio_image_meta1',
				'desc' => __('932px x unlimited', 'stakers'),
				'type' => 'image'
			),
			array(
				'name' => __('Image 2:', 'corvius'),
				'id' => 'rb_folio_image_meta2',
				'desc' => __('932px x unlimited', 'stakers'),
				'type' => 'image'
			),
			array(
				'name' => __('Image 3:', 'corvius'),
				'id' => 'rb_folio_image_meta3',
				'desc' => __('932px x unlimited', 'stakers'),
				'type' => 'image'
			),
			array(
				'name' => __('Image 4:', 'corvius'),
				'id' => 'rb_folio_image_meta4',
				'desc' => __('932px x unlimited', 'stakers'),
				'type' => 'image'
			)
		)
	),
	array(
		'id' => 'rb_folio_video_meta',
		'title' => __('Project Video', 'corvius'),
		'pages' => array('portfolio'),
		'context' => 'normal',
		'priority' => 'high',
		'template' => '',
		'exclude' => '',
		'fields' => array(
			array(
				'name' => __('Video height:', 'corvius'),
				'desc' => __('The video\'s height (eg. 524)', 'corvius'),
				'id' => 'rb_folio_video_meta5',
				'type' => 'text'
			),
			array(
				'name' => __('Video Poster:', 'corvius'),
				'desc' => __('The preview image', 'corvius'),
				'id' => 'rb_folio_video_meta1',
				'type' => 'image'
			),
			array(
				'name' => __('Path to *.OGV file:', 'corvius'),
				'id' => 'rb_folio_video_meta2',
				'type' => 'text'
			),
			array(
				'name' => __('Path to *.MP4 file:', 'corvius'),
				'id' => 'rb_folio_video_meta3',
				'type' => 'text'
			),
			array(
				'name' => __('Path to *.WEBM file:', 'corvius'),
				'id' => 'rb_folio_video_meta6',
				'type' => 'text'
			),
			array(
				'name' => __('Youtube or Vimeo embed frame:', 'corvius'),
				'desc' => __('If you wish to add a custom video from Youtube or Vimeo, just write the embed code here.', 'corvius'),
				'id' => 'rb_folio_video_meta4',
				'type' => 'textarea'
			)
		)
	)
);

foreach ($meta_boxes as $meta_box) {
    $rb_meta_box = new rb_meta_box($meta_box);
}
	
class rb_meta_box {

    protected $_meta_box;

    //Create meta box based on given data
    function __construct($meta_box) {
        $this->_meta_box = $meta_box;
        add_action('admin_menu', array(&$this, 'add'));
        add_action('save_post', array(&$this, 'save'));
    }

    //Add meta box for multiple post types & page templates
    function add() {
		
		$post_id = isset($_GET['post']) ? $_GET['post'] : (isset($_POST['post_ID']) ? $_POST['post_ID'] : 'no');
		$template_file = $post_id != 'no' ? get_post_meta($post_id,'_wp_page_template',TRUE) : 'no';
	
        foreach ($this->_meta_box['pages'] as $page) {
		
			if(!$this->_meta_box['template'] && $this->_meta_box['exclude'] != $template_file && $this->_meta_box['template'] != 'no')
		
				add_meta_box($this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $page, $this->_meta_box['context'], $this->_meta_box['priority']);
				
			else if($this->_meta_box['template'] == $template_file)
				
				add_meta_box($this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $page, $this->_meta_box['context'], $this->_meta_box['priority']);
			
        }
    }

    //Callback function to show fields in meta box
    function show() {
        global $post;

        //Use nonce for verification
        echo '<input type="hidden" name="rb_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		echo (isset($this->_meta_box['desc']) ? '<p class="metaDescription">'.$this->_meta_box['desc'].'</p>' : '');
		
        echo '<div class="metaTable">';

        foreach ($this->_meta_box['fields'] as $field) {
            //Get current post meta data
            $meta = get_post_meta($post->ID, $field['id'], true);
			
			check_isset($field['name']);
			check_isset($field['desc']);
			check_isset($field['options']);
			check_isset($field['std']);

            echo '<div class="clearfix"><div class="leftSide labelField">',
                    '<strong><label for="', $field['id'], '">', $field['name'], '</label></strong><span class="customDesc">', $field['desc'], '</span></div>',
                    '<div class="rightSide">';
            switch ($field['type']) {
                case 'text':
                    echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />';
                    break;
                case 'textarea':
                    echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:100%">', $meta ? $meta : $field['std'], '</textarea>';
                    break;
                case 'select':
                    echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                    foreach ($field['options'] as $option) {
                        echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                    }
                    echo '</select>';
                    break;
                case 'radio':
                    foreach ($field['options'] as $option) {
                        echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                    }
                    break;
                case 'checkbox':
                    echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
                    break;
				case 'image':  
					$image = get_template_directory_uri().'/images/image.png';  
					echo '<span class="custom_default_image" style="display:none">'.$image.'</span>';  
					if ($meta) { $image = wp_get_attachment_image_src($meta, 'medium'); $image = $image[0]; }  
					echo    '<input name="'.$field['id'].'" type="hidden" class="custom_upload_image" value="'.$meta.'" /> 
								<img src="'.$image.'" class="custom_preview_image" alt="" />
									<div><input class="custom_upload_image_button button" type="button" value="Choose Image" /> 
									<small> <a href="#" class="custom_clear_image_button">Remove Image</a></small></div>
									';  
					break;  
            }
            echo     '</div>',
                '</div>';
        }

        echo '</div>';
    }

    //Save data from meta box
    function save($post_id) {
	
		if(isset($_POST['rb_meta_box_nonce'])){
			if (!wp_verify_nonce($_POST['rb_meta_box_nonce'], basename(__FILE__))) {
				return $post_id;
			}
		}else {
			return $post_id;
		}
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        foreach ($this->_meta_box['fields'] as $field) {
            $old = get_post_meta($post_id, $field['id'], true);
            $new = isset($_POST[$field['id']]) ? $_POST[$field['id']] : '';

            if ($new && $new != $old) {
                update_post_meta($post_id, $field['id'], $new);
            } elseif ('' == $new && $old) {
                delete_post_meta($post_id, $field['id'], $old);
            }
        }
    }
}

function check_isset(&$item){
	$return;
	isset($item) ? $return = $item : $return = '';
	return $return;
}

?>
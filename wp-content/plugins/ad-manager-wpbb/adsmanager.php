<?php

/*                                                                   
Plugin Name: Ads Manager WP/BB
Version: 1.6.3
Plugin URI: http://www.letsfx.com/adsmanager
Author: aqlan
Author URI: http://blog.letsfx.com/
Plugin Description: Use this plugin to quickly and easily insert Any Ad code Unit to your posts and BuddyPress sections including Forum topics.
*/

/*
    This program is free software; you can redistribute it
    under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

// Displays Simple Ad Campaign Options menu
function ad_letsfxad_option_page() {
    if (function_exists('add_options_page')) {
        add_options_page('Ads Manager', 'Ads Manager', 8, __FILE__, 'letsfxad_insertion_options_page');
    }
}

function letsfxad_insertion_options_page() {

    if (isset($_POST['info_update']))
    {
        echo '<div id="message" class="updated fade"><p><strong>';

        $tmpCode1 = htmlentities(stripslashes($_POST['wp_letsfxad_adsused']) , ENT_COMPAT);
        update_option('wp_letsfxad_adsused', $tmpCode1);
        $tmpCode1 = htmlentities(stripslashes($_POST['wp_letsfxad_hide']) , ENT_COMPAT);
        update_option('wp_letsfxad_hide', $tmpCode1);
        
        $tmpCode1 = htmlentities(stripslashes($_POST['wp_letsfxad_top']) , ENT_COMPAT);
        update_option('wp_letsfxad_top', $tmpCode1);

        $tmpCode1 = htmlentities(stripslashes($_POST['wp_letsfxad_bottom']) , ENT_COMPAT);
        update_option('wp_letsfxad_bottom', $tmpCode1);
        
        $tmpCode1 = htmlentities(stripslashes($_POST['wp_letsfxad_other1']) , ENT_COMPAT);
        update_option('wp_letsfxad_other1', $tmpCode1);
        
        $tmpCode1 = htmlentities(stripslashes($_POST['wp_letsfxad_other2']) , ENT_COMPAT);
        update_option('wp_letsfxad_other2', $tmpCode1);
        
        $tmpCode1 = htmlentities(stripslashes($_POST['wp_letsfxad_other3']) , ENT_COMPAT);
        update_option('wp_letsfxad_other3', $tmpCode1);
        

        echo 'Options Updated!';
        echo '</strong></p></div>';
    }

    ?>

    <div class=wrap>

    <h2>Ads Manager Options </h2>

    <p>For information and updates, please visit:<br />
    <a href="http://blog.letsfx.com/">http://blog.letsfx.com/</a></p>
    
     <a href="http://www.letsfx.com/donate" target="_blank" style="float: right; background: #FFFFC0; padding: 5px; border: 1px dashed #FF9A35;">
            <img alt="" border="0" src="http://www.letsfx.com/wp-content/uploads/2012/07/btn_donate_LG.gif" >
            </a>
    <div style="clear: both;"></div>
    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    <input type="hidden" name="info_update" id="info_update" value="true" />

    <fieldset class="options">
    <legend><strong>Usage</strong></legend>
    <p>Use this plugin to quickly and easily insert Ads to your posts and BuddyPress Forum topics.</p>
      </fieldset>      
    <table>
        <tr><td>Limit Ads per page: </td><td><input type="text" name="wp_letsfxad_adsused" value=<?php if(get_option('wp_letsfxad_adsused')){ echo get_option('wp_letsfxad_adsused');}else{ echo '3';} ?>></input></td><td></td></tr>        
        <tr><td>Hide for members: </td><td><input type="text" name="wp_letsfxad_hide" value=<?php if(get_option('wp_letsfxad_hide')!=null){ echo get_option('wp_letsfxad_hide');}else{ echo '11';} ?>></input></td><td>Value from 0 to 10 (0=Registered, 10=Administrator) more: http://codex.wordpress.org/User_Levels</td></tr>        
        <tr><td>Blog Post Top Ad: </td><td style="border: solid gray 1px"><textarea rows="6" cols="40"  type="text" name="wp_letsfxad_top" ><?php echo get_option('wp_letsfxad_top'); ?></textarea></td><td style="border: solid gray 1px"><?php echo html_entity_decode(get_option('wp_letsfxad_top')); ?></td></tr>
        <tr><td>Blog Post Bottom Ad: </td><td style="border: solid gray 1px"><textarea rows="6" cols="40" type="text" name="wp_letsfxad_bottom" ><?php echo get_option('wp_letsfxad_bottom'); ?></textarea></td><td style="border: solid gray 1px"><?php echo html_entity_decode(get_option('wp_letsfxad_bottom')); ?></td></tr>
        <tr><td>BuddyPress Above: </td><td style="border: solid gray 1px"><textarea rows="6" cols="40" type="text" name="wp_letsfxad_other1" ><?php echo get_option('wp_letsfxad_other1'); ?></textarea></td><td style="border: solid gray 1px"><?php echo html_entity_decode(get_option('wp_letsfxad_other1')); ?></td></tr>
        <tr><td>BuddyPress Forum Topic Bottom: </td><td style="border: solid gray 1px"><textarea rows="6" cols="40" type="text" name="wp_letsfxad_other2" ><?php echo get_option('wp_letsfxad_other2'); ?></textarea></td><td style="border: solid gray 1px"><?php echo html_entity_decode(get_option('wp_letsfxad_other2')); ?></td></tr>
        <tr><td>Bottom floating Ad: </td><td style="border: solid gray 1px"><textarea rows="6" cols="40" type="text" name="wp_letsfxad_other3" ><?php echo get_option('wp_letsfxad_other3'); ?></textarea></td><td style="border: solid gray 1px"><?php echo html_entity_decode(get_option('wp_letsfxad_other3')); ?></td></tr>
    </table>
    <div class="submit">
        <input type="submit" name="info_update" value="<?php _e('Save'); ?> &raquo;" />
    </div>

    </form>
    </div>

<?php
}
$letsfxad_adsused_c = 0;
  
  function letsfxad_hide(){
      if ( is_user_logged_in() ){
        $letsfxad_hide = html_entity_decode(get_option('wp_letsfxad_hide'));
        global $current_user;
        get_currentuserinfo();
        if($current_user->user_level>=$letsfxad_hide)
            return(true);
    }
    return(false);
  }  
       
  function wp_letsfxad_process($content)
{       
    if(!is_single()) return($content);
    if(letsfxad_hide()) return($content);
    global $letsfxad_adsused_c;
    $content.= '<!-- letsfxad_adsused_c= '.$letsfxad_adsused_c.'-->';
    $letsfxad_adsused    = html_entity_decode(get_option('wp_letsfxad_adsused'));
    if($letsfxad_adsused_c>$letsfxad_adsused)return($content) ;
    
    $top    = html_entity_decode(get_option('wp_letsfxad_top'));
    if(strlen($top)>2) $letsfxad_adsused_c++;
    //if($letsfxad_adsused_c>=$letsfxad_adsused)return(($top).$content) ;
    
    $bottom = html_entity_decode(get_option('wp_letsfxad_bottom'));
    if(strlen($bottom)>2) $letsfxad_adsused_c++;
                                                             
    $more = '<span id="more-'.get_the_ID().'"></span>';
    if($morep=strpos($content,$more)!==false){
      $content = str_replace($more, $more.$top, $content);
      $top='';                                
    }
    
    return $top.$content.($bottom);//.'<textarea>'.$content.'</textarea>'.$morep;//.$_SERVER["HTTP_CF_IPCOUNTRY"];
}
function bb_letsfxad_before_directory(  ) {
    if(letsfxad_hide()) return;
    global $letsfxad_adsused_c;
    $letsfxad_adsused    = html_entity_decode(get_option('wp_letsfxad_adsused'));
    if($letsfxad_adsused_c>=$letsfxad_adsused)return('<!-- letsfxad_adsused_c= '.$letsfxad_adsused_c.'-->') ;
    
    $other1 = html_entity_decode(get_option('wp_letsfxad_other1'));
    if(strlen($other1)<2) return('<!-- letsfxad_adsused_c= '.$letsfxad_adsused_c.'-->');
    $letsfxad_adsused_c++;
    echo ($other1.'<!-- letsfxad_adsused_c= '.$letsfxad_adsused_c.'-->');
}
function bb_letsfxad_topic_footer( $content ) {
    if(letsfxad_hide()) return($content);
    global $letsfxad_adsused_c;
    $letsfxad_adsused    = html_entity_decode(get_option('wp_letsfxad_adsused'));
    if($letsfxad_adsused_c>=$letsfxad_adsused)return($content) ;
    
    $other2 = html_entity_decode(get_option('wp_letsfxad_other2'));           
    if(strlen($other2)<2) return($content);
    $letsfxad_adsused_c++;
    return $content.($other2);
}
function bb_letsfxad_activity( ) {
    if(letsfxad_hide()) return;
    global $letsfxad_adsused_c;
    $letsfxad_adsused    = html_entity_decode(get_option('wp_letsfxad_adsused'));
    if($letsfxad_adsused_c>=$letsfxad_adsused)return ;
    $other2 = html_entity_decode(get_option('wp_letsfxad_other2'));           
    if(strlen($other2)<2) return;
    $letsfxad_adsused_c++;
    echo $other2;
}
function bb_letsfxad_float( ) {
    if(letsfxad_hide()) return;
    global $letsfxad_adsused_c;
    ?> <a href="http://blog.letsfx.com"></a><?php
    echo '<!-- letsfxad_adsused_c= '.$letsfxad_adsused_c.'-->';
    $letsfxad_adsused    = html_entity_decode(get_option('wp_letsfxad_adsused'));
    if($letsfxad_adsused_c>=$letsfxad_adsused)return ;
    $other3 = html_entity_decode(get_option('wp_letsfxad_other3'));
    if(strlen($other3)<2) return;           
    $letsfxad_adsused_c++;
    ?>
    <div id="divadfloat" style="z-index: 9998;width: auto; height: auto; position: fixed ;display: marker;background-color: #F5F5FA; bottom: -500px;left:0;float: left;-webkit-border-radius: 10px; -moz-border-radius: 10px; padding:2px 2px 0px 2px;">
    <small style="background-color: #EDEFF4;color: #3B5998;z-index: 9999;float: left;position: absolute;display:inline ; cursor:pointer;bottom : 0px;left: 150px" onclick="document.getElementById('divadfloat').style.display='none'; ">[Close Ad]</small>
    <?php echo $other3; ?>
    <script type="text/javascript">// <![CDATA[
        setTimeout('letsfxad_delayer()', 7000);
        var letsfxad_timer;
        function letsfxad_delayer(){
            letsfxad_timer = setInterval("letsfxad_scrl()", 1);
            if(!letsfxad_timer) document.getElementById('divadfloat').style.bottom=0;
        }
        function letsfxad_scrl(){
            var i = parseInt(document.getElementById('divadfloat').style.bottom);
            if(i>=0) i = parseInt(eval('document.all.divadfloat'+'.style.bottom'));
            if(i>=0) document.getElementById('divadfloat').style.bottom=0;
            var d = document.getElementById('divadfloat');    
            if(parseInt(d.style.height)<20) return;            
            if(i<-4)
                d.style.bottom=(i+5)+'px';                    
            else
                clearInterval(letsfxad_timer);    
        }
    // ]]></script></div>     
    <?php                              
}


add_filter('bp_before_directory_activity_content', 'bb_letsfxad_before_directory',4);
add_filter('bp_before_directory_groups_content', 'bb_letsfxad_before_directory',4);  
add_filter('bp_before_directory_forums_content', 'bb_letsfxad_before_directory',4);  
add_filter('bp_before_directory_members_content', 'bb_letsfxad_before_directory',4);  
add_filter('bp_before_directory_links_content', 'bb_letsfxad_before_directory',4);      
add_action('wp_footer', 'bb_letsfxad_float');      
//add_filter('bp_after_header', 'bb_letsfxad_float',4);      
//add_filter('', 'bb_letsfxad_before_directory',4);      
                                     
    
add_filter( 'bp_get_the_topic_post_content', 'bb_letsfxad_topic_footer' );
//add_filter( 'bp_get_activity_content_body', 'bb_letsfxad_topic_footer' );
//add_filter( 'bp_activity_entry_content', 'bb_letsfxad_activity' );
                        
add_filter('the_content', 'wp_letsfxad_process');
add_action('admin_menu', 'ad_letsfxad_option_page');

?>

<?php
if (!defined('ABSPATH')) exit;


function get_webstory_plugin_details()
{
    $return=array('wse_active_status' => '','wse_post_type' =>'');

     if ( in_array( 'makestories-helper/makestories.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        // do stuff only if makestories-helper is installed and active
        $return['wse_active_status'] = 'makestories';
        $return['wse_post_type'] ='makestories_story';
      }else if ( in_array( 'web-stories/web-stories.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        // do stuff only if web-stories is installed and active
        $return['wse_active_status'] = 'webstories';
        $return['wse_post_type'] ='web-story';
      }

      return $return;
     
}
<?php
/*
Plugin Name: Yac cms Plugin
Description: Thi is Plugin can perform 
Version: 1.0.0
Author: Itelc.com
Author URI: http://Itelc.com
*/
// function to create the DB / Options / Defaults		

define('ROOTDIR', plugin_dir_path(__FILE__));



if(!function_exists('wpac_plugin_script_yac')){
  function wpac_plugin_script_yac($hook){

    global $pagenow;
    
    if(strpos(get_current_screen()->base, 'youtube-searacher') == false){
      return;
    }else{
    wp_enqueue_style('yac_bootstrap',plugin_dir_url( __FILE__ ).'style/bootstrap/bootstrap.min.css',false,'1.0', 'all' );
    wp_enqueue_style('yac_style',plugin_dir_url( __FILE__ ).'style/style.css',false,'1.0', 'all' );
    wp_enqueue_style('style_font_yac','https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',false,'1.0', 'all' );
    wp_enqueue_script('yac_bootstrap_script',plugin_dir_url( __FILE__ ).'js/bootstrap.min.js','JQuery','1.0.0',true);
    wp_enqueue_script('yac_ajax_script',plugin_dir_url( __FILE__ ).'js/yac_custom.js','JQuery','1.0.0',true);


   //Ajax Calls
   //wp_localize_script( 'yac_single_video_import_ajax_action', 'yacSingleVideoAjaxAction', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
   //wp_localize_script( 'yac_bulk_video_import_ajax_action', 'yacBulkVideoAjaxAction', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

   wp_enqueue_script( 'yac_single_video_import_ajax_action' );
   wp_enqueue_script( 'yac_search_validate_format_video_ajax_action' );
    }
  
  }
  add_action('admin_enqueue_scripts','wpac_plugin_script_yac'); 
}			


function youtube_options_install() {

    global $wpdb;

    $table_name = $wpdb->prefix . "yac";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
			      `id` varchar(3) CHARACTER SET utf8 NOT NULL,
            `title` varchar(50) CHARACTER SET utf8 NOT NULL,
            `duration` varchar(50) CHARACTER SET utf8 NOT NULL,
						`thumbnail` varchar(250) CHARACTER SET utf8 NOT NULL,
						`url` varchar(250) CHARACTER SET utf8 NOT NULL,
            `tags` varchar(250) CHARACTER SET utf8 NOT NULL,
            PRIMARY KEY (`id`)
          ) $charset_collate; ";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'youtube_options_install');

/*
//menu items
add_action('admin_menu','yac_menu');
function yac_menu() {
	
	//this is the main item for the menu
	add_menu_page('Users Options', //page title
	'YAC', //menu title
	'manage_options', //capabilities
	'yac_ui_page', //menu slug
	'yac_ui_page' //function
	);
}
*/

/**
 * 
 */
function youtube_search_page(){

  $page_title = 'Youtube';
  $menu_title = 'Youtbe';
  $capability = 'manage_options';
  $menu_slug  = 'youtube-searacher';
  $function   = 'yac_search_page';
 $icon_url   = '
 dashicons-image-flip-horizontal';
  $position   = 4;

  add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );


  	//this is a submenu
	add_submenu_page('youtube-searacher', //parent slug
	'Video Container', //page title
	'Add Videos', //menu title
	'manage_options', //capability
	'yac_video_container', //menu slug
	'yac_video_container_post_type'); //function

add_submenu_page('youtube-searacher', //parent slug
'Video Container package', //page title
'Add Videos pakage', //menu title
'manage_options', //capability
'yac_video_container_package', //menu slug
'yac_video_Package_post_type'); //function

add_submenu_page('youtube-searacher', //parent slug
'Video section', //page title
'Video section', //menu title
'manage_options', //capability
'yac_video_section_post_type', //menu slug
'yac_video_section_post_type'); //function
}





add_action( 'admin_menu', 'youtube_search_page' );






require_once(ROOTDIR . 'yac-main-page.php');
require_once(ROOTDIR . 'poststype-metabox/posttype.php');
require_once(ROOTDIR . 'libs/ajax.php');
require_once(ROOTDIR . 'libs/search_api.php');
if(strpos($_SERVER['REQUEST_URI'], 'wp-json')){
require_once(ROOTDIR . 'libs/yac_rest_api.php');
}

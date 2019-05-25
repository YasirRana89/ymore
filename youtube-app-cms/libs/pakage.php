<?php

function yac_video_Package_post_type()
{

    $labels = array(
        'name' => __('Package'),
        'singular_name' => __('Package'),
        'add_new' => __('Add New Package'),
        'add_new_item' => __('Add New Package'),
        'edit_item' => __('Edit Package'),
        'new_item' => __('Add New Package'),
        'view_item' => __('View Package'),
        'search_items' => __('Search Package'),
        'not_found' => __('No Package found'),
        'not_found_in_trash' => __('No Package found in trash'),
    );
    $supports = array(
        'title',
    );
    $args = array(
        'labels' => $labels,
        'supports' => $supports,
        'public' => true,
        'capability_type' => 'post',
        'rewrite' => array('slug' => 'Package'),
        'has_archive' => true,
        'menu_position' => 30,
        'menu_icon' => 'dashicons-grid-view',
        'register_meta_box_cb' => 'wporg_add_custom_box_package',
    );
    register_post_type('Package', $args);

}
add_action('init', 'yac_video_Package_post_type');
function wporg_add_custom_box_package()
{
    $screens = ['Package'];
    foreach ($screens as $screen) {
        add_meta_box(
            'wporg_box_id', // Unique ID
            'Custom Meta Box Title', // Box title
            'wporg_custom_box_package_html', // Content callback, must be of type callable
            $screen // Post type
        );
        // add_meta_box(
        //     'wporg_box_id', // Unique ID
        //     'Custom Meta Box Title', // Box title
        //     'wporg_custom_box_html', // Content callback, must be of type callable
        //     $screen // Post type
        // );
    }
}
add_action('add_meta_boxes', 'wporg_add_custom_box_package');

function wporg_custom_box_package_html($post){

    
    $preSelectedVideos = get_post_meta($post->ID, 'container_selected_videos');
    if(!empty($preSelectedVideos)){
        $preSelectedVideos = json_decode($preSelectedVideos);
        $selectedVideosHtml = '';
        foreach ($preSelectedVideos as $video){

            $selectedVideoHiddenFields = '<input name="container_video['.$post->ID.'][id]" value="'.$video->ID.'">';
            $selectedVideoHiddenFields .= '<input name="container_video['.$post->ID.'][title]" value="'.$video->ID.'">';
            $selectedVideoHiddenFields .= '<input name="container_video['.$post->ID.'][videoId]" value="'.$video->ID.'">';
            $selectedVideoHiddenFields .= '<input name="container_video['.$post->ID.'][videoViews]" value="'.$video->ID.'">';
            $selectedVideoHiddenFields .= '<input name="container_video['.$post->ID.'][videoDuration]" value="'.$video->ID.'">';
            $selectedVideoHiddenFields .= '<input name="container_video['.$post->ID.'][id]" value="'.$video->ID.'">';
            $selectedVideoHiddenFields .= '<input name="container_video['.$post->ID.'][id]" value="'.$video->ID.'">';
            $selectedVideoHiddenFields .= '<input name="container_video['.$post->ID.'][id]" value="'.$video->ID.'">';
            $selectedVideoHiddenFields .= '<input name="container_video['.$post->ID.'][id]" value="'.$video->ID.'">';
            $selectedVideoHiddenFields .= '<input name="container_video['.$post->ID.'][id]" value="'.$video->ID.'">';


                $selectedVideosHtml .= ' <div class="row video-'.$post->ID+'">';
                $selectedVideosHtml .= '<div class="col-sm-3"></div>';
                $selectedVideosHtml .= '<div class="col-sm-8">';
                $selectedVideosHtml .= '<a href=""></a>';
                $selectedVideosHtml .= '<p>Views:'.$video->views.' Duration:'.$video->duration.'</p></div>';
                $selectedVideosHtml .= '<div class="col-sm-1">'.$selectedVideoHiddenFields.'<a href="javascript:;" data=vid="video-'.$post->ID.'" onclick="containerVideoDeleteMe(this)></a></div></div>';
        }
    }
    ?>
    <script src="http://localhost/wordpress/wp-content/plugins/youtube-app-cms/js/yac_custom.js?ver=1.0.0"></script>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <input type="text" id="container_post_search" name="container_post_search" placeholder="Search..">
                <div id="container_autocomplete_search_results"></div>
            </div>        
        </div>
        <!--// Selected Videos-->
        <div class="row">
            <div class="col-sm-12">
                <h3>Selected Videos</h3>
                <div class="yac-selected-videos">
                    <?php echo $selectedVideosHtml;?>
                </div><!-- // selected videos end-->
            </div>
        </div>
    </div>

    <?php
}
function wpt_save_pcakage_meta($post_id, $post)
{
    
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    
    if (!isset($_POST['location']) || !wp_verify_nonce($_POST['event_fields'], basename(__FILE__))) {
        return $post_id;
    }    
    
    $key = 'package_video';
    $container_video = esc_textarea($_POST['package_video']);
    
        if (get_post_meta($post_id, $key, false)) {
            update_post_meta($post_id, $key, $container_video);
        } else {    
            add_post_meta($post_id, $key, $container_video);
        }
    
}
add_action('save_post', 'wpt_save_pcakage_meta', 1, 2);
?>
<?php


function wporg_add_custom_box()
{
    $screens = ['container'];
    foreach ($screens as $screen) {
        add_meta_box(
            'wporg_box_id', // Unique ID
            'Custom Meta Box Title', // Box title
            'wporg_custom_box_html', // Content callback, must be of type callable
            $screen // Post type
        );
    }
}
add_action('add_meta_boxes', 'wporg_add_custom_box');

function wporg_custom_box_html($post){

    ?>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.9.1/underscore-min.js"></script>    
     <script src="http://localhost/wordpress/wp-content/plugins/youtube-app-cms/js/yac-js/yac_html_loder.js?ver=1.0.0"></script>
     
     <link rel="stylesheet" id="yac_bootstrap-css" href="http://localhost/wordpress/wp-content/plugins/youtube-app-cms/style/bootstrap/bootstrap.min.css?ver=1.0" type="text/css" media="all">
     <link rel="stylesheet" id="yac_style-css" href="http://localhost/wordpress/wp-content/plugins/youtube-app-cms/style/style1.css?ver=1.0" type="text/css" media="all">
    <div>
        <label for="my_meta_box_post_type">Choose Container's Parts: </label>
        <select name='post_type' id='container_parts'>
            <?php 
            $postTypes = [
                'post'=> 'Video Posts',
                'section'=> 'Section Posts',
                'subsection'=> 'Sub-Section Posts',
                'advert'=> 'Sub-Section Posts',
                'playstor'=> 'Sub-Section Posts'
            ];
            foreach ($postTypes as $postType => $postTypeLabel): 
            ?>
            <option value="<?php echo $postType; ?>"><?php echo $postTypeLabel; ?></option>
            <?php endforeach; ?>
        </select>        
        <button  type="button" onclick="getContainerPart()" class="btn btn-success">Select</button>
        </div>
        <script type="text/html" id='usageList'>
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
                <div class="row">
                    <!-- <?php echo $selectedVideosHtml; ?> -->
                </div>
                </div><!-- // selected videos end-->
            </div>
        </div>
    </div>
</script>

<!-- Create your target -->
<div id="container-parts-wrapper" class="mmm"></div>

     

</div>
    <?php   
}

function wpt_save_container_meta($post_id, $post)
{
    $key = 'container_video';
    $container_video = $_POST['container_video'];

    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    /*
    if (!isset($_POST['location']) || !wp_verify_nonce($_POST['event_fields'], basename(__FILE__))) {
    return $post_id;
    }
     */

    if (get_post_meta($post_id, $key, false)) {
        update_post_meta($post_id, $key, $container_video);
    } else {
        add_post_meta($post_id, $key, $container_video);
    }

}
add_action('save_post', 'wpt_save_container_meta', 1, 2);

// load html template
$path =  plugin_dir_path(__DIR__);
require_once "$path/js/yac-js/section-templates.php";
?>

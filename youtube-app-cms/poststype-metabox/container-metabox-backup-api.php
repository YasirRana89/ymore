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

function wporg_custom_box_html($post)
{

    $preSelectedVideos = get_post_meta($post->ID, 'container_video');

    if (!empty($preSelectedVideos)) {
        $selectedVideosHtml = '';
        foreach ($preSelectedVideos as $videos) {
            foreach ($videos as $video) {

                extract($video);

                $selectedVideoHiddenFields = '<input type="hidden" name="container_video[' . $id . '][id]" value="' . $id . '">';
                $selectedVideoHiddenFields .= '<input type="hidden" name="container_video[' . $id . '][title]" value="' . $title . '">';
                $selectedVideoHiddenFields .= '<input type="hidden" name="container_video[' . $id . '][content]" value="' . $content . '">';
                $selectedVideoHiddenFields .= '<input type="hidden" name="container_video[' . $id. '][categories]" value="' . $categories . '">';
                $selectedVideoHiddenFields .= '<input type="hidden" name="container_video[' . $id . '][featuredImage]" value="' . $featuredImage . '">';
                $selectedVideoHiddenFields .= '<input type="hidden" name="container_video[' . $id . '][tags]" value="' . $tags . '">';
                $selectedVideoHiddenFields .= '<input type="hidden" name="container_video[' . $id . '][videoId]" value="' . $videoId . '">';
                $selectedVideoHiddenFields .= '<input type="hidden" name="container_video[' . $id . '][videoUploadDate]" value="' . $videoUploadDate . '">';
                $selectedVideoHiddenFields .= '<input type="hidden" name="container_video[' . $id . '][videoUrl]" value="' . $videoUrl . '">';
                $selectedVideoHiddenFields .= '<input type="hidden" name="container_video[' . $id . '][videoDuration]" value="' . $videoDuration . '">';
                $selectedVideoHiddenFields .= '<input type="hidden" name="container_video[' . $id . '][videoViews]" value="' . $videoViews . '">';

                $selectedVideosHtml .= ' <div class="col-lg-4 video-' . $post->ID . '">';
                $selectedVideosHtml .= '<div class="col-sm-3"><img src="' . $featuredImage . '"></div>';
                $selectedVideosHtml .= '<div class="col-sm-8">';
                $selectedVideosHtml .= '<a href="">' . $title . '</a>';
                $selectedVideosHtml .= '<p>Views:' . $videoViews . ' Duration:' . $videoDuration . '</p></div>';
                $selectedVideosHtml .= '<div class="col-sm-1">' . $selectedVideoHiddenFields . '<a href="javascript:;" data=vid="video-' . $id . '" onclick="containerVideoDeleteMe(this)></a></div></div>';
            }
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
                <div class="row">
                    <?php echo $selectedVideosHtml; ?>
                </div>
                </div><!-- // selected videos end-->
            </div>
        </div>
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
?>
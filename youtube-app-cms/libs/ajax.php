<?php

require_once 'common.php';

add_action("wp_ajax_yac_handle_single_video_request", "yac_handle_single_video_request");
add_action("wp_ajax_yac_import_bulk_videos_request", "yac_import_bulk_videos_request");
add_action("wp_ajax_yac_Play_video_request", "yac_Play_video_request");
//add_action("wp_ajax_nopriv_handle_single_video_request", "my_must_login");

function yac_handle_single_video_request()
{

    if (!wp_verify_nonce($_REQUEST['nonce'], "yac_single_import_nonce")) {
        exit("No naughty business please");
    }

    $response = [];
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

        // save and create post
        $data = $_POST['data'];
        $postData = [
            "title" => $data['title'],
            "description" => $data['description'],
            "short_description" => $data['description'],
            "tags_input" => $data['tags_input'],
        ];
        $uploder_data = [
            "username" => $data['username'],
            "url" => $data['url'],
            "verified" => $data['verified'],
        ];
        $image_url = $data['image'];
        $custom_fields = [
            'duration' => $data['duration'],
            'views' => $data['views'],
            'video_id' => $data['video_id'],
            'video_url' => $data['video_url'],
            'upload_date' => $data['upload_date'],
            'uploader_url' => $data['uploader_url'],
        ];

        $is_duplicate = yac_get_meta_values('video_id', $data['video_id']);
        if (count($is_duplicate) == 0) {

            $postID = yac_create_post($postData,$uploder_data);

            if (!empty($postID)) {
                $response['post_id'] = $postID;
                // save image
                yac_save_image($postID, $image_url);

                // save custome fields
                yac_save_custom_field($postID, $custom_fields);
                //yac_save_tags($postID);
            }
        }
    } else {

    }

    echo json_encode($response);
    die();

}

function yac_import_bulk_videos_request()
{

    if (!wp_verify_nonce($_REQUEST['nonce'], "yac_bulk_import_nonce")) {
        exit("No naughty business please");
    }

    $response = [];
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {


        
        $response = [];
        $bulk_videos = $_POST['data'];
        foreach ($bulk_videos as $key => $videos) {
            
            $userdata = $videos['uploader'];
            $video = $videos['video'];
            
            //print_r($video); exit;
            $postData = [
                "title" => $video['title'],
                "description" => $video['snippet'],
                "short_description" => $video['snippet'],
                "tags_input" => $video['tags_input'],
            ];

            

            $uploder_data = [
                "username" => $userdata['username'],
                "url" => $userdata['url'],
                "verified" => $userdata['verified'],
            ];
            
            $video_id = yac_get_video_id_from_url($video['url']);
             $image_url = "https://img.youtube.com/vi/$video_id/mqdefault.jpg"; 

            

            $custom_fields = [
                'duration' => $video['duration'],
                'views' => $video['views'],
                'video_id' => $video_id,
                'video_url' => $video['url'],
                'upload_date' => $video['upload_date'],
                'uploader_url' => $userdata['uploader_url'],
            ];

            //die($video_id);

            //validation
            $is_duplicate = yac_get_meta_values('video_id', $video_id);
            if (count($is_duplicate) == 0) {
               $postID = yac_create_post($postData);
               yac_save_image($postID, $image_url);
               yac_save_custom_field($postID, $custom_fields);

               $response['success'][] = $bulk_videos[$key];

            }else{
                $response['duplicate'][] = $bulk_videos[$key];
            }
        }
        echo json_encode($response);exit;
    }

}


// function yac_Play_video_request()
// {

//     if (!wp_verify_nonce($_REQUEST['nonce'], "yac_Play_video_nonce")) {
//         exit("No naughty business please");
//     }

//     $response = [];
//     if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

//         // save and create post
//         $data = $_POST['data'];
//         $postData = [
//             "title" => $data['title'],
//             "description" => $data['description'],
//             "short_description" => $data['description'],
//             "tags_input" => $data['tags_input'],
//         ];
//         $image_url = $data['image'];
//         $custom_fields = [
//             'duration' => $data['duration'],
//             'views' => $data['views'],
//             'video_id' => $data['video_id'],
//             'video_url' => $data['video_url'],
//             'upload_date' => $data['upload_date'],
//             'uploader_url' => $data['uploader_url'],
//         ];

//         $is_duplicate = yac_get_meta_values('video_id', $data['video_id']);
//         if (count($is_duplicate) == 0) {

//             $postID = yac_create_post($postData);

//             if (!empty($postID)) {
//                 $response['post_id'] = $postID;
//                 // save image
//                 yac_save_image($postID, $image_url);

//                 // save custome fields
//                 yac_save_custom_field($postID, $custom_fields);
//                 //yac_save_tags($postID);
//             }
//         }
//     } else {

//     }

//     echo json_encode($response);
//     die();

// }


//create the post
function yac_create_post($postData)
{

    $postTitle = $postData['title'];
    $postDesc = $postData['description'];
    $postShortDesc = $postData['short_description'];
    $postTags = $postData['tags_input'];

    $postData = array(
        //'ID'       => 4,
        'post_content' => $postDesc,
        'post_title' => $postTitle,
        'post_excerpt' => $postShortDesc,
        'post_status' => 'publish',
        'tags_input' => $postTags,
        'post_type' => 'post',
        'comment_status' => 'closed',
        'ping_status' => 'closed',

    );
   // print_r($postData); 
    $postResponse = wp_insert_post($postData);
    //var_dump($postResponse);
    return $postResponse;
}

function yac_save_custom_field($postID, $custom_fields)
{

    foreach ($custom_fields as $key => $val) {

        if($key == 'views') $val = str_replace( ',', '', $key );
        update_post_meta($postID, $key, $val);
    }

}

//set the featured image
function yac_save_image($postID, $image_url)
{
    
    $image_name = 'wp-header-logo.png';
    $upload_dir = wp_upload_dir(); // Set upload folder
    $image_data = file_get_contents($image_url); // Get image data
    $unique_file_name = wp_unique_filename($upload_dir['path'], $image_name); // Generate unique name
    $filename = basename($unique_file_name); // Create image file name

// Check folder permission and define file location
    if (wp_mkdir_p($upload_dir['path'])) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $image_url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
    $image = curl_exec($ch);
    $info = curl_getinfo($ch);
 
    //print_r($info);
    // If image is found than save it to a file.
    if($info['http_code'] == 200) {
        // Store thumbnails in the given directory, change this
        // to your liking.
        file_put_contents($file, $image);
    

    
// Create the image  file on the server
    //file_put_contents($file, $image_data);

// Check image file type
    $wp_filetype = wp_check_filetype($filename, null);

// Set attachment data
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit',
    );

// Create the attachment
    $attach_id = wp_insert_attachment($attachment, $file, $postID);

// Include image.php
    require_once ABSPATH . 'wp-admin/includes/image.php';

// Define attachment metadata
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);

// Assign metadata to attachment
    wp_update_attachment_metadata($attach_id, $attach_data);

// And finally assign featured image to post
    set_post_thumbnail($postID, $attach_id);
    }
    else{
      //  echo 'no thumbnails';
    }
    
}

//save the tags
function yac_save_tags($postID, $taxonomy = 'post_tag', $append = false)
{
    $postTags = $_POST['data']['tags'];
    if (!$postID) {
        return false;
    }
    if (empty($postTags)) {
        $postTags = array();
    }
    if (!is_array($postTags)) {
        $comma = _x(',', 'tag delimiter');
        if (',' !== $comma) {
            $postTags = str_replace($comma, ',', $postTags);
        }
        $postTags = explode(',', trim($postTags, " \n\t\r\0\x0B,"));
    }
    if (is_taxonomy_hierarchical($taxonomy)) {
        $postTags = array_unique(array_map('intval', $postTags));
    }
    return wp_set_object_terms($postID, $postTags, $taxonomy, $append);
}
add_action('wp_insert_post','yac_save_tags');

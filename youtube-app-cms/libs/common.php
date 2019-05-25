<?php


// validate video for duplication
function yac_is_video_exist($video_id){
    $response = yac_get_meta_values('video_id', $video_id);
    
    return $response;
}

// extect Youtube video id
function yac_get_video_id_from_url($url){
    list($garbage, $video_id) = explode('v=',$url);
    return $video_id;
}



function yac_get_meta_values( $key, $value, $type = 'post', $status = 'publish' ) {

    global $wpdb;

    if( empty( $key ) )
        return;
    $sql = "
    SELECT pm.meta_value FROM {$wpdb->postmeta} pm        
    WHERE pm.meta_key = '%s'         
    AND pm.meta_value = '%s' ";
    $r = $wpdb->get_col( $wpdb->prepare( $sql, $key, $value ) );

//LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
//AND p.post_status = '%s' 
//AND p.post_type = '%s'

    return $r;
}



/************************************* */
/** API Helpers */
/************************************** */


/**
 * Prepair post response for INIT API 
 */
function yacGetAllPosts($type, $limit){

    $args = array(
        'posts_per_page' => $limit,
        'post_type' => array( 'post' )
    );

    switch($type){
        case 'sticky':
            $args['posts_per_page'] = $limit;
            $args['post__in'] = get_option( 'sticky_posts' );
        break;
        case 'all':
        $args['posts_per_page'] = -1;
        break;
        case 'top':
        $args['posts_per_page'] = $limit;
        $args['meta_key'] = 'views';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';

        break;
        case 'slide':
        $args['posts_per_page'] = $limit;
        $args['tags_input'] = 'tags_input';
        $args['order'] = 'DESC';

        break;
    }

    $posts = get_posts( $args );
    $response = [];
    foreach( $posts as $post ) {
        $response[] = yacDesignPostResponse($post);
    }
    return $response;

}




  
/**
 * 
 */
function yacDesignPostResponse($post){

    // $featured_image_url = wp_get_attachment_image_src( $post->ID, 'original' ); // get url of the original size    
    // if( ! $featured_image_url ) {
    //     $featuredImage = $featured_image_url[0];
    // }

    $post_thumbnail_id = get_post_thumbnail_id($post->ID);
    $imageSRC = wp_get_attachment_image_src($post_thumbnail_id, 'full');
    if ( ! $post_thumbnail_id ) {
        return false;
    }
  
    

    $response = [];
    $response['id'] = $post->ID;
    $response['title'] = $post->post_title;
    $response['label'] = $post->post_title;
    $response['content'] = $post->post_content;
    $response['featuredImage'] = $imageSRC[0];
    $response['categories'] = [];
    $response['tags'] = $post->tags ;

    
    // add post custom metadata
    $metadata = get_post_meta( $post->ID);
    $response['videoId'] = $metadata['video_id'][0];
    $response['videoUrl'] = $metadata['video_url'][0];
    $response['videoUploadDate'] = $metadata['upload_date'][0];
    $response['videoViews'] = $metadata['views'][0];
    $response['videoDuration'] = $metadata['duration'][0];
    return $response;
  }

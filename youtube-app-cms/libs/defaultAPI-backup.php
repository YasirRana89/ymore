<?php
/*  function yacGetPostsList($page=1){
    
    $url = "http://itelc.com/wbs/wp-json/wp/v2/posts"; 
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1
        
    ]);

    $jsonResponse = curl_exec($curl);    
    curl_close($curl);
    $jsonResponse =json_decode($jsonResponse);
    print_r($jsonResponse);
    $response = [];
    foreach($jsonResponse as $post){
        print_r($post); exit;
        $postId =  $post->id;
        $featured_image_id = $postId; // get featured image id
        $featured_image_url = wp_get_attachment_image_src( $featured_image_id, 'original' ); // get url of the original size
    
	if( $featured_image_url ) {
		$featuredImage = $featured_image_url[0];
    }
        
        $response['id'] = $postId;
        $response['title'] = $post->title;
        $response['content'] = $post['content']['rendered'];
        $response['featuredImage'] = $featuredImage;
        
        // add post custom metadata
        $metadata = get_post_meta( $postId);
        $response['video_id'] = $metadata['video_id'][0];
        $response['video_url'] = $metadata['video_url'][0];
        $response['video_upload_date'] = $metadata['upload_date'][0];
        $response['video_views'] = $metadata['views'][0];
        $response['video_duration'] = $metadata['duration'][0];


    }

    json_encode($response); exit;

  }





function yac_rest_posts( $data, $post, $context ) {

	$featured_image_id = $data->data['featured_media']; // get featured image id
    $featured_image_url = wp_get_attachment_image_src( $featured_image_id, 'original' ); // get url of the original size
    
	if( $featured_image_url ) {
		$data->data['featured_image_url'] = $featured_image_url[0];
    }
    $excerpt = $data->data['excerpt'];
    $title = $data->data['title'];
    $content = $data->data['content'];

    //removing unwanter params
   
    unset(
        $data->data['ping_status'], 
        $data->data['template'],
        $data->data['type'],
        $data->data['guid'], 
        $data->data['date_gmt'], 
        $data->data['slug'], 
        $data->data['title'], $data->data['content'],
        $data->data['excerpt'],
        $data->data['format'],
        $data->data['comment_status'],
        $data->data['featured_media'],
        $data->data['author'],
        $data->data['modified_gmt'],
        $data->data['links'],
        $data->data['categories'],
        $data->data['meta']
    //$data->data['']
);

    // removing extra links
    $data->remove_link( 'collection' );
    $data->remove_link( 'self' );
    $data->remove_link( 'about' );
    $data->remove_link( 'author' );
    $data->remove_link( 'replies' );
    $data->remove_link( 'version-history' );
    $data->remove_link( 'https://api.w.org/featuredmedia' );
    $data->remove_link( 'https://api.w.org/attachment' );
    $data->remove_link( 'https://api.w.org/term' );
    $data->remove_link( 'predecessor-version' );
    $data->remove_link( 'curies' );


    // add post custom metadata
    $metadata = get_post_meta( $data->data['id']);
    $data->data['video_id'] = $metadata['video_id'][0];
    $data->data['video_url'] = $metadata['video_url'][0];
    $data->data['video_upload_date'] = $metadata['upload_date'][0];
    $data->data['video_views'] = $metadata['views'][0];
    $data->data['video_duration'] = $metadata['duration'][0];

    //print_r($metadata);

    //adding required params
    $data->data['excerpt'] = strip_tags($excerpt['rendered']);
    $data->data['title'] = strip_tags($title['rendered']);
    $data->data['content'] = strip_tags($content['rendered']);
	
	return $data;
}
//add_filter( 'rest_prepare_post', 'yac_rest_posts', 10, 3 );



// WP Categories API 

function yac_rest_categories( $data, $post, $context ) {

    $data->data['featured_image_url'] = "";
	if( function_exists('cfix_featured_image') ) {
        $image = cfix_featured_image_url( array( 'size' => 'large', 'cat_id' => $data->data['id'] ));
        
		$data->data['featured_image_url'] = $image;
    }else{
        $data->data['featured_image_url'] = 'cfix_featured_image does not exist';
    }
    

    //removing unwanter params
   
    unset(
        $data->data['ping_status'], 
        $data->data['template'],
        $data->data['type'],
        $data->data['guid'], 
        $data->data['date_gmt'], 
        $data->data['slug'], 
        $data->data['title'], $data->data['content'],
        $data->data['excerpt'],
        $data->data['format'],
        $data->data['comment_status'],
        $data->data['featured_media'],
        $data->data['author'],
        $data->data['modified_gmt'],
        $data->data['links'],
        $data->data['parent'],
        $data->data['meta']
    //$data->data['']
);

    // removing extra links
    $data->remove_link( 'collection' );
    $data->remove_link( 'about' );
    $data->remove_link( 'self' );
    $data->remove_link( 'https://api.w.org/post_type' );
    $data->remove_link( 'curies' );


	
	return $data;
}
//add_filter( 'rest_prepare_category', 'yac_rest_categories', 10, 3 );
*/



function stackoverflow_code_image(){
    $image_path = "jcMHt.jpg";

    $jpg = imagecreatefromjpeg($image_path);
    $black = array("red" => 0, "green" => 0, "blue" => 0, "alpha" => 0);
    
    $removeLeft = 0;
    for($x = 0; $x < imagesx($jpg); $x++) {
        for($y = 0; $y < imagesy($jpg); $y++) {
            if(imagecolorsforindex($jpg, imagecolorat($jpg, $x, $y)) != $black){
                break 2;
            }
        }
        $removeLeft += 1;
    }
    
    $removeRight = 0;
    for($x = imagesx($jpg)-1; $x > 0; $x--) {
        for($y = 0; $y < imagesy($jpg); $y++) {
            if(imagecolorsforindex($jpg, imagecolorat($jpg, $x, $y)) != $black){
                break 2;
            }
        }
        $removeRight += 1;
    }
    
    $removeTop = 0;
    for($y = 0; $y < imagesy($jpg); $y++) {
        for($x = 0; $x < imagesx($jpg); $x++) {
            if(imagecolorsforindex($jpg, imagecolorat($jpg, $x, $y)) != $black){
                break 2;
            }
        }
        $removeTop += 1;
  }


}
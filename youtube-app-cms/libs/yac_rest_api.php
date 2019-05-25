<?php

require_once 'common.php';

/**
 * 
 */
add_action( 'rest_api_init', function () {
    register_rest_route( 'yacrest/v1', '/posts', array(
      'methods' => 'GET',
      'callback' => 'yac_posts_api',
    ) );
  } );

  function yac_posts_api( $data ) {

    $categoryId = $_GET['categoryid'];
    $query = $_GET['q'];
    $page = empty($_REQUEST['page']) ? 1:$_REQUEST['page'];
    $limit = empty($_REQUEST['limit']) ? 10:$_REQUEST['limit'];
    //print_r($_REQUEST);    die($_REQUEST);
    $args = [
        'posts_per_page' => $limit,
        'paged' => $page
    ];
    if(!empty($query)){
      $args['s'] = $query;
    }
    if(!empty($categoryId)){
        $args['category'] = ["tax_query" => array(
            array(
                "taxonomy" => "category",
                "field"    => "id",
                "terms"    => $categoryId,
            )
        )];
    }


    $posts = get_posts( $args );

    $response = [];
    $i = 0;
    foreach($posts as $post):        
        $postId =  $post->ID;
        $response[$i] = yacDesignPostResponse($post);       
        $i++;
    endforeach;
    wp_reset_postdata();
        return $response;
    
  }

/**
 * 
 */
  add_action( 'rest_api_init', function () {
    register_rest_route( 'yacrest/v1', '/posts/(?P<id>\d+)', array(
      'methods' => 'GET',
      'callback' => 'yac_singlepost_api',
    ) );
  } );


  function yac_singlepost_api( $data ) {

    $postId = $data['id'];
    $args = [
        
    ];
    $posts = get_posts( $args );
    $response = [];
    $i = 0;
    foreach($posts as $post):        
        $postId =  $post->ID;
        $response[$i] = yacDesignPostResponse($post);       
        $i++;
    endforeach;
    wp_reset_postdata();
        return $response;
    
  }

  


/**
 * 
 */
  add_action( 'rest_api_init', function () {
    register_rest_route( 'yacrest/v1', '/category', array(
      'methods' => 'GET',
      'callback' => 'yac_category_api',
    ) );
  } );

function yac_category_api($data){

    $args = array('taxonomy' => 'category', 'show_post_count'=>true );
    $categories = get_categories($args);
    //print_r($categories);
    $response = [];
    $i = 0;
    foreach ($categories as $category){
        $featuredImage = cfix_featured_image_url( array( 'size' => 'large', 'cat_id' => $category->term_id) );
        $response[$i]['id'] = $category->term_id;
        $response[$i]['name'] = $category->name;
        $response[$i]['slug'] = $category->slug;
        $response[$i]['featuredImage'] = $featuredImage;
        $response[$i]['totalposts'] = $category->category_count;
        
        $i++;
    }
    return $response;
  }


/**
 * 
 */
add_action( 'rest_api_init', function () {
    register_rest_route( 'yacrest/v1', '/init', array(
      'methods' => 'GET',
      'callback' => 'yac_splash_api',
    ) );
  } );

function yac_splash_api($post){
    
    $response = [];
    $response['posts'] = yacGetAllPosts('all',-1);
    $response['featured'] = yacGetAllPosts('sticky',10);
    $response['categories'] = yac_category_api('');
    $response['topposts'] = yacGetAllPosts('top', 10);
    $response['slides'] = yacGetAllPosts('slide', 10);
    return $response;
  }


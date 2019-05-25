<?php

require_once 'common.php';

add_action("wp_ajax_yac_search_validate_format_video", "yac_search_validate_format_video");


function yac_search_validate_format_video(){


    if (!wp_verify_nonce($_REQUEST['nonce'], "yac_search_api")) {
        exit("No naughty business please");
    }

    $search = $_REQUEST['yac_search'];
    $page = $_REQUEST['page'];

    $url = "https://youtube-scrape.herokuapp.com/api/search?q=$search"; 
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1
        
    ]);

    $resp = curl_exec($curl);    
    curl_close($curl);

        

        
    $html = '';    
    $result = json_decode($resp);    
    
    $result = $result->results;
    $bulkjson = [];
    foreach ($result as $index => $videoData){
        

        // filter vides for bulk import
        


        //extract($video);
        //print_r($video);
        $video = $videoData->video;
        $uploader = $videoData->uploader;

        $video_id = yac_get_video_id_from_url($url);
        $is_video_exist = yac_is_video_exist($video_id);

        $url = $video->url;
        $title = $video->title;
        $duration = $video->duration;
        //$thumbnail_src = $video->thumbnail_src;
        $thumbnail_src = "https://img.youtube.com/vi/$video_id/mqdefault.jpg";
        $description = $video->snippet;
        $upload_date = $video->upload_date;
        $views = $video->views;

        
       
       
        if (count($is_video_exist) == 0) {            
            $bulkjson[] = $videoData;
            $nonce = wp_create_nonce("yac_single_import_nonce");
            $jsonData = json_encode($videoData);
            $nonce = wp_create_nonce("yac_Play_video_nonce");
            $button1 = '<div class="overlay"></div><div class="button-container"><a class="btn btn-success" data-videourl="'.$url.'" data-videoid="'.$video_id.'" data-nonce="'.$nonce.'" onclick="yacPlayVideo(this)" href="'.$url.'"><i class="fa fa-play"></i></a></div></div>';
            $button = '<div class="overlay"></div><div class="button-container"><a class="btn export" data-videourl="'.$url.'" data-videoid="'.$video_id.'" data-title="'.$title.'" data-videoviews="'.$views.'" data-upload_date="'.$upload_date.'" data-description="'.$description.'" data-image="'.$thumbnail_src.'" data-duration="'.$duration.'" data-nonce="'.$nonce.'" onclick="yacImportSingleVideo(this)" href="#"><i class="fa fa-download"></i></a></div></div>';
        }else{
            $button = '<div class="button-container"></div><a class="btn btn-danger export"><i class="fa fa-upload"></i></a></div><div class="overlay"></div>';
        }


        //design page
        $htmlSting =  <<<EOE
                <div class="col-md-2">
                    <div class="card yac-card">
                        <img class="card-img-top img-responsive" src="$thumbnail_src" alt="Card image cap">
                        <div class="card-body">
                        <h6 class="card-title">$title</h6>
                        <span class="fa fa-time">$duration</span>
                        <span class="fa fa-eye">$views</span>
                            $button  
                            $button1                      
                    </div>
                </div>
                
EOE;

    $html .= $htmlSting;

    }



    $html .= '<script>var all_videos = '.json_encode($bulkjson).'</script>'; 
    die($html);
}




?>
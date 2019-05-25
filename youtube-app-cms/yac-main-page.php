<?php

function yac_search_page()
{
    global $wpdb;

    ?>

    

    <!--search box-->
    <section id="crad-view">
          <div class="container container-search">
          <div class="row">
                    <div class="col-md-10">
                    <form class="youtube-search-form" action="" id="yac_search_form" onsubmit="return yacSearchVideos(this);">
                     <input type="text" placeholder="Search.." name="yac_search" id="yac_search">
                     <input type="hidden"  name="nonce" value="<?php echo wp_create_nonce("yac_search_api");?>">
                     <input type="hidden"  name="action" value="yac_search_validate_format_video">
                     <button class="btn-search-btn" type="submit">Search</button>
                  </form>
                </div>
                <div class="col-md-2">
                    <?php
                    $nonce = wp_create_nonce("yac_bulk_import_nonce");
                    echo '<button class="btn-export btn btn-primary " data-nonce="'.$nonce.'" onclick="yacImportBulkVideosRequest(this)"><i class="fa fa-download"></i></button>';?>
                    
                </div>
                <div class="bulk-import-status col-sm-12"></div>
            </div>
        </div>



        <!--search results -->
        <div class="container-fluid youtube-search-container">
            <div class="row yac-search-result">
                <div class="col-md-3">
                    <div class="card yac-card" >
                        <img class="card-img-top img-responsive" src="https://pcafalcons.com/wp-content/uploads/2018/02/wallpaper-love-photo-3-1080x675.jpg" alt="Card image cap">
                        <div class="card-body">
                        <h5 class="card-title">Somthing Fishing</h5>
                        <a href="#" class="card-link">lets search</a>
                        
                        </div>
                    </div>
                </div>
                
                
            </div>
            <!--paginantion -->
            <div class="row">
            <div class="col-md-4 col-lg-offset-6">
                <nav class="nav-pagination" aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="#">Previous</a></li>    
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
                </nav>
            </div>
        </div>
        </div>
  


   

</section>

   
    <?php
}
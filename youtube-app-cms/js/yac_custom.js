var AJAX_URL = "http://localhost/wordpress/wp-admin/admin-ajax.php";
// var AJAX_URL = "http://itelc.com/wbs/wp-admin/admin-ajax.php";

function yacImportSingleVideo(me) {

    //var post_id = $(me).attr("rel"); //this is the post id
    

    if(jQuery(me).data('title') == '')
    return false;
    

    var jsonData = {
        title: jQuery(me).data('title'),
        duration: jQuery(me).data('duration'),
        description: jQuery(me).data('description'),
        url: jQuery(me).data('url'),
        image: jQuery(me).data('image'),
        views: jQuery(me).data('videoviews'),
        upload_date: jQuery(me).data('upload_date'),
        video_id:jQuery(me).data('videoid'),
        video_url: jQuery(me).data('videourl'),
        uploader_url: jQuery(me).data('uploader_url'),
        tags: ""
    };

    var nonce = jQuery(me).data("nonce");

    jQuery.ajax({
        url: AJAX_URL,
        type: 'Post',
        data: {
            action: 'yac_handle_single_video_request',
            nonce: nonce,
            data: jsonData
        },
        success: function (data) {
            // What I have to do...
            
        },
        fail: function () { }
    });


}

//javascript:;

function yacSearchVideos(form) {

    if (jQuery('#yac_search').val() !== '') {

        jQuery.ajax({
            url: AJAX_URL,
                
            type: 'POST',
            data: jQuery(form).serialize(), 
            dataType: 'html',            
            success: function (response) {
                // What I have to do...
                jQuery('.yac-search-result').empty().html(response);
                console.log(       response     )
            },
            fail: function () { }
        });
    }
    return false;
}




function yacPlayVideo(me) {

    //var post_id = $(me).attr("rel"); //this is the post id
    

    if(jQuery(me).data('title') == '')
    return false;
    

    var jsonData = {
        url: jQuery(me).data('url'),
        video_id:jQuery(me).data('videoid'),
        video_url: jQuery(me).data('videourl'),
        tags: ""
    };

    var nonce = jQuery(me).data("nonce");

    jQuery.ajax({
        url: AJAX_URL,
            
        type: 'Post',
        data: {
            action: 'yac_import_bulk_videos_request',
            nonce: nonce,
            data: jsonData
        },
        success: function (data) {
            // What I have to do...
            
        },
        fail: function () { }
    });


}



function yacImportBulkVideosRequest(me) {

    //var post_id = $(me).attr("rel"); //this is the post id

    var btnexport = document.querySelector('.btn-export');
    
    btnexport.addEventListener("click", function() {
        btnexport.innerHTML = "Signing In";
        btnexport.classList.add('spinning');
        
      setTimeout( 
            function  (){  
                btnexport.classList.remove('spinning');
                btnexport.innerHTML = "";
                
            }, 6000);
    }, false);

    $(me).attr('disabled', true);
    $('.bulk-import-status').empty();
    if(all_videos == '')
    return false;
    

    var nonce = jQuery(me).data("nonce");

    jQuery.ajax({
        url: AJAX_URL,
            
        type: 'Post',
        dataType: 'json',
        data: {
            action: 'yac_import_bulk_videos_request',
            nonce: nonce,
            data: all_videos
        },
        success: function (data) {
            
           // console.log(data)
           
            $(me).attr('disabled', false);
            if(data.duplicate.length > 0){
                $('.bulk-import-status').html('<div class="alert alert-danger">'+data.duplicate.length+' Videos aare duplicate </div>');
            }
            if(data.success.length > 0){
                $('.bulk-import-status').html('<div class="alert alert-success">'+data.success.length+' Videos successfully imported!</div>');
            }


            // What I have to do...
            console.log(data);

        },
        fail: function () { 
            $(me).attr('disabled', false);
            $(me).next('alert').addClass('alert-error').html();
        }
    });


}


jQuery(document).ready(function(){
    //auto complete
    

    jQuery('#container_post_search').autocomplete({
        minChars: 3,
        appendTo: '#container_autocomplete_search_results',
        source: 'http://localhost/wordpress/wp-json/yacrest/v1/posts',
        select: function (ui, item) {

            console.log(item.item)
            var videoViews = item.item.videoViews;
            var videoDuration = item.item.videoDuration;
            var title = item.item.title;
            var content = item.item.content;
            var categories = item.item.categories;
            var postId = item.item.id;
            var featuredImage = item.item.featuredImage;
            var tags = item.item.tags;
            var videoId = item.item.videoId;
            var videoUploadDate = item.item.videoUploadDate;
            var videoUrl = item.item.videoUrl;

            


            var hiddenFields = '<input type="hidden" name="container_video['+postId+'][id]" value="'+postId+'">';
                hiddenFields += '<input type="hidden" name="container_video['+postId+'][title]" value="'+title+'">';
                hiddenFields += '<input type="hidden" name="container_video['+postId+'][content]" value="'+content+'">';
                hiddenFields += '<input type="hidden" name="container_video['+postId+'][categories]" value="'+categories+'">';
                hiddenFields += '<input type="hidden" name="container_video['+postId+'][featuredImage]" value="'+featuredImage+'">';
                hiddenFields += '<input type="hidden" name="container_video['+postId+'][tags]" value="'+tags+'">';
                hiddenFields += '<input type="hidden" name="container_video['+postId+'][videoId]" value="'+videoId+'">';
                hiddenFields += '<input type="hidden" name="container_video['+postId+'][videoUploadDate]" value="'+videoUploadDate+'">';
                hiddenFields += '<input type="hidden" name="container_video['+postId+'][videoUrl]" value="'+videoUrl+'">';
                hiddenFields += '<input type="hidden" name="container_video['+postId+'][videoDuration]" value="'+videoDuration+'">';
                hiddenFields += '<input type="hidden" name="container_video['+postId+'][videoViews]" value="'+videoViews+'">';



            var html = ' <div class="row video-'+postId+'">';
            html += '<div class="col-sm-3"><img src="'+featuredImage+'"></div>';
            html += '<div class="col-sm-8">';
            html += '<a href="">'+title+'</a>';
            html += '<p>Views:'+videoViews+' Duration:'+videoDuration+'</p></div>';
            html += '<div class="col-sm-1">'+hiddenFields+'<a href="javascript:;" data=vid="video-'+postId+'" onclick="containerVideoDeleteMe(this)></a></div></div>';


            // append
            jQuery('.yac-selected-videos').append(html);
            jQuery('#container_post_search').val('');
        }
    });

});

function containerVideoDeleteMe(me){

}



function getContainerPart(){
    var selectedPostType = jQuery('#container_parts').val();
    switch(selectedPostType){
        
        case "post":
            getPostSection();
        break;

        case "section":
            getSectionSection();
        break;

        case "subsection":
            getSubsectionSection();
        break;

        case "advert":
            getAdvertSection();
        break;

        case "playstore":
            getPlaystoreSection();
        break;
        
    }

}




function getPostSection(){
    //alert()
    var template = jQuery("#_post-section").html();
    //console.log(_.template(template))
    jQuery('#container-parts-wrapper').append(template);    

}

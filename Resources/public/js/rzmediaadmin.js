jQuery(document).ready(function () {
    rzmediaadmin.init();
});


var rzmediaadmin = {
    init:function(){
        rzmediaadmin.initMasonry();
    },

    initMasonry: function() {
        if($(".rz-masonry-gallery").length > 0){
            $(".rz-masonry-gallery").imagesLoaded(function(){
                $(".rz-masonry-gallery").masonry({
                    itemSelector: '.rz-gallery-item',
                    isAnimated: true,
                    isFitWidth: true,
                    gutter: 10
                });
            });
        }
    }
}

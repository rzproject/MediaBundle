var rzmediacarousel = {
    settings: null,
    instance: null,
    init:function(){
        rzmediacarousel.initCarousel();
    },

    initCarousel: function() {
        if(jQuery(".bxslider").length > 0){
            rzmediacarousel.instance = jQuery(".bxslider").bxSlider({
                minSlides: 0,
                maxSlides: 1,
                slideMargin: 0,
                video: true,
                adaptiveHeight: true,
                auto: true,
                autoHover: true
            });
        }
    },

    reload: function() {
        rzmediacarousel.instance.reloadSlider({
            minSlides: 0,
            maxSlides: 1,
            slideMargin: 0,
            video: true,
            adaptiveHeight: true,
            auto: true,
            autoHover: true
        });
    }
}

var rzmediabxslider = {
    settings: null,
    instance: null,
    init:function(){
        rzmediabxslider.initCarousel();
    },

    initCarousel: function() {
        if(jQuery(".bxslider").length > 0){
            rzmediabxslider.instance = jQuery(".bxslider").bxSlider({
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
        rzmediabxslider.instance.reloadSlider({
            minSlides: 0,
            maxSlides: 1,
            slideMargin: 0,
            video: true,
            adaptiveHeight: true,
            auto: true,
            autoHover: true
        });

        jQuery('.bx-next').on('click', function(e) {
            rzmediabxslider.instance.goToNextSlide();
            e.preventDefault();
        });

        jQuery('.bx-prev').on('click', function(e) {
            rzmediabxslider.instance.goToPrevSlide();
            e.preventDefault();
        });

    }
}

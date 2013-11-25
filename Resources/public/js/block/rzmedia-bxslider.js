var rzmediabxslider = {
    settings: null,
    instance: null,
    init:function(settings){
        rzmediabxslider.settings = settings;
        rzmediabxslider.initCarousel();
    },

    initCarousel: function() {
        if(jQuery(".bxslider").length > 0){
            rzmediabxslider.instance = jQuery(".bxslider").bxSlider(rzmediabxslider.settings);
        }
    },

    reload: function() {
        rzmediabxslider.instance.reloadSlider(rzmediabxslider.settings);

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

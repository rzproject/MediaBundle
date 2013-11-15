var rzmediacarousel = {

    interval: 5000,
    pause: 'hover',

    init:function(){
        rzmediacarousel.initCarousel();
    },

    initCarousel: function() {
        if(jQuery(".rz-media-carousel").length > 0){
            console.log('rommel');
            jQuery(".rz-media-carousel").carousel({
                interval: rzmediacarousel.interval,
                pause: rzmediacarousel.interval
            });
        }
    }
}

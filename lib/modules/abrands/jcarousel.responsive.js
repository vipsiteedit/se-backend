(function($) {
    $(function() {
        var jcarousel = $('.jcarousel');

        jcarousel
            .on('jcarousel:reload jcarousel:create', function () {
                var carousel = $(this),
                    width = carousel.innerWidth();
                    
                    
                if (width >= 1199) {
                    width = width / 6;
                } else if (width >= 991) {
                    width = width / 5;
                } else if (width >= 768) {
                    width = width / 4;
                } else if (width >= 480) {
                    width = width / 3;
                } else if (width >= 320) {
                    width = width / 2;
                }
                
                 
                carousel.jcarousel('items').css('width', Math.ceil(width) + 'px');
                carousel.jcarousel('items').css('height', Math.ceil(width) + 'px');

                $('.jcarousel-image-container').css('width', Math.ceil(width-60) + 'px');
                $('.jcarousel-image-container').css('height', Math.ceil(width-60) + 'px'); 
                 
                /*
                $('.jcarousel-image-container').css('width', Math.ceil(width-50) + 'px');
                $('.jcarousel-image-container').css('height', Math.ceil(width-50) + 'px');

                $('.jcarousel-inner').css('width', Math.ceil(width-30) + 'px');
                $('.jcarousel-inner').css('height', Math.ceil(width-30) + 'px');  
                */
            })
            .jcarousel({
                wrap: 'circular'
            });

        $('.jcarousel-control-prev')
            .jcarouselControl({
                target: '-=1'
            });

        $('.jcarousel-control-next')
            .jcarouselControl({
                target: '+=1'
            });

        $('.jcarousel-pagination')
            .on('jcarouselpagination:active', 'a', function() {
                $(this).addClass('active');
            })
            .on('jcarouselpagination:inactive', 'a', function() {
                $(this).removeClass('active');
            })
            .on('click', function(e) {
                e.preventDefault();
            })
            .jcarouselPagination({
                perPage: 1,
                item: function(page) {
                    return '<a href="#' + page + '">' + page + '</a>';
                }
            });
    });
})(jQuery);

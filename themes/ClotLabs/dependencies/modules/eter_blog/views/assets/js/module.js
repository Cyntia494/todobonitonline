$(document).ready(function(){
    $(".blogslist").owlCarousel({
        margin:9,
        responsiveClass:true,
        dots: true,
        nav: false,
        loop:true,
        responsive:{
            0:{
                items:1,
            },
            480:{
                items:2,
            },
            769:{
                items:3,
            },
            1200:{
                items:3,
            },
            1480:{
                items:4,
            }
        }
    });    
});
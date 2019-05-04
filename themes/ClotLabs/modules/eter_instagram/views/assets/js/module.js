$(document).ready(function(){
    $(".instagram-images").owlCarousel({
        margin:0,
        responsiveClass:true,
        dots: false,
        nav: true,
        navText: ['<i class="material-icons">&#xE408;</i>','<i class="material-icons">&#xE409;</i>'],
        responsive:{
            0:{
                items:2,
            },
            480:{
                items:3,
            },
            769:{
                items:4,
            },
            1200:{
                items:6,
            }
        }
    });    
});
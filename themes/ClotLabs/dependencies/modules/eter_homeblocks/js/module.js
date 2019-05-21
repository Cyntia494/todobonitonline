$(document).ready(function(){
	$(".blockslider").owlCarousel({
    loop:true,
    margin:20,
    responsiveClass:true,
    dots: false,
    nav: false,
    responsive:{
        0:{
            items:1,
        },
        640:{
            items:2,
        },
        769:{
            items:2,
        },
        1200:{
            items:3,
        }
    }
  });    
});
$(function () {
    $.fn.renderCards = function (options) {
        var settings = $.extend({
            top: 10,
            left: 5,
            height: 200,
            width: 100,
        }, options );
 
    	var top = 0; 
        var left = 0;
        var height = settings.height;
        var width = settings.width;
        var totalcards = this.find(".card").length; 
        var zindex = totalcards; 
        var nextitem = totalcards;
    	var list = this.find(".cardlist"); 
        list.css('height',settings.height+"px");
        list.css('width',settings.width+"px");  

        this.find(".card").each(function() { 
            var card = $(this); 
            
            card.css('top',top+"px"); 
            card.css('height',height+"px"); 
            card.css('width',settings.width+"px");
            card.css('left',left+"px");
            card.css('z-index',zindex);
            card.addClass('card-'+zindex);
            
            
            nextitem -= 1; 
            if(nextitem == 0) {
            	nextitem = totalcards;
            }  
            card.append('<div class="card-buttons"><button class="back">back</button><button class="next" data-next="'+nextitem+'" data-current="'+zindex+'">next</button></div>'); 
						zindex -= 1;
            top += settings.top;
            height -= (settings.top*2); 
            left += settings.left;
        });
        $(".card-buttons .next").on('click',function() {
        	var next = $(this).data('next');  
            var current = $(this).data('current');
            $('.card-'+current).addClass('current'); 
            $('.card-'+current).addClass('next');
            setTimeout(function(){
                $('.card-'+current).insertAfter($('.cardlist li:last-child'));
                $('.card.next').removeClass('next');
                var top = 0; 
                var left = 0;
                var height = settings.height;
                var width = settings.width;
                var zindex = totalcards; 
                var nextitem = totalcards;
                $(".cardlist .card").each(function() {
    	            var card = $(this); 
                    card.css('top',top+"px"); 
                    card.css('height',height+"px"); 
                    card.css('width',settings.width+"px");
                    card.css('left',left+"px");
                    card.css('z-index',zindex);
    				zindex -= 1;
                    top += settings.top;
                    height -= (settings.top*2); 
                    left += settings.left;
                });

            },1000);
            //$('.card-'+current).insertAfter($('.cardlist li:last-child'));
        });
    };
});
$(document).ready(function() {
    $('.cardcontainer').renderCards({
    	left: 5,
        top: 3,
        height: 360,
        width: 230,
    });
})


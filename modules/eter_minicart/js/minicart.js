$(document).ready(function () {
	var showModal = prestashop.blockcart.showModal || function (modal) {
		var $body = $('body');
		$body.append(modal);
		$body.one('click', '#blockcart-modal', function (event) {
		  if (event.target.id === 'blockcart-modal') {
			$(event.target).remove();
		  }
		});
	};

    prestashop.on('updateCart', function (event) {
	    var refreshURL = $('.minicart').data('update');
		$('.minicart').hide();
		var requestData = {};
		if (event && event.reason) {
          	requestData = {
            	id_product_attribute: event.reason.idProductAttribute,
            	id_product: event.reason.idProduct,
            	action: event.reason.linkAction
          	};
		}	
	    $.ajax({
			url: refreshURL,
			data: requestData,
	        success:function(resp) {
			  	$('.minicart').replaceWith(resp.preview);
			  	if(resp.modal){
				  	showModal(resp.modal);
			  	}
	        }
	    });
	});

	$("body").on("click",".minicart .cart",function(e) {
		e.preventDefault();
		$("#_desktop_top_menu").removeClass("showmenu");
		$("body").removeClass("mobilemenu");
		$(".headerlogin").removeClass('show');
		$("#search_widget").removeClass("showform");
		$(this).toggleClass("show");
	});

	$("body").on("click",".remove-from-cart",function(e) {
	    e.preventDefault();
	    var refreshURL = $('.minicart').data('update');
	    $('.minicart').hide();
	    $.ajax({
	      	url: refreshURL,
	      	data:{remove:true,idproduct:$(this).data('id'),idattribute:$(this).data('idattribute')},
	      	success:function(resp) {
	        	$('.minicart').replaceWith(resp.preview);
	      	}
	    });
	});
});

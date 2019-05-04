$(document).ready(function() {
	$('#myTab.nav-tabs > li').each(function() {
		$(this).removeClass("active");
	});
	$('.enviaya-tab').addClass("active");
	$('#shipping').removeClass("active");
	$('#returns').removeClass("active");
	$('.edit_shipping_link').remove();

	$(".request-enviaya-shipment").click(function(e){
		e.preventDefault();
		var url = $(this).attr('href');
		var orderid = $(this).data('id-order');
		$(this).attr('disabled',true);
		$.ajax({
			method: 'POST',
			url: url,
			data:{order:orderid},
			success: function(data){
				if (data.success) {
					location.reload();
				} else {
					alert(data.message);
					$(".request-enviaya-shipment").removeAttr('disabled');
				}
			}
		})
	});
});
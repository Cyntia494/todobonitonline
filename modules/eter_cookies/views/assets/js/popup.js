$(document).ready(function() {
	$('.close-popup').click(function(){
		$('#eter-pop-up').fadeOut('slow');
	});
	$('#eter-pop-up form').submit(function(e) {
		e.preventDefault();
		var url = $(this).attr('action');
		var data = $(this).serializeArray();
		console.log(data);
		$.ajax({
			url: url,
			data:data,
			method: 'POST',
			success: function(response) {
				var response = JSON.parse(response);
				console.log(response);
				if (response.new) {
					$('#eter-pop-up form').html(response.message);
					setTimeout(function(){ $('.close-popup').trigger('click') }, 2000);
				} else {
					$('#eter-pop-up form').append(response.message);
				}
			}
		})
	});
});
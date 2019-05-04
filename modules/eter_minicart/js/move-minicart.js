$(document).ready(function () {
	moveMinicart();
	$(window).resize(function(){
		moveMinicart();
	});
});

function moveMinicart() {
	$("#_desktop_cart").remove();
	if($(window).width() < 767) {
		if ($("#_mobile_cart .minicart").length == 0) {
			$("#_mobile_cart").html('');
			$(".minicart").appendTo("#_mobile_cart");
		}
	} else {
		if ($(".right-nav .minicart").length == 0) {
			$(".minicart").appendTo(".right-nav");
		}
	}
}
$(document).ready(function() {
	moveLogin();
	$(window).resize(function(){
		moveLogin();
	});
});
function moveLogin() {
	if($(window).width() < 767) {
		if ($("#_mobile_user_info .headerlogin").length == 0) {
			$("#_mobile_user_info").html('');
			$(".headerlogin").appendTo("#_mobile_user_info");
		}
	} else {
		if ($(".right-nav .headerlogin").length == 0) {
			$(".headerlogin").appendTo(".right-nav");
		}
	}
}
$(document).ready(function(){

	if($("#errorMSG").text() != ""){
		$("#errorMSG").hide();
		$("#errorMSG").fadeIn(1000);			
		$("#round").vibrate(conf);
		$("#user_name").val("");
		$("#pword").val("");
	}
});
setTimeout('$("#errorMSG").slideUp()',8000);

// JavaScript Document
$(document).ready(function(){
	$('form').submit(function (){
			return false;
	});
	$('#transfer').click(function(){
		var data = $('form').serialize();
		$.post("index.php?ajax=1&takeCalls=1&transfer=1", data, function(data){
			$('#msg').html(data);
			if($("#success").text() != ""){
				$('form').css({'display': 'none'});
			}
		});
	});
	$('#cancel').click(function(){
		self.close();
	});
});
function closeSuccess(){
	opener.childResponse();	
	self.close();
}
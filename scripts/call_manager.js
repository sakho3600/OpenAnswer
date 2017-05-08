// JavaScript Document

function showCall(){
	$('#form').load('index.php?ajax=1&takeCalls=1&getForm=1', function(){
																if($('#script_content').html() != null){
																	$('#script').html($('#script_content').html()).animate({height: "225px"},500);
																	$('.hangup').click(function(){hangUp()});
																	$('.transfer').click(function(){transfer()});
																	$('.hold').click(function(){showHold();});
																	$('form').submit(function (){
																			return false;
																	});
																	$('#save').click(function(){
																		var data = $('form').serialize();
																		$.post('form_processor.php', data, function(data){
																			$('#form').html(data);
																			setTimeout('hangUp()',3000);
																			if($("#success").text() != ""){
																				$('#form').html(''); $('#script').html('');
																			   checkCall = setInterval('showCall()',15000);
																			}
																			successMSG();
																		});
																	});
																}
	});
	if($('#form').html() != ""){
		clearInterval(checkCall);
		hangup = 0;
	}
}
$('#container').css({'padding': '0 20px'});
checkCall = setInterval('showCall()',500);
function resetCheck(){
	clearInterval(checkCall);
	checkCall = setInterval('showCall()',500);
}
var hangup = 2;
function hangUp(){
	if(hangup < 1){
	$('#form').load('index.php?ajax=1&takeCalls=1&hangUp=1', function(){
		   $('#form').html('');
		   $('#script').html('');
		   checkCall = setInterval('resetCheck()',15000);
		   hangup = setTimeout('hangup=2',10000);
		});
	}
}
function submitCallForm(){	
	$('form').submit(function (){
			return false;
	});
	$('#save').click(function(){
		var data = $('form').serialize();
		$.post('form_processor.php', data, function(data){
			$('#form').html(data);
			if($("#success").text() != ""){
				$('#form').html(''); $('#script').html('');
			   checkCall = setInterval('showCall()',15000);
			}
			successMSG();
		});
	});
}
var paused = false;
function pauseAgent(){
	if(paused==false){
		$('#form').load('index.php?ajax=1&takeCalls=1&pause=1', function(){
			   $('#form').html('');
			   $('#script').html('');
			   $('#pauseCalls').text('Resume Calls');
				clearInterval(checkCall);
			   paused = true;
			});
	}
	else{
		$('#form').load('index.php?ajax=1&takeCalls=1&unpause=1', function(){
			   $('#form').html('');
			   $('#script').html('');
			   $('#pauseCalls').text('Pause Calls');
				checkCall = setInterval('showCall()',500);
			   paused = false;
		});
	}
}
function transfer(){
	if($('#form').html() != '' || $('#script').html() != ''){
		var left = (screen.width/2)-(400/2)
		var top = (screen.height/2)-(350/2)
		window.open('index.php?ajax=1&takeCalls=1&transfer=1','Transfer Call','dependent=1, height=350, width=400, resizable=0, status=0,location=0,menubar=0,toolbar=0,directories=0,top='+top+',left='+left);
		return false;
	}
}
function childResponse(){
	$('#form').html('');
	$('#script').html('');
	checkCall = setInterval('resetCheck()',15000);
}
function showHold(){
	if($('#alert').dialog("isOpen")){
		$('#alert').dialog('destroy');
	}
	$('.ui-dialog').remove();
	var id = Math.ceil(Math.random()*100);
	var newBox = $('#alert').clone();
	newBox.attr('id', 'alert'+id);
	$('#form').append(newBox);
	holdCall(1,id);
	$('#alert'+id).css({'display': 'inline'});
	$('#alert'+id).dialog({
		autoOpen: false,
		bgiframe: true,
		resizable: false,
		width:300,
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},
		closeOnEscape: false,
		buttons: {
			'Return to call': function() {
				holdCall(2);
				$('#alert'+id).remove();
				$(this).dialog('destroy');
			},
			'Close': function() {
				$('#alert'+id).remove();
				$(this).dialog('destroy');
			}

		}
	});
	$('#alert'+id).dialog("open");
}
function holdCall(act,id){
	switch (act){
		case 1:
			$('#alert'+id).load('index.php?ajax=1&takeCalls=1&holdCall=1');
		break;
		case 2:
			$('#alert'+id).load('index.php?ajax=1&takeCalls=1&holdCall=2');
		break;
	}
}
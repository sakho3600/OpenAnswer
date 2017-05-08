
// JavaScript Document
//Take Calls Default hot keys.

//Hold Caller ALT+Q

//Transfer Call ALT+W

//Hangup Calls ALT+E

//Record Call ALT+R

//Submit Form Shift+Enter


//Open/Close Script ESC
var isCtrl=false;
var isShift=false;
var isAlt=false;
$(document).keyup(function (e) { 
				if(e.which == 17) isCtrl=false;
				if(e.which == 16) isShift=false;
				if(e.which == 18) isAlt=false;
				return false;
	}).keydown(function (e){
	if(e.which == 17) isCtrl=true;
	if(e.which == 16) isShift=true;
	if(e.which == 18) isAlt=true;
	if(e.which == 27){
		show_script();
		return false;
	}
	if(e.which == 69 && isAlt == true){
		hangUp();
		return false;
	}
	if(e.which == 84 && isAlt == true){
		transfer();
		return false
	}
	/*if(e.which == 72 && isAlt == true){
		hold();
		return false;
	}*/
})
function checkSize(){
	var nW = $(window).width();
	var h = $(window).height();
	if(nW < 1000){
		alert("Your desktop resolution must be at least 1024 x 768 size.\nYour browser will attempt to resize itself automatically.\nIf you feel this is an error you can't change, contact your administrator.");
		top.moveTo(0,0);
		top.resizeTo(1124,768);
	}
}
$(function /*sidebar*/() {
		$(".sideBar").accordion({
			collapsible: true,
			autoHeight: false
		});
});
$(document).ready(
	function () {
		checkSize();
		$('a.closeEl').bind('click', toggleContent);
		sortables();
		$(window).resize(function(){checkSize();});
		$('#script').css('height', '0px');
		var cpg = $.getUrlVar('cpg');
		if(cpg == 2){
			$('#licp').removeClass('currentTab');
			$('#litake_calls').addClass('currentTab');
		}
	}
);
var toggleContent = function(e){
	var targetContent = $('div.widgetContent', this.parentNode.parentNode);
	if (targetContent.css('display') == 'none') {
		targetContent.slideDown(300);
		$(this).html('[-]');
	} else {
		targetContent.slideUp(300);
		$(this).html('[+]');
	}
	return false;
};
function serialize(s){
	serial = $.SortSerialize(s);
	alert(serial.hash);
};
function sortables(){
		$('div.widgetColumn').Sortable(
			{
				accept: 'widget',
				helperclass: 'sortHelper',
				activeclass : 	'sortableactive',
				hoverclass : 	'sortablehover',
				handle: 'div.widgetHeader',
				tolerance: 'pointer',
				onChange : function(ser)
				{
				},
				onStart : function()
				{
					$.iAutoscroller.start(this, document.getElementsByTagName('body'));
				},
				onStop : function()
				{
					$.iAutoscroller.stop();
				}
			}
		);
}

$(function (){
		$('#add_user').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=1', function(){ addForm('1','#view_user_details');});});
		$('#add_role').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=2',function(){ check_all(); addForm('2','#modify_role');});});
		$('#remove_user').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=5&list=1', function (){ $("#userList").tablesorter(); searchBlur('.searchBar','#userListTable',5,'#userList'); } );});
		$('#add_team').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=3',function(){ addForm('3','#modify_team');});});
		$('#view_user_details').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=4&list=1', function (){ $("#userList").tablesorter(); searchBlur('.searchBar','#userListTable',4,'#userList'); });});
		$('#modify_user').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=6&list=1', function (){ $("#userList").tablesorter(); searchBlur('.searchBar','#userListTable',6,'#userList'); });});
		$('#delete_user').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=7&list=1', function (){ $("#userList").tablesorter(); searchBlur('.searchBar','#userListTable',7,'#userList'); });});
		$('#delete_role').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=8&list=1', function (){ $("#roleList").tablesorter(); searchBlur('.searchBar','#roleListTable',8,'#roleList'); });});
		$('#modify_role').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=9&list=1', function (){ $("#roleList").tablesorter(); searchBlur('.searchBar','#roleListTable',9,'#roleList'); });});
		$('#delete_team').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=11&list=1', function (){ $("#teamList").tablesorter(); searchBlur('.searchBar','#teamListTable',11,'#teamList'); });});
		$('#remove_team').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=12&list=1', function (){ $("#teamList").tablesorter(); searchBlur('.searchBar','#teamListTable',12,'#teamList'); });});
		$('#modify_team').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=13&list=1', function (){ $("#teamList").tablesorter(); searchBlur('.searchBar','#teamListTable',13,'#teamList'); });});
		$('#add_client').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=15', function(){ addClient(); });});
		$('#modify_client').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=16&list=1', function (){ $("#clientList").tablesorter(); searchBlur('.searchBar','#clientListTable',16,'#clientList'); });});
		$('#delete_client').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=17&list=1', function (){ $("#clientList").tablesorter(); searchBlur('.searchBar','#clientListTable',17,'#clientList'); });});
		$('#view_client_details').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=18&list=1', function (){ $("#clientList").tablesorter(); searchBlur('.searchBar','#clientListTable',18,'#clientList'); });});
		$('#add_client_form').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=19', function(){ addForm('19','#update_client_form');});});
		$('#delete_client_form').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=21&list=1', function (){ $("#formList").tablesorter(); searchBlur('.searchBar','#formListTable',21,'#formList'); });});
		$('#remove_client_form').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=22&list=1', function (){ $("#formList").tablesorter(); searchBlur('.searchBar','#formListTable',22,'#formList'); });});
		$('#update_client_form').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=23&list=1', function (){ $("#formList").tablesorter(); searchBlur('.searchBar','#formListTable',23,'#formList'); });});
		$('#add_queue').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=24', function(){ addForm('24','#modify_queue');});});
		$('#delete_queue').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=25&list=1', function (){ $("#queueList").tablesorter(); searchBlur('.searchBar','#queueListTable',25,'#queueList'); });});
		$('#modify_queue').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=26&list=1', function (){ $("#queueList").tablesorter(); searchBlur('.searchBar','#queueListTable',26,'#queueList'); });});
		$('#play_record').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=29&list=1', function (){ $("#recordList").tablesorter();});});
		$('#all_live_calls').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=30&list=1', function (){ $("#liveCallList").tablesorter();});});
		$('#personal_settings').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=31', function(){addForm('31','#home')});});
		$('#assign_role').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=32', function (){ addFormSelect('32','#modify_role'); list_move();  });});
		$('#assign_team').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=33', function (){ addFormSelect('33','#modify_team'); list_move(); });});
		$('#assign_queue').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=34', function (){ addFormSelect('34','#modify_queue'); list_move(); });});
		$('#all_cdr').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=36&list=1', function (){ $("#liveCallList").tablesorter();});});
		$('#own_cdr').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=36.1&list=1&prsn=1', function (){ $("#liveCallList").tablesorter();});});
		$('#home').click(function(){$('.mainPage').load('index.php?ajax=1&ajxPG=37', function(){sortables();});});
		$('#assign_ext').click(function(){ $('.mainPage').load('index.php?ajax=1&ajxPG=38') });
		$('#assign_queue_form').click(function(){ alert('Hi'); });
});
function list_move(){
	$(function(){
		$("#add").click(function() {
			$("#list1 > option:selected").appendTo("#list2");
		});
		$("#add_all").click(function() {
			$("#list1 > option").appendTo("#list2");
		});
		$("#remove").click(function() {
			$("#list2 > option:selected").appendTo("#list1");
		});
		$("#remove_all").click(function() {
			$("#list2 > option").appendTo("#list1");
		});
	});
}

function check_all(){
	$(function (){
		$("#checkAll").click(function(){
			$("#permTable").find("input[type$='checkbox']").each(function(){
					if(this.checked){
						this.checked = false;
					}
					else{
						this.checked = true;
						$('#checkAll').val("Inverse Selection");
					}
				});
		});
	});
}

function viewDetails(uid){
	$(function (){
		$('.mainPage').load('index.php?ajax=1&ajxPG=4&userID='+uid);
	});
};
function viewRoleDetails(rid){
	$(function (){
		$('.mainPage').load('index.php?ajax=1&ajxPG=10&roleID='+rid);
	});
};
function viewTeamDetails(tid){
	$(function (){
		$('.mainPage').load('index.php?ajax=1&ajxPG=14&teamID='+tid);
	});
};
function viewClientDetails(cid){
	$(function (){
		$('.mainPage').load('index.php?ajax=1&ajxPG=18&clientID='+cid);
	});
};
function viewFormDetails(fid){
	$(function (){
		$('.mainPage').load('index.php?ajax=1&ajxPG=20&formID='+fid);
	});
};
function viewQueueDetails(qid){
	$(function (){
		$('.mainPage').load('index.php?ajax=1&ajxPG=28&queueID='+qid);
	});
};
function viewRecording(rid){
	$(function (){
		$('.mainPage').load('index.php?ajax=1&ajxPG=29&recordID='+rid+'&details=1');
	});
};
var timeout = undefined;
function searchBlur(att,divID,pg,table){
	clearSearch(att);
	$(function (){
			$(att).focus(function(){clearSearch(att);}).blur(function(){clearSearch(att);});
			$(att).keyup(function(){
				if(timeout != undefined){
					clearTimeout(timeout);
				}
				timeout = setTimeout(function(){doSearch(att,divID,pg,table);},500);
			});
	});
	$('#reset').click( function(){
			cancelSearch(att,divID,pg,table);
	});
}
function doSearch(att,divID,pg,table){
	$.get("index.php",{ajax: 1, ajxPG: pg, list: 1, q: $(att).val()}, 
		function(data){
			$(divID).html(data);
			$(table).tablesorter();
		}
	);
}
function cancelSearch(att, divID,pg,table){
	$(function(){
		$.get("index.php",{ajax: 1, ajxPG: pg, list: 1, q:''},
			function(data){
				$(divID).html(data);
				//$(divID).slideDown('500');
				$(table).tablesorter();
			}
		)
		$(att).val(''); 
		clearSearch(att);
	})
}
function clearSearch(att){
	if($(att).val()=="Search..."){
		$(att).val('');
		if($('.search_wrapper').hasClass('empty')){
			$('.search_wrapper').removeClass('empty');
		}
	}
	else{
		if($(att).val()==''){
			$(att).val('Search...');
			if(!$('.search_wrapper').hasClass('empty')){
				$('.search_wrapper').addClass('empty');
			}
		}
	}
}

function editUser(uid){
	$('.mainPage').load('index.php?ajax=1&ajxPG=6&edt=1&userID='+uid, function(){ modifyForm('6','userID='+uid,'#view_user_details');});
}
function editRole(rid){
	$('.mainPage').load('index.php?ajax=1&ajxPG=9&edt=1&roleID='+rid, function(){check_all(); modifyForm('9','roleID='+rid,'#modify_role')});
}
function editTeam(tid){
	$('.mainPage').load('index.php?ajax=1&ajxPG=13&edt=1&teamID='+tid,function(){modifyForm('13','teamID='+tid,'#modify_team');});
}
function editClient(cid){
	$('.mainPage').load('index.php?ajax=1&ajxPG=16&edt=1&clientID='+cid,function(){modifyClient(cid);});
}
function editForm(fid){
	$('.mainPage').load('index.php?ajax=1&ajxPG=23&edt=1&formID='+fid, function(){modifyForm('23','formID='+fid,'#update_client_form');});
}
function editQueue(qid){
	$('.mainPage').load('index.php?ajax=1&ajxPG=26&edt=1&queueID='+qid, function(){modifyForm('26','queueID='+qid,'#modify_queue');});
}
function removeUser(uid, name){
	$('#uName').html(name);
	showConfirm('actualRemoveUser(\''+uid+'\',\''+name+'\')')
}
function actualRemoveUser(uid, name){
	$('.mainPage').load('index.php?ajax=1&ajxPG=5&rmv=1&userID='+uid, function (){ $("#userList").tablesorter(); searchBlur('.searchBar','#userListTable',5,'#userList'); successMSG();} );
}
function removeTeam(tid, name){
	$('#tName').html(name);
	showConfirm('actualRemoveTeam(\''+tid+'\',\''+name+'\')')
}
function actualRemoveTeam(tid, name){
	$('.mainPage').load('index.php?ajax=1&ajxPG=12&rmv=1&teamID='+tid, function (){ $("#teamList").tablesorter(); searchBlur('.searchBar','#teamListTable',12,'#teamList'); successMSG();} );
}
function removeForm(fid, name){
	$('#fName').html(name);
	showConfirm('actualRemoveForm(\''+fid+'\',\''+name+'\')')
}
function actualRemoveForm(fid, name){
	$('.mainPage').load('index.php?ajax=1&ajxPG=22&rmv=1&formID='+fid, function (){ $("#formList").tablesorter(); searchBlur('.searchBar','#formListTable',22,'#formList'); successMSG();} );
}
function deleteUser(uid, name){
	$('#uName').html(name);
	showConfirm('actualDeleteUser(\''+uid+'\',\''+name+'\')')
}
function actualDeleteUser(uid, name){
	$('.mainPage').load('index.php?ajax=1&ajxPG=7&dlt=1&userID='+uid, function (){ $("#userList").tablesorter(); searchBlur('.searchBar','#userListTable',7,'#userList'); successMSG();} );
}
function deleteRole(rid, title){
	$('#rName').html(title);
	showConfirm('actualDeleteRole(\''+rid+'\',\''+title+'\')')
}
function actualDeleteRole(rid, title){
	$('.mainPage').load('index.php?ajax=1&ajxPG=8&dlt=1&roleID='+rid, function (){ $("#roleList").tablesorter(); searchBlur('.searchBar','#userListTable',8,'#userList'); successMSG();} );
}
function deleteTeam(tid, name){
	$('#tName').html(name);
	showConfirm('actualDeleteTeam(\''+tid+'\',\''+name+'\')')
}
function actualDeleteTeam(tid, name){
	$('.mainPage').load('index.php?ajax=1&ajxPG=11&dlt=1&teamID='+tid, function (){ $("#teamList").tablesorter(); searchBlur('.searchBar','#teamListTable',11,'#teamList'); successMSG();} );
}
function deleteClient(cid, name){
	$('#cName').html(name);
	showConfirm('actualDeleteClient(\''+cid+'\',\''+name+'\')')
}
function actualDeleteClient(cid, name){
	$('.mainPage').load('index.php?ajax=1&ajxPG=17&dlt=1&clientID='+cid, function (){ $("#clientList").tablesorter(); searchBlur('.searchBar','#clientListTable',17,'#clientList'); successMSG();} );
}
function deleteClientExt(extid, name, clientID){
	$('#cExt').html(name);
	showConfirm('actualDeleteClientExt(\''+extid+'\',\''+clientID+'\')')
}
function actualDeleteClientExt(extid, clientID){
	$('#ext_result').load('index.php?ajax=1&ajxPG=38&dlt=1&clientID='+clientID+'&extID='+extid, function (){ $("#extList").tablesorter(); successMSG();} );
}
function deleteForm(fid, name){
	$('#fName').html(name);
	showConfirm('actualDeleteForm(\''+fid+'\',\''+name+'\')')
}
function actualDeleteForm(fid, name){
	$('.mainPage').load('index.php?ajax=1&ajxPG=21&dlt=1&formID='+fid, function (){ $("#formList").tablesorter(); searchBlur('.searchBar','#formListTable',21,'#formList'); successMSG();} );
}
function deleteQueue(qid, name){
	$('#qName').html(name);
	showConfirm('actualDeleteQueue(\''+qid+'\',\''+name+'\')')
}
function actualDeleteQueue(qid){
	$('.mainPage').load('index.php?ajax=1&ajxPG=25&dlt=1&queueID='+qid, function (){ $("#queueList").tablesorter(); searchBlur('.searchBar','#queueListTable',25,'#queueList'); successMSG();} );
}
function deleteRecording(rid, name){
	$('#rName').html(name);
	showConfirm('actualDeleteRec(\''+rid+'\',\''+name+'\')')
}
function actualDeleteRec(rid){
	$('.mainPage').load('index.php?ajax=1&ajxPG=29&dlt=1&recordID='+rid, function (){ $("#recordList").tablesorter(); successMSG();} );
}
function successMSG(){
	clearTimeout();
	if($("#successMSG").text() != ""){
		$("#successMSG").slideDown(500);		
	}
	setTimeout('$("#successMSG").slideUp()',5000);
	if($("#msg").text() != ""){
		
		$("#msg").slideDown(500);		
	}
	setTimeout('$("#msg").slideUp()',15000);
}
function showConfirm(code){
	if($('#confirm').dialog("isOpen")){
		$('#confirm').dialog('destroy');
	}
	$('.ui-dialog').remove();
	var id = Math.ceil(Math.random()*100);
	var newBox = $('#confirm').clone();
	newBox.attr('id', 'confirm'+id);
	$('#mainPage').append(newBox);
	$('#confirm'+id).css({'display': 'inline'});
	$('#confirm'+id).dialog({
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
			'Yes': function() {
				eval(code);
				$(this).dialog('destroy');
				$(this).remove();
			},
			'No': function() {
				$('#uName').html('');
				$(this).dialog('destroy');
				$(this).remove();
			}
		}
	});
	$('#confirm'+id).dialog("open");
}
function showAlert(code){
	if($('#alert').dialog("isOpen")){
		$('#alert').dialog('destroy');
	}
	$('#alert').css({'display': 'inline'});
	$('#alert').dialog({
		autoOpen: false,
		bgiframe: true,
		resizable: false,
		width:300,
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},
		closeOnEscape: true,
		buttons: {
			'OK': function() {
				$(this).dialog('destroy');
			}
		}
	});
	$('#alert').dialog("open");
}
function addClient(){	
	$('#phy_mail_same').click(function(){
        if ($("#phy_mail_same").is(":checked")) {
			$('#mail_address').val($('#phy_address').val());
			$('#mail_city').val($('#phy_city').val());
			$('#mail_zip').val($('#phy_zip').val());			
            var phy_state = $("#phy_state").val();
            $("#mail_state").selectOptions(phy_state);                    
        } else {
			$('#mail_address').val('');
			$('#mail_city').val('');
			$('#mail_zip').val('');
            $("#mail_state").selectOptions("");
        }
    });
	
	$('form').submit(function (){
			return false;
	});
	
	$('#save').click(function(){
		var data = $('form').serialize();
		$.post("index.php?ajax=1&ajxPG=15", data, function(data){
			$('#msg').html(data);
			if($("#success").text() != ""){
				$('form').slideUp('slow');
				$('form').css({'display': 'none'});
			}
			successMSG();
		});
	});
	
	$('#cancel').click(function(){
		$('#view_client_details').click();
	});
}
function modifyClient(cid){	
	$('#phy_mail_same').click(function(){
        if ($("#phy_mail_same").is(":checked")) {
			$('#mail_address').val($('#phy_address').val());
			$('#mail_city').val($('#phy_city').val());
			$('#mail_zip').val($('#phy_zip').val());			
            var phy_state = $("#phy_state").val();
            $("#mail_state").selectOptions(phy_state);                    
        } else {
			$('#mail_address').val('');
			$('#mail_city').val('');
			$('#mail_zip').val('');
            $("#mail_state").selectOptions("");
        }
    });
	
	$('form').submit(function (){
			return false;
	});
	
	$('#save').click(function(){
		var data = $('form').serialize();
		$.post("index.php?ajax=1&ajxPG=16&clientID="+cid, data, function(data){
			$('#msg').html(data);
			if($("#success").text() != ""){
				$('form').css({'display': 'none'});
			}
			successMSG();
		});
	});
	$('#cancel').click(function(){
		$('#view_client_details').click();
	});
}
function addForm(pg,callback){	
	$('form').submit(function (){
			return false;
	});
	
	$('#save').click(function(){
		var data = $('form').serialize();
		$.post("index.php?ajax=1&ajxPG="+pg, data, function(data){
			$('#msg').html(data);
			if($("#success").text() != ""){
				$('form').css({'display': 'none'});
			}
			successMSG();
		});
	});
	$('#cancel').click(function(){
		$(callback).click();
	});
}
function modifyForm(pg,id,callback){	
	$('form').submit(function (){
			return false;
	});
	
	$('#save').click(function(){
		var data = $('form').serialize();
		$.post("index.php?ajax=1&ajxPG="+pg+"&"+id, data, function(data){
			$('#msg').html(data);
			if($("#success").text() != ""){
				$('form').css({'display': 'none'});
			}
			successMSG();
		});
	});
	$('#cancel').click(function(){
		$(callback).click();
	});
}
function getRoleUsers (id) {	
	$('#list1').load('index.php?ajax=1&ajxPG=32&optgrp=1&unassgn=1&roleID='+id);
	$('#list2').load('index.php?ajax=1&ajxPG=32&optgrp=1&assgn=1&roleID='+id);
}
function getTeamMembers (id) {	
	$('#list1').load('index.php?ajax=1&ajxPG=33&optgrp=1&unassgn=1&teamID='+id);
	$('#list2').load('index.php?ajax=1&ajxPG=33&optgrp=1&assgn=1&teamID='+id);
}
function getQueueMembers (id) {	
	$('#list1').load('index.php?ajax=1&ajxPG=34&optgrp=1&unassgn=1&queueID='+id);
	$('#list2').load('index.php?ajax=1&ajxPG=34&optgrp=1&assgn=1&queueID='+id);
}
function addFormSelect(pg,callback){	
	$('form').submit(function (){
			return false;
	});
	
	$('#save').click(function(){
		$("#list2").each(function(){
				$("#list2>option[selected!=true]").attr('selected', 'selected');
		});
		var data = $('form').serialize();
		$.post("index.php?ajax=1&ajxPG="+pg, data, function(data){
			$('#msg').html(data);
			if($("#success").text() != ""){
				$('form').css({'display': 'none'});
			}
			successMSG();
		});
	});
	$('#cancel').click(function(){
		$(callback).click();
	});
}
function show_script(){
	if($('#script').html() != ""){
		if($("#script").height()<1){
			$("#script").animate({height: "225px"},500);
		}
		else{
			$("#script").animate({height: "0px"},500);
		}
	}
}
function get_assigned_numbers(cid){
	$('#ext_result').load('index.php?ajax=1&ajxPG=38&list=1&clientID='+cid, function(){ $('#extList').tablesorter();});
}
function add_new_ext(cid){
	$('#ext_result').load('index.php?ajax=1&ajxPG=38&form&clientID='+cid, function(){ addForm('38&clientID='+cid,'#assign_ext');});
}
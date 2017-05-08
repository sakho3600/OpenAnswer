<?php
if(isset($_GET['clientID']) && is_numeric($_GET['clientID']) && $_GET['clientID'] != 0){
	$obj = new client();
	$info = $obj->clientInfoAll($_GET['clientID'], 11);
	$extinfo = display::get_client_extensions('',$info['id']);
	if(!is_array($info)){
		if(preg_match("/0x1/i", $info)){
			echo display::error_msg($info); exit();
		}
		else{
			echo display::error_msg("Unknown Error Occured. Please contact your administrator and reference error UD:10"); exit();
		}
	}
	$info = sanitize_data::desanitize_array($info);
	if(isset($_GET['list']) && $_GET['list']==1){
		echo display::build_confirm_box("Are you sure you want to <span style=\"font-weight:bold;color:red\">PERMANENTLY DELETE</span> the client extension <span id=\"cExt\"></span>?<br /><span style=\"font-weight:bold;\">THIS ACTION CANNOT BE UN-DONE!</span>", "Confirm Deletion");
		echo display::get_client_extensions('table',$info['id']);
		echo "<a href=\"#\" onclick=\"add_new_ext($info[id])\">Add New Extension</a>";
		exit();
	}
	if(isset($_POST['add_client_ext']) && $_POST['add_client_ext'] == 1){
		$errors = array();
		$validation = new validation();
		$rules = array();
		$rules[] = "required,extension,The extension to assign is required.";
		$errors = $validation->validateFields($_POST, $rules);
		if(!empty($errors)){
			$html = array();
			$html[] = "Please correct the following errors:";
			$html[] = "<ul>";
			foreach($errors as $error){
				$html[] = "<li>$error</li>";
			}
			$html[] = "</ul>";
			echo display::error_msg(implode("\n",$html),"L");
			exit();
		}
		else{
			$do = new action();
			$chance = $do->add_ext($_POST);
			if($chance === TRUE){
				echo "<div id=\"success\">".display::success_msg("Client Extension Added Successfully.")."</div>";
				exit();
			}
			else{
				echo display::error_msg($chance);
				exit();
			}
		}
	}
	if(isset($_GET['dlt']) && $_GET['dlt'] == 1 && isset($_GET['extID']) && is_numeric($_GET['extID'])){
			$do = new action();
			$action = $do->delete_ext($info['id'], $_GET['extID']);
			if(!preg_match("/0x1/i",$action) && preg_match("/1x1/i", $action)){
				echo display::success_msg(str_replace("1x1","",$action)); 
				echo display::build_confirm_box("Are you sure you want to <span style=\"font-weight:bold;color:red\">PERMANENTLY DELETE</span> the client extension <span id=\"cExt\"></span>?<br /><span style=\"font-weight:bold;\">THIS ACTION CANNOT BE UN-DONE!</span>", "Confirm Deletion");
				echo display::get_client_extensions('table',$info['id']);
				echo "<a href=\"#\" onclick=\"add_new_ext($info[id])\">Add New Extension</a>";
				exit();
			}
			else{
				echo display::error_msg($action); exit();
			}
	}
}
if(isset($_GET['clientID']) && ($_GET['clientID'] == "" || $_GET['clientID'] == 0)){
	echo display::error_msg("Invalid client selected");exit();
}

if(!isset($_GET['clientID'])){?>
<select name="client" id="client" onchange="get_assigned_numbers(this.value)"><?= display::get_clients('option'); ?></select>
<div id="ext_result">
<?php }
if(isset($_GET['form'])){
	if(isset($_GET['edit'])){ $client_ext = $extinfo['0']['extension']; }else{ $client_ext = '';}
?><div id="msg"></div>
<form id="add_user" name="add_user" method="post" action="#">
  <fieldset class="formContainer">
    <legend><span style="font-weight:bold;">Add Client Extension</span>    </legend>
    <table width="510" border="0" cellpadding="0" cellspacing="0">
      <tr class="sample">
        <td><label for="client">Company Name</label></td>
        <td colspan="2"><?=$info['company_name']?>
          <input name="clientID" type="hidden" id="clientID" value="<?=$info['id']?>" /></td>
      </tr>
      <tr class="sample">
        <td><label for="extension">Extension</label></td>
        <td colspan="2"><input name="extension" type="text" id="extension" /></td>
      </tr>
      <tr>
        <td colspan="3"><input name="add_client_ext" type="hidden" id="add_client_ext" value="1" /></td>
      </tr>
      <tr>
        <td width="225">&nbsp;</td>
        <td width="70"><input class="buttons" type="button" name="save" id="save" value="  Save  " /></td>
        <td width="215"><input class="buttons" type="button" name="cancel" id="cancel" value="Cancel" /></td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </fieldset>
</form>
<?php exit(); } ?>
</div>
<p />
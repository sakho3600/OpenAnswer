<?php
if(isset($_GET['formID']) && is_numeric($_GET['formID']) && $_GET['formID'] != 0){
	$info = display::get_client_forms('','','','','',$_GET['formID']);
	$client = display::get_clients('','','','',$info['0']['client_id']);
	$client = stripslashes($client['0']['company_name']);
	if(!is_array($info)){
		if(preg_match("/0x1/i", $info)){
			echo display::error_msg($info); exit();
		}
		else{
			echo display::error_msg("Unknown Error Occured. Please contact your administrator and reference error UD:10"); exit();
		}
	}
	if(isset($_POST['modify_form']) && $_POST['modify_form'] == 1){
		$errors = array();
		$validation = new validation();
		$rules = array();
		$rules[] = "required,client,A client is required";
		$rules[] = "required,form_name,The form name is required";
		$rules[] = "required,client_form_txt,The form text is required";
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
			($_POST['active']==1?$_POST['active']=0:$_POST['active'] = 1);
			$chance = $do->modify_form($_POST, $info['0']['id']);
			if($chance === TRUE){
				echo "<div id=\"success\">".display::success_msg("Form Modified Successfully.")."</div>";
				exit();
			}
			else{
				echo display::error_msg($chance);
				exit();
			}
		}
	}
}
elseif(isset($_GET['list']) && $_GET['list']==1 && !isset($_GET['q'])){
	echo "<div style=\"float:right;\">".display::get_search('forms')."</div><br /><br />";
	echo display::get_client_forms('table',1);
	exit();
}
elseif(isset($_GET['list']) && $_GET['list']==1 && isset($_GET['q'])){
	 echo display::get_client_forms('table','1','','',$_GET['q']); 
	 exit();
}

else{
	echo display::error_msg("No form specified. <br />Please select another user and try again."); exit();
}
?><div id="msg"></div>
<form id="add_user" name="add_user" method="post" action="#">
  <fieldset class="formContainer">
    <legend><span style="font-weight:bold;">Update Client Form</span>    </legend>
    <table width="510" border="0" cellpadding="0" cellspacing="0">
      <tr class="sample">
        <td><label for="client">Company Name</label></td>
        <td colspan="2"><select name="client" id="client"><option selected="selected" value="<?=$info['0']['client_id']?>"><?=$client?></option><?= display::get_clients('option','','','','',1,1); ?></select></td>
      </tr>
      <tr class="sample">
        <td><label for="form_name">Form Name</label></td>
        <td colspan="2"><input name="form_name" type="text" id="form_name" value="<?=$info['0']['common_name']?>"/></td>
      </tr>
      <tr class="sample">
        <td><label for="client_form_txt">Form Text:</label></td>
        <td colspan="2">
        <label>Disable:
          <input name="active" type="checkbox" id="active" value="1" <? echo ($info['0']['active']==0?"checked=\"checked\"":"");?>/>
        </label></td>
      </tr>
      <tr class="sample">
        <td colspan="3"></label>
        <textarea name="client_form_txt" id="client_form_txt" cols="75" rows="5"><?=$info['0']['form']?></textarea></td>
      </tr>
      <tr>
        <td colspan="3"><input name="modify_form" type="hidden" id="modify_form" value="1" /></td>
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
<p />
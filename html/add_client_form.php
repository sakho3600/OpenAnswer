<?php
if(isset($_POST['add_form']) && $_POST['add_form'] == 1){
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
		$chance = $do->add_form($_POST);
		if($chance === TRUE){
			echo "<div id=\"success\">".display::success_msg("Form Added Successfully.")."</div>";
			exit();
		}
		else{
			echo display::error_msg($chance);
			exit();
		}
	}
}
?><div id="msg"></div>
<form id="add_user" name="add_user" method="post" action="#">
  <fieldset class="formContainer">
    <legend><span style="font-weight:bold;">Add Client Form</span>    </legend>
    <table width="510" border="0" cellpadding="0" cellspacing="0">
      <tr class="sample">
        <td><label for="client">Company Name</label></td>
        <td colspan="2"><select name="client" id="client"><?= display::get_clients('option'); ?></select></td>
      </tr>
      <tr class="sample">
        <td><label for="form_name">Form Name</label></td>
        <td colspan="2"><input name="form_name" type="text" id="form_name" /></td>
      </tr>
      <tr class="sample">
        <td><label for="client_form_txt">Form Text:</label></td>
        <td colspan="2">
        <label>Disable:
          <input name="active" type="checkbox" id="active" value="1" />
        </label></td>
      </tr>
      <tr class="sample">
        <td colspan="3"></label>
        <textarea name="client_form_txt" id="client_form_txt" cols="75" rows="5"></textarea></td>
      </tr>
      <tr>
        <td colspan="3"><input name="add_form" type="hidden" id="add_form" value="1" /></td>
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
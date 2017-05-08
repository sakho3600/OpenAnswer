<?php
if(isset($_GET['formID']) && is_numeric($_GET['formID']) && $_GET['formID'] != 0){
	$info = display::get_client_forms('','','','','',$_GET['formID']);
	if(!is_array($info)){
		if(preg_match("/0x1/i", $info)){
			echo display::error_msg($info); exit();
		}
		else{
			echo display::error_msg("Unknown Error Occured. Please contact your administrator and reference error UD:10"); exit();
		}
	}
	$client = display::get_clients('','','','',$info['0']['client_id']);
	$client = stripslashes($client['0']['company_name']);
	$info = sanitize_data::desanitize_array($info);
}
elseif(isset($_GET['list']) && $_GET['list']==1 && !isset($_GET['q'])){
	echo "<div style=\"float:right;\">".display::get_search('forms')."</div><br /><br />";
	echo display::get_client_forms('table');
	exit();
}
elseif(isset($_GET['list']) && $_GET['list']==1 && isset($_GET['q'])){
	 echo display::get_client_forms('table','','','',$_GET['q']); 
	 exit();
}

else{
	echo display::error_msg("No form specified. <br />Please select another form and try again."); exit();
}
?>
  <fieldset class="formContainer">
    <legend><span style="font-weight:bold;"> Client Form Details</span></legend>
    <span style="float:right; font-weight:bold;" class="links"><a href="#" id="editForm"  onclick="editForm('<?=$info['0']['id']?>')">Edit</a></span><br />
    <table width="510" border="0" cellpadding="0" cellspacing="0">
      <tr class="sample">
        <td width="225"><label for="client">Client</label></td>
        <td width="285"><?=$client?></td>
      </tr>
      <tr class="sample">
        <td><label for="form_name">Form Name</label></td>
        <td><?=$info['0']['common_name']?></td>
      </tr>
      <tr class="sample">
        <td><label for="client_form_txt">Form Text:</label></td>
        <td>
        <label>Disable:
          <input type="checkbox" name="active" id="active" disabled="disabled" <? echo ($info['0']['active']==0?"checked=\"checked\"":"");?>/>
        </label></td>
      </tr>
      <tr class="sample">
        <td colspan="2"></label>
        <textarea name="client_form_txt" cols="75" rows="5" disabled="disabled" id="client_form_txt"><?=$info['0']['form']?></textarea></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </fieldset>

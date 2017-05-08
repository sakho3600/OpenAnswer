<?php
if(isset($_POST['add_queue']) && $_POST['add_queue'] == 1){
	$errors = array();
	$validation = new validation();
	$rules = array();
	$rules[] = "required,queue_name,The Queue name is required and must be identical to the queue name in queues.conf.";
	$rules[] = "required,queue_ext,The Queue extension is required.";
	(!empty($_POST['sla']) ? $rules[] = "digits_only,sla,The SLA must be numeric.":NULL);
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
		$chance = $do->add_queue($_POST);
		if($chance === TRUE){
			echo "<div id=\"success\">".display::success_msg("Queue Added Successfully.")."</div>";
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
    <legend><span style="font-weight:bold;">Add Queue</span>    </legend>
    <table width="510" border="0" cellpadding="0" cellspacing="0">
      <tr class="sample">
        <td><label for="queue_name">Queue Name:</label></td>
        <td colspan="2">
        <input type="text" name="queue_name" id="queue_name" /></td>
      </tr>
      <tr class="sample">
        <td><label for="queue_ext">Queue Extension:</label></td>
        <td colspan="2"><input name="queue_ext" type="text" id="queue_ext" /></td>
      </tr>
      <tr class="sample">
        <td><label for="strategy">Strategy:</label></td>
        <td colspan="2">
          <select name="strategy" id="strategy">
            <option value="ringall">Ring All</option>
            <option value="roundrobin">Round Robin</option>
            <option value="leastrecent">Least Recent</option>
            <option value="fewestcalls">Fewest Calls</option>
            <option value="random">Random</option>
            <option value="rrmemory">RR Memory</option>
        </select></td>
      </tr>
      <tr class="sample">
        <td colspan="3"></td>
      </tr>
      <tr>
        <td><label for="sla">SLA</label></td>
        <td colspan="2">
        <input type="text" name="sla" id="sla" /></td>
      </tr>
      <tr>
        <td width="225"><input name="add_queue" type="hidden" id="add_queue" value="1" /></td>
        <td width="70"><input class="buttons" type="button" name="save" id="save" value="  Save  " /></td>
        <td width="215"><input class="buttons" type="button" name="cancel" id="cancel" value="Cancel" /></td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </fieldset>
</form>
<p />
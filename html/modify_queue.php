<?php
if(isset($_GET['queueID']) && is_numeric($_GET['queueID']) && $_GET['queueID'] != 0){
	$info = display::get_queues('','','','','',$_GET['queueID']);
	if(!is_array($info)){
		if(preg_match("/0x1/i", $info)){
			echo display::error_msg($info); exit();
		}
		else{
			echo display::error_msg("Unknown Error Occured. Please contact your administrator and reference error RD:10"); exit();
		}
	}
	if(isset($_POST['modify_queue']) && $_POST['modify_queue'] == 1){
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
			$chance = $do->modify_queue($_POST, $info['0']['id']);
			if($chance === TRUE){
				echo "<div id=\"success\">".display::success_msg("Queue Modified Successfully.")."</div>";
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
	echo "<div style=\"float:right;\">".display::get_search('queues')."</div><br /><br />";
	echo display::get_queues('table','',1);
	exit();
}
elseif(isset($_GET['list']) && $_GET['list']==1 && isset($_GET['q'])){
	 echo display::get_queues('table','','1','',$_GET['q']); 
	 exit();
}

else{
	echo display::error_msg("No queue specified. <br />Please select another queue and try again."); exit();
}
?><div id="msg"></div>
<form id="add_user" name="add_user" method="post" action="#">
  <fieldset class="formContainer">
    <legend><span style="font-weight:bold;">Modify Queue</span>    </legend>
    <table width="510" border="0" cellpadding="0" cellspacing="0">
      <tr class="sample">
        <td><label for="queue_name">Queue Name:</label></td>
        <td colspan="2">
        <input name="queue_name" type="text" id="queue_name" value="<?=$info['0']['queue_name']?>" /></td>
      </tr>
      <tr class="sample">
        <td><label for="queue_ext">Queue Extension:</label></td>
        <td colspan="2"><input name="queue_ext" type="text" id="queue_ext" value="<?=$info['0']['queue_ext']?>" /></td>
      </tr>
      <tr class="sample">
        <td><label for="strategy">Strategy:</label></td>
        <td colspan="2">
          <select name="strategy" id="strategy">
            <option selected="selected"><?=$info['0']['strategy']?></option>
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
        <input name="sla" type="text" id="sla" value="<?=$info['0']['sla']?>" /></td>
      </tr>
      <tr>
        <td width="225"><input name="modify_queue" type="hidden" id="modify_queue" value="1" /></td>
        <td width="70"><input class="buttons" type="button" name="save" id="save" value="  Save  " /></td>
        <td width="215"><input class="buttons" type="button" name="cancel" id="cancel" value="Cancel" /></td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </fieldset>
</form>
<p />
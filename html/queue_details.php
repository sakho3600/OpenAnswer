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
?>
<form id="add_user" name="add_user" method="post" action="#">
  <fieldset class="formContainer">
    <legend><span style="font-weight:bold;">Queue Details</span></legend>
    <span style="float:right; font-weight:bold;" class="links"><a href="#" id="editQueue"  onclick="editQueue('<?=$info['0']['id']?>')">Edit</a></span><br />
    <table width="510" border="0" cellpadding="10" cellspacing="0">
      <tr class="sample">
        <td width="225"><label for="queue_name"><strong>Queue Name:</strong></label></td>
        <td width="285"><?=$info['0']['queue_name']?></td>
      </tr>
      <tr class="sample">
        <td><label for="queue_ext"><strong>Queue Extension:</strong></label></td>
        <td><?=$info['0']['queue_ext']?></td>
      </tr>
      <tr class="sample">
        <td><label for="strategy"><strong>Strategy:</strong></label></td>
        <td><?=$info['0']['strategy']?></td>
      </tr>
      <tr class="sample">
        <td colspan="2"></td>
      </tr>
      <tr>
        <td><label for="sla"><strong>SLA</strong></label></td>
        <td><?=$info['0']['sla']?></td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </fieldset>
</form>
<p />
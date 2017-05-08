#!/usr/bin/php -q
<?php 
require('config.php');
require('phpagi.php'); 
$agi = new AGI(); 
$unique_id = md5($agi->agi_extension); 
cdr_connect();
$sql = "SELECT `callee_chan`,`caller_chan`, `timeout`, `orig_queue` FROM call_park WHERE `unique_id`='$unique_id'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
if(mysqli_num_rows($result)==1){
   $row = mysqli_fetch_array($result);
   $agi->set_music();
   for($i = $row['timeout']; $i<=$row['timeout']; $i++){
      if($i % 5){
          $agi->exec('Playback', 'one-moment-please');
      }
    wait(1);
   }
   $exten = explode("-", $row['callee_chan']);
   $exten = explode("/", $exten[0]);
   $exten = $exten[1];
   $exten = explode("-", $row['callee_chan']);
   if($agi->channel_status($row['callee_chan'])=='Channel is down and available'){
      $agi->exec('Playback', 'queue-thankyou');
      $agi->exec('Playback', 'followme/pls-hold-while-try');
      $agi->exec('Playback', 'transfer');
      $agi->exec_goto('default',$exten,'1');
   }
   else{
      $agi->exec('Playback', 'followme/sorry');
      if(!empty($row['orig_queue'])){
	  $agi->exec('Playback', 'queue-youarenext');
          $agi->exec('Queue', '$row[orig_queue]');
      }
      else{
         $agi->exec('Playback', 'please-try-again-later');
     }
   }
}
else{
    $agi->exec('Playback', 'invalid');
    $agi->exec('Playback', 'vm-goodbye');
}

((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);

?>

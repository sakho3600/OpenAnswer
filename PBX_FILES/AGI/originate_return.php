#!/usr/bin/php -q
<?php 
require('config.php');
require('phpagi.php'); 
$agi = new AGI(); 
$args = $agi->request;
$unique_id = md5($argv[1]);
//echo $unique_id;
//$unique_id = md5($agi->agi_extension); 
cdr_connect();
$sql = "SELECT `tech`, `exten`, `timeout`, `call_id` FROM originate WHERE `unique_id`='$unique_id'";
$result = mysql_query($sql);
if(mysql_num_rows($result)==1){
	$sql2 = "UPDATE originate SET `channel` = '$args[agi_channel]' WHERE `unique_id` ='$unique_id'";
	mysql_query($sql2);
   $row = mysql_fetch_array($result);
   if(!empty($row['3'])){
   		$agi->set_callerid($row['3']);
   }
   $agi->exec('Dial', "$row[0]/$row[1]|$row[2]");
}
else{
    $agi->exec('Playback', 'invalid');
    $agi->exec('Playback', 'vm-goodbye');
}

mysql_close();

?>

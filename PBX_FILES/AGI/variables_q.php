#!/usr/bin/php -q
<?php
//set_time_limit(3);

//The PHP-AGI library will help with extracting asterisk variables without writing an interaction script on our own.
require('phpagi.php');
require('config.php');
$agi = new AGI();

//The member interface variable will tell us which agent asterisk transferred the call to in the queue. We will need this information for parking
//and getting caller information.
$agent = $agi->get_variable('MEMBERINTERFACE');
$queue = $agi->get_variable('QUEUENAME');
$args = $agi->request;

//For debuging purposes, we can see the array result from the object.
/*print_r($data);
$data .= implode(":",$args)."\n";
$file = "/var/lib/asterisk/agi-bin/tst.txt";
$fh = fopen($file, 'w');
fwrite($fh, $data);
fclose($fh);*/


cdr_connect();
$sql = "INSERT INTO short_cdr (number_dialed,in_trunk,caller_id,unique_id,agent_channel,time,queue) 
		VALUES ('$args[agi_extension]','$args[agi_channel]','$args[agi_callerid]','$args[agi_uniqueid]','$agent[data]','".time()."','$queue[data]')";
mysql_query($sql);
mysql_close();

?>

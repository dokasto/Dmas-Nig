<?php
ini_set('max_execution_time', 3000); //3000 seconds = 5 minutes
error_reporting(E_ALL); 
function __autoload($class) { include_once('../class/class.'.$class.'.php'); } 
include('../functions.php') ;

  /* 
THIS CRON SCRIPT STARTS
2:55PM EVERYDAY 
   FUNCTIONS 
   - Send Daily SMS To Subscribers
   - Alert Admin On low message count
   - Alert subscribers on subscription expiration.
 */

$cron = new cron();
$cron->SendDailySMS();
$cron->LowMessageCountAlert();

exit();
?>
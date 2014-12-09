<?php
ini_set('max_execution_time', 3000); //3000 seconds = 5 minutes
function __autoload($class) { include_once('../class/class.'.$class.'.php'); } 
include('../functions.php') ;

/* 
THIS CRON SCRIPT STARTS
6:00AM EVERYDAY 
   FUNCTIONS 
   - Delete Expired Subscriptions
*/

$cron = new cron();
$cron->DeleteExpiredSubscribers() ;
exit();

?>
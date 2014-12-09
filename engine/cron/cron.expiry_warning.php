<?php
ini_set('max_execution_time', 3000); //3000 seconds = 5 minutes
error_reporting(E_ALL); 
function __autoload($class) { include_once('../class/class.'.$class.'.php'); } 
include('../functions.php') ;

  /* 
THIS CRON SCRIPT STARTS
checks for low message counts
 */

$cron = new cron();
$cron->expiry_warning();

exit();
?>
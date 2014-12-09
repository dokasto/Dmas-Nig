<?php
error_reporting(E_ALL); 
function __autoload($class) { include_once('class/class.'.$class.'.php'); } 
include('functions.php') ;


 $sendSMS = sms::SendSMS( '+447860034096' , 'JT5123YZF4' ) ;
 
 echo $sendSMS  ;
 
 exit();

?>
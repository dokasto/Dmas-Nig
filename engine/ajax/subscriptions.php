<?php
error_reporting(E_ALL); 
ini_set( 'display_errors','1');
    ini_set('max_execution_time', 3000); //3000 seconds = 5 minutes
    session_start();
    if(!isset($_POST['action'])) die();
	
	function __autoload($class) { include_once('../class/class.'.$class.'.php'); } 

	 include('../functions.php') ;
	
	$_POST = sanitize($_POST) ;  // clean all inputs
	$subscriptions = new subscription() ;
	$js = '' ;
	$status = '' ;
    $perload = 10 ;

	#### Load Subcribers List ####	
     if($_POST['action'] == 'LoadSubscriptions'){
	 $subscriptions->page = $_POST['page'] * $perload ;
	 $subscriptions->search = $_POST['search'] ;
	 $list = $subscriptions->LoadSubscribersList($perload);
	 $count = count($list);
	 
	 if($count == 0){
	 $list[] = '<tr><td colspan=4 align=center>sorry nothing was found !</td></tr>' ;
	 }
	 
	 $htmllist = str_replace(array("\n","\r"), "", implode(" " , $list )) ;
	 $js = "FetchSucccess('".$htmllist."','".$subscriptions->page."','".$count."')" ;
	 echo Js($js) ;
	 exit();
}
	

?>
<?php
error_reporting(E_ALL); 
ini_set( 'display_errors','1');
    ini_set('max_execution_time', 3000); //3000 seconds = 5 minutes
    session_start();
    if(!isset($_POST['action'])) die();
	
	function __autoload($class) { include_once('../class/class.'.$class.'.php'); } 

	 include('../functions.php') ;
	
	$_POST = sanitize($_POST) ;  // clean all inputs
	$sms = new sms() ;
	$js = '' ;
	$status = '' ;
	$textMsgLoadSize = 10 ;  /// text message load size

#### delete text messages list #	
     if($_POST['action'] == 'DeleteMsg'){
	 $sms->mid = $_POST['mid'] ;
	 $delete = $sms->deleteTextMsg();
	 if( $delete['status'] == true ){
	 $js = "DeleteSuccess('".$sms->mid."')" ;
	 }
	 else{
	 $js = 'DeleteError("'.$delete['result'].'")';
	 }
	 echo Js($js) ;
	 exit();
	 }
	
#### Load text messages list #	
     if($_POST['action'] == 'LoadTextMessages'){
	 $count = $_POST['count'] ;
	 $text = $sms->LoadTxtMsgs( $count * $textMsgLoadSize ) ;
	 $html = implode(' ', $text ) ;
	 $html = str_replace(array("\n","\r"), "", $html) ;
	 $cnt = count($text) ;
	 $js = "DisplayMsgs( $count , \"$html\" , $cnt )" ;
	 echo Js($js) ;
	 exit();
	 }

#### Add New Text Message #	
    if($_POST['action'] == 'AddTextMsg'){
	$sms->textmessage = $_POST['text'] ;	
	$sms->day = $_POST['day'] ;	
	/// First check for duplicate
	if( $sms->checkduplicate() == false){
	$add = $sms->AddNewSms() ;
	if( $add['status'] == true ){
	$js = 'PostSuccess()' ;
	}
	else{
	$js = "PostFailed('".$add['result'] ."')";
	}
	
	}
	else{
	$js = 'PostDuplicate()' ;
	}
	echo Js($js) ;
	exit();
	}

?>
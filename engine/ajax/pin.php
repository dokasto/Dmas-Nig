<?php
error_reporting(E_ALL); 
ini_set( 'display_errors','1');
    ini_set('max_execution_time', 3000); //3000 seconds = 5 minutes
    session_start();
    if(!isset($_POST['action'])) die();
	
	function __autoload($class) { include_once('../class/class.'.$class.'.php'); } 

	 include('../functions.php') ;
	
	$_POST = sanitize($_POST) ;  // clean all inputs
	$pin = new pin() ;
	$js = '' ;
	$status = '' ;


	#### Get details of pin -> status , subscriber , subscribe date #	
     if($_POST['action'] == 'GetPinInfo'){
     	$info = $pin->GetPinInformation($_POST['pincode']);
     	$HTML = '' ;
     	foreach ($info as $key => $value) {
     		$HTML .= '<tr><td>'.$key.'</td><td>'.$value.'</td></tr>' ;
     	}
     	echo $HTML ;
     	exit();
     }

	#### Load Pin Code List For view #	
     if($_POST['action'] == 'LoadBatchPinCodes'){
	 $pin->batch = $_POST['batch'] ;
	 $batch = $pin->LoadBatchPinCodes();
	 $list = str_replace(array("\n","\r"), "", implode(" " , $batch ) ) ;
	 echo $list ;
	 exit();
     }
	
#### Generate pins #	
     if($_POST['action'] == 'DeletePinBatch'){
	 $pin->batch = $_POST['batch'] ;
	 $delete = $pin->DeletePinBatch();
	 if( $delete == true){
	 $js = 'PinDeleteSuccess()' ;
	 }
	 else{
	  $js = 'PinDeleteError()' ;
	 }
	 echo Js($js) ;
	 exit();
}
	 

#### Generate pins #	
     if($_POST['action'] == 'GeneratePins'){
	 $pin->amount = $_POST['amount'] ;
	 $pin->type = $_POST['type'] ;
	 $generate = $pin->GeneratePins() ;
	 if( $generate['status'] == true ){
	 $js = 'PinGenerationSuccess()';
	 }
	 else{
	 $js = 'PinGenerationFailure("'.$generate['result'].'")';
	 }
	 echo Js($js) ;
	 exit();
	 }
	 
#### Load  Pin Batch information #	
     if($_POST['action'] == 'LoadBatchInfo'){
	 $information = $pin->PinBatchInfo( $_POST['batch'] ) ;
	 $information = str_replace(array("\n","\r"), "", $information) ;
	 echo $information ;
	 exit();
	 }
	
#### Load Pin list #	
     if($_POST['action'] == 'LoadPinList'){
	 $pinList = implode(" " , $pin->LoadPinList() );
	 echo $pinList ;
	 exit();
	 }


?>
<?php
/*  THIS CLASS HANDLES ALL SMS RELATED FUNCTIONALITY */

class sms extends database{

	private $properties = array();
	
	function __get($property){
	return $this->properties[$property];
	}
	
	function __set($property, $value){
	$this->properties[$property]=$value;
	}
	
	var $table = 'dm_text_messages' ; 

	
	function deleteTextMsg(){
	$r = $this->dbUPDATE( "status='deleted'", "mid='".$this->mid."'" ) ;
	return $r ;
	}
	
	function LoadTxtMsgs($count){
	$return = array();
	$result = $this->query("SELECT * FROM ".$this->table." WHERE status != 'deleted' ORDER BY mid DESC LIMIT ".$count.", 10"); 
	while ($data = $result->fetch_assoc() ){
	$return[] = $this->buildMsgHTML($data['mid'],$data['message'],$data['datestamp'],$data['status']);
	}
	return $return ;
	}
	
	
	function buildMsgHTML($mid , $msg , $timestamp , $status){
	$date = date("Y-M-d (g:i:s a)", strtotime($timestamp) )  ;
	$pieces = explode(" " , $msg )  ;
	$word = array_splice( $pieces , 0, 5) ;
	$excerpt = implode(" ", $word );
	$html = "<div class='panel' id='panel-".$mid."' >
				  <div class='panel-heading'>
				  <h3 class='panel-title'>
				  <a class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#accordion' href='#".$mid."'>
				  <i class='fa fa-dot-circle-o'></i> ".$excerpt." .....    
				  <date>".$date."</date></a>
				  </h3>
				  </div>
				  <div id='".$mid."' class='panel-collapse collapse' style='height: 0px;'>
				  <div class='panel-body'>
				  <span class='label label-warning label-square counter'>".$status."</span>
				  <br />
				  <p>".$msg."</p>
				  <div class='panel-tools '>
				  <a class='delete btn btn-danger btn-square' data-mid='".$mid."' href=''>
                  <i class='fa fa-trash-o fa-lg'></i> Delete</a>
			      </div>
				  </div>
				  </div>
				  </div> ";
	return $html ;
	}
	
	function checkduplicate(){
	$where = "message='".$this->textmessage."'" ;
	$select = $this->dbSELECT('message',$where) ; 
	$r = $select['result'] ;
	if(isset($r['message'])){
	return true ;
	}
	else{
	return false ;
	}
	}
	
	function AddNewSms(){
	$set = "message = '".$this->textmessage."', day = '".$this->day."'" ;
	$insert = $this->dbINSERT($set) ;
	return $insert ;
	}
	
	public static function SendSMS($GSM , $SMSText){
	  $username = 'divinemercyDM' ;
	  $password = 'victor@12' ;
	  $sender = 'DivineMercy' ;
	  //$sender = '+2348135143089' ;
	 
	  $URL = 'http://api.infobip.com/api/v3/sendsms/plain' ;
	  
	  $data = array(
	  'user' => $username, 
	  'password' => $password, 
	  'sender' => $sender, 
	  'SMSText' => $SMSText, 
	  'GSM' => $GSM, 
	  );
	  
      $SendCurl =  curlUsingGet($URL, $data);
	  
	  $xml = new SimpleXMLElement($SendCurl) ;
	  
	  $status = intval($xml->result->status) ;
	
	  if( $status == -2 ){
	  $email = 'chukwumav@gmail.com' ;
	  $subject = 'Not Enough Credit On SMS PROVIDER !' ; 
	  $message = 'This is to notify you that the credit on your SMS PROVIDER is no longer enough for sending SMS please recharge ASAP. <br> Divine Mercy Systems.' ;
	  $from = 'Divine Mercy System' ;
	  $sendMail = SendEmail($email,$subject,$message,$from) ;
	  }
	  
	  return $status ;
	  
	}	
	
}

?>
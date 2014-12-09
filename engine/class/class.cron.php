<?php

/*  THIS CLASS HANDLES ALL USER RELATED FUNCTIONALITY */

class cron extends database{
	
	private $properties = array();
	
	function __get($property){
	return $this->properties[$property];
	}
	
	function __set($property, $value){
	$this->properties[$property]=$value;
	}
	
	var $smsTable = 'dm_text_messages' ;
	var $subscribersTable = 'dm_subscribers' ;
	var $message  ;
	
	/* WRITE CLASS TO IMPLEMENT CRON SENDING OF MESSAGES */

	public function expiry_warning(){

		$q = $this->query('SELECT * FROM '.$this->subscribersTable);

	 while( $data = $q->fetch_assoc() ){
			$type = intval($data['type']) ;
			$subscribed = $data['datestamp'] ;
			$expires = $data['expires'] ;
			$days = $this->CalculateDays($expires);
			$sid = $data['sid'] ;
			$phone = $data['phone'] ;

			if( $days == 5 or $days == 3 ){ 			
					$msg = 'Your Subscription to the DivineMercy daily SMS will expire in '.$days. ' days.' ;
					sms::SendSMS( $phone , $msg );			
			}

			if( $days == 1 ){ 			
					$msg = 'Your Subscription to the DivineMercy daily SMS will expire tomorrow.' ;
					sms::SendSMS( $phone , $msg );			
			}
		}	

	}
	
	function ChooseRandomSMS($fetchtype = ''){
	//// match with current day 
	    $today = strtolower( date('l')  ) ;
	 
	   	$thisday = date('Y-m-d');
	   	$select = $this->dbSELECT('mid,message',"datesent='$thisday' AND day='".$today."'",$this->smsTable) ;
	   	if($select['status'] !== true){
	   		$select = $this->dbSELECT('mid,message',"status='unsent' AND day='".$today."'",$this->smsTable) ;
	   	}
	   

	    if(isset($select['result']['message'])){
		    $r = $select['result'] ;
		   $datesent = date('Y-m-d');
	      $update = $this->dbUPDATE("status='sent', datesent='$datesent'","mid='".$r['mid']."'",$this->smsTable );
		   if( $update['status'] == true ){
		       $this->message = $r['message'] ;
			  unset($r['mid'] , $r['message']) ; /// Script Optimization
		    }
		}
		else{
		        $this->message = 'Unable to get text message' ;
		}
	
	}
	
	function SendDailySMS(){ // choose subribers that have not exprired

		// Get Random message, must not be empty
		while ( strlen($this->message) < 10) {
			$this->ChooseRandomSMS() ;
		}

		// Clean up message and remove illegal characters
		$msgString = $this->message ;
		$this->message =  iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $msgString);
	
	if(isset($this->message)){
	$q=$this->query("SELECT phone FROM ".$this->subscribersTable." WHERE CURRENT_DATE() < expires ");
	/// First Send SMS to ADMIN
	sms::SendSMS('2348135143089','Sent: '.$this->message);
	//// Now send to other users 
    while ($data = $q->fetch_assoc() ){
	sms::SendSMS($data['phone'],$this->message);
	}
    $this->LogUpdate('SentMessage') ;	
	}
	else{
	echo 'No message to send !' ;
	}
	}
	
	function LowMessageCountAlert(){
	$q = $this->query("SELECT COUNT(message) AS count FROM ".$this->smsTable." WHERE status = 'unsent'"); 
	$r = $q->fetch_assoc();
	   if( $r['count'] < 10 ){
	   $email = 'chukwumav@gmail.com' ;
	   $subject = 'Low Message Alert' ; 
	   $message = 'This is to alert you that divine mercy has only '.$r['count'].' messages left to send. Please update the messages as soon as possible.<br/> Divine Mercy Systems.';
	   $from = 'Divine Mercy System' ;
	   SendEmail($email,$subject,$message,$from) ;
	   }
	}
	
	private function CalculateDays($mydate){
		$now = time(); 
     	$your_date = strtotime($mydate);
     	$datediff = $your_date - $now  ;
     	$days =  floor($datediff/(60*60*24));
     	return intval($days) ;
	}
	
	public function DeleteExpiredSubscribers(){
		$q = $this->query('SELECT * FROM '.$this->subscribersTable);

	 while( $data = $q->fetch_assoc() ){
			$type = intval($data['type']) ;
			$subscribed = $data['datestamp'] ;
			$expires = $data['expires'] ;
			$days = $this->CalculateDays($expires);
			$sid = $data['sid'] ;
			$phone = $data['phone'] ;

			echo $subscribed.' <br>';
			echo $expires.' <br>' ;
			echo $days.' <br>' ;			
			echo $type ;
			echo '<hr>' ;

			if( $days < 1 ){ // then delete user
				$qry = $this->query('DELETE FROM '.$this->subscribersTable.' WHERE sid="'.$sid.'"');
				if($qry['status'] == true){
					echo "deleted ".$data['phone'] ;
					$msg = 'You DivineMercy Subscription has expired. Please re-subscribe to continue recieving daily DivineMercy SMS.' ;
					sms::SendSMS( $phone , $msg );
				}
			}
		}	    
	}


}

?>
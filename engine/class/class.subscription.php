<?php
/*  THIS CLASS HANDLES ALL SMS RELATED FUNCTIONALITY */

class subscription extends database{
	
	private $properties = array();
	
	function __get($property){
	return $this->properties[$property];
	}
	
	function __set($property, $value){
	$this->properties[$property]=$value;
	}
	
	var $table = 'dm_subscribers' ; 
	var $pin_code_table = 'dm_pin_codes' ; 
	var $pin_batch_table = 'dm_pin_batch' ; 
	
	function LoadSubscribersList($perload){
	$search = '' ;
	$return = array() ;
	 if ( strlen($this->search) > 1 ){
	     $search = " WHERE phone LIKE '%".$this->search."%'" ;
	    }
	     $q =  "SELECT * FROM ".$this->table.$search." ORDER BY sid DESC LIMIT ".
		            $this->page." , ".$perload ;
	              $query = $this->query($q);
	              while ($data = $query->fetch_assoc() ){
	                $return[] = $this->SusbscriberListHTML($data) ;
	             }
				 
			return $return ;
	}
	
	function SusbscriberListHTML($data) {
	$this->pincode = $data['code'] ;
	$this->getPinType() ;
	$date = date("Y-M-d (g:i:s a)", strtotime($data['datestamp']) )  ;
	$expire = date("Y-M-d (g:i:s a)", strtotime($data['expires']) )  ;
	$html = '<tr>
	               <td>'.$data['phone'].'</td>
				   <td>'.$this->type.' days</td>
				   <td>'.$date.'</td>
				   <td>'.$expire.'</td>
				   </tr>' ;
	return $html ;
	}
	
	function getPinType(){
		$qry = $this->query("SELECT batch FROM ".$this->pin_code_table." WHERE code='".$this->pincode."'");
		$qres = $qry->fetch_assoc() ;
		$batch = $qres['batch'] ;

		$q = $this->query("SELECT type FROM ".$this->pin_batch_table." WHERE batch='".$batch."'") ;
	    $r = $q->fetch_assoc();
	    $this->type = $r['type'] ; 
	}
	
    public function ValidatePinCode(){
	 $return = false ;
	 $select = $this->dbSELECT('status,code',"code='".$this->pincode."'",$this->pin_code_table ) ;
	   if( $select['status'] == true ){
	       $r = $select['result']  ;
	       if( isset($r['code']) and  $r['status'] == 'VALID' ){
		     $return = true ;
		    }
	 
	    }
		  return $return ;
	}
	
	public function SubscriberExists(){
	$return = false ;
	$select = $this->dbSELECT('phone,code',"phone='".$this->phone."'") ; 
	     /// Check if user exists
	     if(isset($select['result']['phone'])){
	     $this->pincode =  $select['result']['code'] ;
	      // check if the user pincode exists and is valid or not
	      $select = $this->dbSELECT('code,status',"code='".$this->pincode."'",$this->pin_code_table ) ; 
	        if ( $select['status'] == true ){
	           $r = $select['result'] ;
	             if( $r['status'] == 'INVALID'){
					$return = true ;
				}
	        }
     	}
		return $return  ;
	}

	public function subscriptionhasExpired($sub_date , $type){
		$expired = '' ;
		$now = time() ;
		$dStart = strtotime($sub_date) ;
		$diff = $now - $dStart ;
		$days = intval (ceil( $diff/(60*60*24) )  );
		$type = intval($type) ;

		if( $days > $type ){
			$expired = true ;
		}else{
			$expired = false ;
		}
		return $expired ;
	}
	
	function SubscribeNewPerson(){
	$status = false ;
    $expires =  date("Y-m-d H:i:s", strtotime("+".intval($this->type)." days") );
	$set = "phone = '".$this->phone."', type='".$this->type."', code='".$this->pincode."', expires='".$expires."' " ;
	$insert = $this->dbINSERT($set) ;
	  if( $insert['status'] == true ){
	     $update = $this->dbUPDATE( "status='INVALID'", "code='".$this->pincode."'",$this->pin_code_table) ;
	            if($update['status'] == true ){
				  $status = true ;
	             }
				 else{  /// Delete user from DB if pincode update to INVALID fails
				 $this->dbDELETE("phone='".$this->phone."'") ;
				 }
	    }
		return $status ;
	}
	
	public static function Message($messgeType,$days){
	
	$message = '' ;
	
	switch($messgeType) {
	
	case 'SubscribeSuccess':
	$message = 'DIVINEMERCY devotional alert for '.$days.' days activated. click here http://tinyurl.com/q5gtgyt to download the android app ' ;
	break;
	
	case 'SubscribeError':
	$message = 'Sorry, unable to complete subscription, please try again later. click here http://tinyurl.com/q5gtgyt to download the android app' ;
	break;
	
	case 'AlreadySubscribed':
	$message = 'You have already subcribed to the DIVINEMERCY devotional alert , click here http://tinyurl.com/q5gtgyt to download the android app' ;
	break;
	
	case 'InvalidPin':
	$message = 'Sorry the pin entered is incorrect or has already been used kindly try again or contact customer care on +2347057356038. click here http://tinyurl.com/q5gtgyt to download the android app' ;
	break;
	
	}
	return trim($message)  ;
	}

}

?>
<?php

/*  THIS CLASS HANDLES ALL USER RELATED FUNCTIONALITY */

class frontend extends database{
	
	private $properties = array();
	
	function __get($property){
	return $this->properties[$property];
	}
	
	function __set($property, $value){
	$this->properties[$property]=$value;
	}
	
	function FetchLog(){
	       $this->table = 'dm_log' ;
	       $result = $this->dbSELECT(' * ',"lid='1'") ;
		   $this->pinsgenerated = $result['result']['pins_generated'] ;
		   $this->messagessent = $result['result']['messages_sent'] ;
	}
	
	function PinLog(){
	           $result = array('valid' => 0,'invalid' => 0, 'total' => 0);
	           $query = $this->query("SELECT status FROM dm_pin_codes"); 
	            while ($data = $query->fetch_assoc() ){
				             if ($data['status'] == 'VALID') {
							 $result['valid']++ ;
						     }
							 elseif($data['status'] == 'INVALID'){
							 $result['invalid']++ ;
							 }
							 $result['total']++;
				}
				return $result ;
	}
	
	function FetchSubscribers(){
              $result = array(
			  'one_month' => 0, 
			  'two_months' => 0 , 
			  'three_months' => 0 , 
			  'total' => 0);
	         $query = $this->query("SELECT type FROM dm_subscribers"); 
	            while ($data = $query->fetch_assoc() ){
	                         if ($data['type'] == 30) {
							 $result['one_month']++ ;
						     }
							 else if( $data['type'] == 60){
							  $result['two_months']++ ;
							 }
							 else{
							 $result['three_months']++ ;
							 }
							 $result['total']++ ;
	                    }
					return $result ;
	}
	
	function FetchMsgStats(){
	     $result = array('sent' => 0,'unsent' => 0, 'total' => 0);
	     $query = $this->query("SELECT status FROM dm_text_messages"); 
	            while ($data = $query->fetch_assoc() ){
				             if ($data['status'] == 'sent') {
							 $result['sent']++ ;
						     }
							 elseif($data['status'] == 'unsent'){
							 $result['unsent']++ ;
							 }
							 $result['total']++;
				}
				return $result ;
	}
	
}

?>
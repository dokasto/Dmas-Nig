<?php
/*  THIS CLASS HANDLES ALL SMS RELATED FUNCTIONALITY */

class pin extends database{

	private $properties = array();
	
	function __get($property){
	return $this->properties[$property];
	}
	
	function __set($property, $value){
	$this->properties[$property]=$value;
	}
	
	var $pin_btch_table = 'dm_pin_batch' ; 
	var $pin_code_table = 'dm_pin_codes' ; 

	public function GetPinInformation($pincode){
		$pininfo = array('status' => 'Not Found !');
		$this->table = $this->pin_code_table ;
		$select = $this->dbSELECT( ' code , status ' , "code='".$pincode."'") ; 
		if( $select['status'] == true ){
			$pininfo['status'] = $select['result']['status'] ;
			//if( $select['result']['status'] == 'INVALID'){ // pin has been used	
			    $this->table = 'dm_subscribers' ;			
				$userinfo = $this->dbSELECT( ' * ' , "code='".$pincode."'" ) ; 
				 if( $userinfo['status'] == true ){
				 	 $pininfo['subscriber'] = $userinfo['result']['phone'] ;
				     $pininfo['subscription_date'] = $userinfo['result']['datestamp'] ;
				     $pininfo['expiration_date'] = $userinfo['result']['expires'] ;
				 }				
			//} 
		}
		return $pininfo;
	}
	
	function LoadBatchPinCodes(){
	$return = array();
	$pincodes = $this->FetchPinCodes('code,status') ;
	$i = 1 ;
	foreach( $pincodes as $data ){
	$return[] = $this->build_pinVeiw_HTML($data,$i);
	$i++;
	}
	return $return ;
	}
	
	function GetPinCodes($colums){
	$pincodes = $this->FetchPinCodes($colums);
	return $pincodes ;
	}
	
	private function FetchPinCodes($colums){
	$return = array();
	$result = $this->query("SELECT ".$colums." FROM ".$this->pin_code_table." WHERE batch='".$this->batch."' ORDER BY pid DESC"); 
	while ($data = $result->fetch_assoc() ){
	$return[] = $data ;
	}
	return $return ;
	}
	
	function build_pinVeiw_HTML($data,$i){
	$pin = $data['code'] ;
	$status = $data['status'] ;
	$html = '<tr><td>'.$i.'</td><td>'.$pin.'</td><td>'.$status.'</td></tr>' ;
	return $html ;
	}
	
	function DeletePinBatch(){
	$return = false ;
	$this->table = $this->pin_code_table ;
	$deletepincode = $this->dbDELETE("batch='".$this->batch."'") ;
	if( $deletepincode['status'] == true ){
	$this->table = $this->pin_btch_table ;
	$deletepinbatch = $this->dbDELETE("batch='".$this->batch."'") ;
	if( $deletepinbatch['status'] == true ){
	$return = true ;
	}
	}
	return $return ;
	}
	
	function PinBatchInfo($batch){
	$this->table = $this->pin_btch_table ;
	$select = $this->dbSELECT( ' * ' , "batch='".$batch."'") ; 
	$batchinfo = '' ;
	if( $select['status'] == true ){
	$result = $select['result'] ;
	$generated = date("Y-M-d (g:i:s a)", strtotime($result['generated']) )  ;
	$pintype = $result['type'].' days'  ;
	$totalpins = $result['total']   ;
	$printstatus = $result['printed']   ;
	
	$validpins = $this->count_pins('VALID',$batch) ;
	$invalidpins = $this->count_pins('INVALID',$batch) ;
	
	$batchinfo = $this->BatchInfoHTML($generated,$pintype,$totalpins,$validpins,$invalidpins,$batch,$printstatus); 
	}
	else{
	$batchinfo = 'Nothing found !' ;
	}
	return $batchinfo ;
	}
	
	function count_pins($type,$batch){
	$q = $this->query('SELECT count(pid) AS count FROM '.$this->pin_code_table." WHERE status='".$type."' AND batch='".$batch."'");
	$r = $q->fetch_assoc();
	return $r['count'] ;
	}
	
	function BatchInfoHTML($generated,$pintype,$totalpins,$validpins,$invalidpins,$batch,$printstatus){
	$html = "
	                       <li class='list-group-item'>".$generated."</li>
	                       <li class='list-group-item'>Printed: <strong>".$printstatus."</strong></li>
							<li class='list-group-item'><span class='badge badge-info badge-square'>".$pintype."</span>Pin Type</li>
							<li class='list-group-item'><span class='badge badge-warning badge-square'>".$totalpins."</span>Total Pins</li>
							<li class='list-group-item'><span class='badge badge-success badge-square'>".$validpins."</span>Valid Pins</li>
							<li class='list-group-item'><span class='badge badge-danger badge-square'>".$invalidpins."</span>Invalid Pins</li>
							<li class='list-group-item' style='text-align:center;'>
							<button type='button' onClick=\"DownloadPins('".$batch."')\" class=\"btn btn-primary downloadpins btn-square\">
							<i class='fa fa-download'></i> Download</button>
							<button type='button' data-batch='".$batch."' class=\"btn btn-warning viewPinbtch btn-square\">
							<i class='fa fa-eye'></i> View</button>
							<button type='button' data-batch='".$batch."' class='btn btn-danger deletePins btn-square'>
							<i class='fa fa-trash-o'></i> Delete</button>
							</li>"
							;
	return $html ;
	
	}
	
	function LoadPinList(){
	$return = array();
	$result = $this->query("SELECT bid,batch FROM ".$this->pin_btch_table." ORDER BY bid DESC"); 
	while ($data = $result->fetch_assoc() ){
	$return[] = $this->build_pinlist_HTML($data);
	}
	return $return ;
	}
	
	function build_pinlist_HTML($data){
	$batch = $data['batch'] ;
	$name = '#batch-'.$data['bid'] ;
	$html = "<a href='".$batch."' class='list-group-item'>".$name."</a>" ;
	return $html ;
	}
	
	function GeneratePins(){
	$return['status'] = false ;
	$batchToken = get_random() ;  // get random token for batch pins
	$set = "batch='".$batchToken."', type='".$this->type."', total='".$this->amount."' " ;
	
	$this->table = $this->pin_btch_table ; // change table
	$createbatch = $this->dbINSERT($set) ;
	
	if( $createbatch['status'] == true ){
	
	for ($i = 1; $i <= $this->amount; $i++) {
	$this->table = $this->pin_code_table ; // change table again
	$pinCode = $this->GenerateRandomCode() ;
	$setquery = "code='".$pinCode."', batch='".$batchToken."'" ;
    $insert = $this->dbINSERT($setquery) ;
	$return['status'] = true ;
    }
	
	}
	else{
	$return['result'] = $createbatch['result'] ;
	}
	
	if( $return['status'] == true ){
	$this->LogUpdate('GeneratedPin');
	}
	
	return $return ;
	}
	
   
     function GenerateRandomCode(){
     $characters = array(
     "A","B","C","D","E","F","G","H","J","K","L","M",
     "N","P","Q","R","S","T","U","V","W","X","Y","Z",
     "1","2","3","4","5","6","7","8","9","0",
	 "A1","B3","C3","D4","E5","F6","G7","H8","J9","K0","1L","2M",
     "3N","4P","5Q","6R","7S","8T","9U","0V","W1","X2","Y3","Z4");
     $keys = array();
     while(count($keys) < 10) {
    $x = mt_rand(0, count($characters));
    if(!in_array($x, $keys)) {
       $keys[] = $x ;
    }
    }
   @$random_chars = '' ;
   foreach($keys as $key){
   @$random_chars .= $characters[$key];
    }
	$pincode = substr($random_chars, 0, 10);  
    return $pincode;
   }

}

?>
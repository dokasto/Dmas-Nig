<?php
/*  THIS CLASS HANDLES ALL DATABASE RELATED FUNCTIONALITY */

class database extends MySQLi {

  const DB_USERNAME = 'root' ;
  const DB_PASSWORD = '' ;
  const DB_HOST = 'localhost' ;
  const DB_NAME = 'dmasnigc_data' ;

	public function __construct()
	{
		parent :: __construct( database::DB_HOST , database::DB_USERNAME , database::DB_PASSWORD , database::DB_NAME );
		if(mysqli_connect_error())
		{
			die("Database connection error! (" . mysqli_connect_errno() . ") ");
		}
	}
	
	var $result = array() ;

	public function cleanIt($input){ 
  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
  );
 
    $output = preg_replace($search, '', $input);
    return $output;
  }

  public function cleanUP($input) {
   $link = mysqli_connect( database::DB_HOST , database::DB_USERNAME , database::DB_PASSWORD , database::DB_NAME );

    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = $this->cleanUP($val);
        }
    }
    else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $input  = $this->cleanIt($input);
        $output = mysqli_real_escape_string($link,$input);
    }
        return $output;
    }
	
	function dbUPDATE( $set , $where,$table=''){
	
	if( $where !== ''){ $WHERE = ' WHERE '.$where ;	}
	else{ $WHERE = '' ;	}
	
	$data_table = '' ;
	if( $table !== ''){
	$data_table = $table ;
	}
	else{
	$data_table = $this->table ;
	}
	if($this->query("UPDATE ".$data_table ." SET ".$set.$WHERE)){
	$this->result['status'] = true ;
	}
	else{
	$this->result['status'] = false ;
	$this->result['result'] = $this->error ;
	}
	
	return $this->result ;
	}
	
	function dbSELECT($colums,$where='',$table=''){
	$datas = array() ; 
	$data_table = '' ;
	if( $table !== ''){
	$data_table = $table ;
	}
	else{
	$data_table = $this->table ;
	}
	
	if( $where !== ''){ $WHERE = ' WHERE '.$where ;	}
	else{ $WHERE = '' ;	}
	
	$q = $this->query('SELECT '.$colums.' FROM '.$data_table.$WHERE.' ');
	$r = $q->fetch_assoc();
	if($r){
	$this->result['status'] = true ;
	foreach($r as $key => $value){
	$datas[$key] = $value  ;
	}
	$this->result['result'] = $datas ;
	}
	else{
	$this->result['status'] = false ;
	$this->result['result']  = $this->error ;
	}
	return $this->result ;
	}
	
	function dbINSERT($set ,$table=''){
	$data_table = '' ;
	if( $table !== ''){
	$data_table = $table ;
	}
	else{
	$data_table = $this->table ;
	}
	if($this->query("INSERT INTO ".$data_table ." SET ".$set)){
	$this->result['status'] = true ;
	$this->result['result'] = 'success' ;
	}
	else{
	$this->result['status'] = false ;
	$this->result['result'] = $this->error ;
	}
	return $this->result ;
	}
	
	function dbDELETE($where,$table=''){
	$data_table = '' ;
	if( $table !== ''){
	$data_table = $table ;
	}
	else{
	$data_table = $this->table ;
	}
	if($this->query("DELETE FROM ".$data_table." WHERE ".$where)){
	$this->result['status'] = true ;
	}
	else{
	$this->result['status'] = false ;
	$this->result['result'] = $this->error ;
	}
	return $this->result ;
	}
	
	function LogUpdate($action){
	$usetable = 'dm_log' ;
	$q = $this->dbSELECT(' * ',"lid='1'",$usetable) ;
	    $result = $q['result'] ;
	    $PinsGenerated = intval($result['pins_generated']) ;
	    $MessagesSent = intval($result['messages_sent']);
		      	if( $action == 'GeneratedPin'){
				$PinsGenerated++ ;
             $this->dbUPDATE("pins_generated='".$PinsGenerated."'","lid='1'",$usetable) ;
	              }
		      else if( $action == 'SentMessage' ){
			  $MessagesSent++;
		     $this->dbUPDATE("messages_sent='".$MessagesSent."'","lid='1'",$usetable) ;
		       }
	    
	}
	
}

?>
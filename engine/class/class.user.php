<?php

/*  THIS CLASS HANDLES ALL USER RELATED FUNCTIONALITY */

class user extends database{
	
	private $properties = array();
	
	function __get($property){
	return $this->properties[$property];
	}
	
	function __set($property, $value){
	$this->properties[$property]=$value;
	}
	
	var $table = 'dm_users' ; //users table
	
	function ChangePassword(){
	$update = $this->dbUPDATE("password='".$this->newpassword."'" ,"uid='1'" ) ;
	return $update['status'] ;
	}
	
	function ChangeUsername(){
	$update = $this->dbUPDATE("username='".$this->username."'" ,"uid='1'" ) ;
	return $update['status'] ;
	}
	
	function fetch_user_data($data){
	$select = $this->dbSELECT($data,"uid = '".$this->uid."'")  ;
	return $select['result'] ;
	}

	function Login(){
	$where = "username='".$this->username."' AND password='".$this->password."'" ;
	$select = $this->dbSELECT('username,password',$where) ; 
	$r = $select['result'] ;
	if(isset($r['username'])){
	$_SESSION['username'] = $r['username'] ;
	$_SESSION['key'] = sha1($r['password'] );
	return true ;
	}
	else{
	return false ;
	}
	}
	
	function IsLoggedIn(){
	$return = false ;
	if(isset($_SESSION['username']) and isset($_SESSION['key'])){
	$check = $this->dbSELECT('username,password','username='."'".$_SESSION['username']."'") ;
	$r = $check['result'] ;
	if(isset($r['username']) and $_SESSION['key'] == sha1($r['password'] ) ){
	$return = true ;
	}
	}
	return $return;
	}

}

?>
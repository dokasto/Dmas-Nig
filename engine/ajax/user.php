<?php
error_reporting(E_ALL); 
ini_set( 'display_errors','1');
    ini_set('max_execution_time', 3000); //3000 seconds = 5 minutes
    session_start();
    if(!isset($_POST)) die();
	
	function __autoload($class) { include_once('../class/class.'.$class.'.php'); } 
	
    include('../functions.php') ;
	
	$_POST = sanitize($_POST) ;  // clean all inputs
	$user = new user() ;
	$js = '' ;
	$status = '' ;
	$action = $_POST['action'] ;
	
#### Login User #	
    if( $action == 'userLogin' ){
	$user->username = $_POST['username'] ;
	$user->password = sha1($_POST['password']) ;
	$login = $user->Login();
	if($login == true){
	$js = 'LoginSucces()' ;
	}
	else{
	$js = 'LoginFailed()';
	}
	echo Js($js) ;
	}
	
#### Change Username #	
    if($action == 'changePassword'){
	$user->password = sha1($_POST['oldpassword']);
	$user->newpassword = sha1($_POST['newpassword']);
	$user->username = $_SESSION['username'] ;
	
	   if ( $user->Login()== true ){
	   
	          if( $user->ChangePassword()== true ){
			       $js = 'PswdChangeSuccess()' ;
			  }
			  else{
			        $js = 'PswdChangeError()' ;
			  }
	 
	    }
		else {
		      $js = 'WrongPassword()' ;
		}
	    echo Js($js) ;
		exit();
	}

#### Change Username #	
    if($action == 'changeUsername'){
	    $user->username =$_POST['username'] ;
        if( $user->ChangeUsername() == true ){
		     $js = 'ChangeUsernameSucces()' ;
		}
		else{
		      $js = 'ChangeUsernameError()' ;
		}
		echo Js($js) ;
		exit();
	}
	

?>
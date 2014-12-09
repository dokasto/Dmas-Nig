<?php
//print_r($_SERVER);
include_once("../../class/class.database.php") ;
include_once("../../class/class.subscription.php") ;
include_once("../../class/class.cron.php") ;
include_once("../../class/class.app.php") ;

require '../classes/accounts.php';
require '../classes/appServer.php';
require '../libs/Slim/Slim.php';


\Slim\Slim::registerAutoloader();
 
$app = new \Slim\Slim();
 
// User id from db - Global Variable
$user_id = NULL;

/**
 * Verifying required params posted or not
 */
function verifyRequiredParams($required_fields) { 

    $error = false;
    $error_fields = "";
    $request_params = array();

    $request_params = $_REQUEST ;  

    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }
 
    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoRespnse(200, $response);
        $app->stop();
    }
}

/**
 * Validating email address
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response["message"] = 'Email '.$email.' address is not valid';
        echoRespnse(200, $response);
        $app->stop();
    }
}
 
 
/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);
 
    // setting response content type to json
    $app->contentType('application/json');
 
    echo json_encode($response);
}

// Call this function incase apache_request_header is not working 
// Authentication can only be gotten with post request
// Add line to httaccess for authorization to show
if( !function_exists('apache_request_headers') ) {
    function apache_request_headers() {
        $arh = array();
        $rx_http = '/\AHTTP_/';

        foreach($_SERVER as $key => $val) {
            if( preg_match($rx_http, $key) ) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = array();
           // do some nasty string manipulations to restore the original letter case
           // this should work in most cases
                $rx_matches = explode('_', $arh_key);

                if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
                    foreach($rx_matches as $ak_key => $ak_val) {
                        $rx_matches[$ak_key] = ucfirst($ak_val);
                    }

                    $arh_key = implode('-', $rx_matches);
                }

                $arh[$arh_key] = $val;
            }
        }

        return( $arh );
    }
}

/**
 * Adding Middle Layer to authenticate every request
 * Checking if the request has valid api key in the 'Authorization' header
 */
function authenticate(\Slim\Route $route) {
    // Getting request headers
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();
 
    // Verifying Authorization Header
    if (isset($headers['AUTHORIZATION'])) {
        $account = new accounts() ;
 
        // get the api key
        $api_key = $headers['AUTHORIZATION'];
        // validating api key
        if (!$account->isValidApiKey($api_key)) {
            // api key is not present in users table
            $response["error"] = true;
            $response["message"] = "Access Denied. Invalid Api key";
            echoRespnse(200, $response);
            $app->stop();
        } else {
            // get user primary key id
            global $user_id ;
            $user_id = $account->getUserId($api_key);
        }
    } else {
        // api key is missing in header
        $response["error"] = true;
        $response["message"] = "Api key is misssing";
        echoRespnse(200, $response);
        $app->stop();
    }
}

function onlyAlphNumeric($string){
	$clean = preg_replace("/[^A-Za-z0-9 ]/", '', $string);
	$cleanStr = preg_replace("/[^\w\d ]/ui", '', $clean);
	return $cleanStr ;
}


/**
 * Get new message for today 
 * url - /daily
 * method - POST [Authentication ApiKey can only be gotten via POST]
 * @return Array $result contains new message for the day ;
 */
$app->get('/daily', 'authenticate', function() use ($app) {
  $response = array();
  $account = new accounts() ;
  $cron = new cron();

  $headers = apache_request_headers();
  global $user_id;
  $response["error"] = true ;

  // check if user is active
  $phone = $account->getUserPhone($user_id) ;
  $subscriptionArray = $account->getSubscriptionData($phone) ;

  if($subscriptionArray['status'] == 'Active'){// send message
  	// Get message for today
  	$cron->ChooseRandomSMS() ;
  	$response["error"] = false ;
  	$response["message"] = onlyAlphNumeric($cron->message) ;
  	echoRespnse(202, $response);
  }else{
  	$response["error"] = true ;
  	$response["message"] ='account is not active' ;
  	echoRespnse(200, $response);
  }  
});



/**
 * Subscribe new user 
 * url - /subscribe
 * method - POST
 * @return String $message contains xml data of subcription info ;
 */
$app->put('/subscribe', 'authenticate', function() use ($app) {

  $response = array();
  $account = new accounts() ;
  $headers = apache_request_headers();
  global $user_id;
  $response["error"] = true ;

  $phone = $account->getUserPhone($user_id) ;
  $pincode = $headers['PINCODE'] ;
  $account->pincode = $pincode ;
  $account->phone = $phone ;

     // check if pincode is valid
  if($account->ValidatePinCode()){

  	// check if user has already subscribed
  	if($account->SubscriberExists()){
  		$response["message"] = 'You are already subscribed to the divine mercy daily SMS service' ;
  	}else{
  		// Subscribe user
  		$account->getPinType() ; // Get the pin type

  		if($account->SubscribeNewPerson()){
  			// Subscription was successul
                $response["error"] = false ;
                $response["message"] = 'Subscription was successful' ;
  		}else{
  			$response["message"] = 'Subscription was not successful' ;
  		}
  	}

  }else{ // Pincode not valid
  	$response["message"] = 'The pincode is not valid' ;
  }

  echoRespnse(202, $response);

});


/**
 * Get subscription details of user via apikey sent in Authentication header
 * url - /subscription
 * method - GET
 * @return String $message contains xml data of subcription info ;
 */
$app->get('/subscription', 'authenticate', function() use ($app){

  $response = array();
  $account = new accounts() ;
  global $user_id;

  $phone = $account->getUserPhone($user_id) ;
  $subscriptionArray = $account->subscriptionData($phone) ;

  echoRespnse(202, $subscriptionArray);

});


/**
 * Create new user account
 * url - /register
 * method - POST
 * @return String $api_key ;
 */
$app->post('/register', function() use ($app){ /* Update: changed from get to post added new param deviceID*/

           // check for required params
            verifyRequiredParams(array('name', 'email', 'phone', 'password' , 'country' , 'device_id'));
            $response = array();

            $account = new accounts() ;

            // reading post params
            $phone = $account->cleanUP( $app->request->get('phone') );
            $name = $account->cleanUP( $app->request->get('name') );
            $email = $account->cleanUP( $app->request->get('email') );
            $password = $account->cleanUP( $app->request->get('password') );
            $country = $account->cleanUP( $app->request->get('country') );
            $device_id = $account->cleanUP( $app->request->get('device_id') );
            $api_key = $account->generateApiKey() ;

            /// Validate email address
            validateEmail($email);    

            // check if user exists
            if($account->userExists($email,$phone)){
                $response["error"] = true;
                $response["message"] = "this email or phone number has been used.";
                echoRespnse(200, $response);
            }else{
              $create = $account->createAccount($name,$email,$phone,$password,$country,$api_key,$device_id) ;
              if( $create['status'] == true ){  //create account
                   $response["error"] = false ;
                   $response["message"] = $api_key ;
                   echoRespnse(202, $response);
              }else{
                   $response["error"] = true ;
                   $response["message"] = "Unable to create account :: ". $create['result'] ;
                   echoRespnse(200, $response);
              }

            }

      });


/**
 * Sign into account
 * url - /login
 * method - POST
 * @return boolean false/api_key ;
 */
$app->post('/login', function() use ($app){

           // check for required params
            verifyRequiredParams(array('email', 'password', 'device_id'));
            $response = array();

            $account = new accounts() ;

            // reading post params
            $phone = $account->cleanUP( $app->request->get('email') );
            $password = $account->cleanUP( $app->request->get('password') );
            $device_id = $account->cleanUP( $app->request->get('device_id') );

            // login user
            $login = $account->AuthenticateLogin($phone,$password,$device_id) ;

            if($login == false){
                $response["error"] = true ;
                $response["apikey"] = "" ;
                $response["name"] = "" ;
                echoRespnse(200, $response);
            }else{
                   $response["error"] = false ;
                   $response["apikey"] = $login['apikey'] ;
                   $response["name"] = $login['name'] ;
                   echoRespnse(202, $response);
            }
      });

/**
 * Fetch all testimonies from the server
 */
$app->get('/testimony', 'authenticate' , function() use ($app){

    $response = array();

    $account = new accounts() ;
    $appServer = new appServer() ;

    $result = $appServer->fetchTestimonies();

    if(is_array($result)){
        $response["error"] = false ;
        $response["data"] = $result ;
        echoRespnse(200, $result );
    }else{
        $arr = array( "name" => '' , "message" => '' , "time" => '') ;
        $response["error"] = true ;
        $response["data"] = $arr ;
        echoRespnse(200, $arr );
    }


});

/**
 * Post a new testimony
 */
$app->post('/testimony/post', 'authenticate', function() use ($app){

    // check for required params
    verifyRequiredParams(array('message'));
    $response = array();

    $account = new accounts() ;

    $appServer = new appServer() ;
    $headers = apache_request_headers();
    global $user_id;

    // reading post params
    $message = $account->cleanUP( $app->request->get('message') );
    $Post = $appServer->postNewTestimony($message,$user_id) ;

    if($Post['status'] == false){
        $response["error"] = "true" ;
        $response["message"] = $Post['result'] ;
        echoRespnse(200, $response);
    }else{
        $response["error"] = "false" ;
        $response["message"] = '' ;
        echoRespnse(202, $response);
    }

});

/**
 * Fetch all image name in gallery
 */
$app->get('/gallery', 'authenticate' , function() use ($app){

    $appServer = new appServer() ;
    $gallery = $appServer->fetchGallery() ;

    if(is_array($gallery)){
        $response["files"] = $gallery ;
    }else{
        $response["files"] = false ;
    }

    echoRespnse(200, $response);
});

/*
 * Fetch all downloads
 */
$app->get('/downloads', 'authenticate' , function() use ($app){

    $response = array();
    $account = new accounts();

    $appServer = new appServer() ;
    $downloadsList = $appServer->fetchDownloads();

    if(is_array($downloadsList)){
        $response["downloads"] = $downloadsList ;
    }else{
        $response["downloads"] = false ;
    }

    echoRespnse(200, $response);

});


/* Fetch Resources */
$app->get('/resources', 'authenticate' , function() use ($app){


    $response = array();
    $account = new accounts();

    $appServer = new appServer() ;
    $resourceList = $appServer->fetchResources();

    if(is_array($resourceList)){
        $response["resources"] = $resourceList ;
    }else{
        $response["resources"] = false ;
    }

    echoRespnse(200, $response);

});


/* Fetch Scrolling/ticker text */
$app->get('/ticker', 'authenticate' , function() use ($app){
    $appServer = new appServer() ;
    $tickerArr = $appServer->getScrollingText();

    if(is_array($tickerArr)){
        $response["tickers"] = $tickerArr ;
    }else{
        $response["tickers"] = false ;
    }

    echoRespnse(200, $response);
});


$app->run();

?>
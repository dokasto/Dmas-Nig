<?php

/**
 *  This handles everything about the user from a
*  account creation to delete
 *  @author Nkwocha Udoka K. www.udonline.net
*/
class accounts extends subscription{

  var $accts_table = 'dm_accounts' ;
    private $phone;

    /**
    * check if a user exists
    * @param String $email email address of the user
    * @param String $phone phone address of the user
    * @return boolean $result true/false
    */
  public function userExists($email,$phone){
    $select = $this->dbSELECT('email,phone',"email='".$email."' OR phone='".$phone."'",$this->accts_table ) ;
    return $select['status'] ;
  }

    /**
     * Create account
     * @param String $name
     * @param String $email
     * @param $phone
     * @param String $password
     * @param String $country
     * @param $apiKey
     * @param $device_id
     * @return boolean $result true/false
     */
  public function createAccount($name,$email,$phone,$password,$country,$apiKey,$device_id){
    $set = "name='".$name."', email='".$email."', phone='".$phone.
           "', password='".$password."', country='".$country.
           "', apikey='".$apiKey."', device_id='".$device_id."'" ;
    $insert = $this->dbINSERT($set,$this->accts_table) ;
    return $insert ;
  }

    /**
     * Log in to account
     * @param String $email
     * @param String $password
     * @param $device_id String Google Cloud messenger id
     * @return boolean $result true/false
     */
  public function AuthenticateLogin($email,$password,$device_id){
    $select = $this->dbSELECT('apikey,name',"email='".$email."' AND password='".$password."'",$this->accts_table ) ;
    if($select['status'] == true ){
      $return = $select['result'] ;
      $this->dbUPDATE("device_id='".$device_id."'","email='".$email."'",$this->accts_table);
    }else{
      $return = false ;
    }
    return $return ;
  }

  /**
    * Get account details with key
    * @param Array $dataToGet  
    * @param Array $keyToUse 
    * @return Array $select 
    */
  public function getAccountInfo($dataToGet,$keyToUse){
    $colums = implode(",", $dataToGet) ;
    $where = $keyToUse['key']."='".$keyToUse['value']."'" ;
    $select = $this->dbSELECT( $colums , $where , $this->accts_table ) ;
    return $select ;    
  }


    /**
     * Generate API key
     * @return String api key
     */
    public function generateApiKey() {
        return md5(uniqid(rand(), true));
    }


    /**
     * check if api key is valid
     * @param $api_key
     * @internal param String $apikey
     * @return boolean $result true/false
     */
    public function isValidApiKey($api_key) {
      $select = $this->dbSELECT( 'uid' , "apikey='".$api_key."'" , $this->accts_table ) ;
      return $select['status'] ;  
    }


    /**
     * Get user id from api key
     * @param $api_key
     * @internal param String $apikey
     * @return String $key
     */
    public function getUserId($api_key) {
      $select = $this->dbSELECT( 'uid' , "apikey='".$api_key."'" , $this->accts_table ) ;
      $key = $select['result'] ;
      return $key['uid'] ;  
    }


    /**
     * Get user phone from User id
     * @param String $user_id 
     * @return String $phone
     */
    public function getUserPhone($user_id){
      $select = $this->dbSELECT( 'phone' , "uid='".$user_id."'" , $this->accts_table ) ;
      $key = $select['result'] ;
      return $key['phone'] ;  
    }


    /**
     * Get subscription data for user from user id
     * @param $phone
     * @internal param String $user_id
     * @return Array $data
     */
 /*   public function getSubscriptionData($phone){
      $return = array() ;
      $this->phone = $phone ;

      $select = $this->dbSELECT( 'type, datestamp' , "phone='".$phone."'" ) ;
      $return['status'] = 'Inactive' ;
      $return['type'] = '0' ;

        if( $select['status'] == true ){

        $type = intval($select['result']['type']) ;
        $sub_date = $select['result']['datestamp'] ;

        if( !$this->subscriptionhasExpired($sub_date , $type) ){
          $return['status'] = 'Active' ;
          $return['type'] = $type.' days' ;
        }
      }
      return $return ;
    }*/


    public function subscriptionData($phone){
        // Get active status , last subscription date, subscription type , expiry date
        $return = array() ;
        $this->phone = $phone ;

        $select = $this->dbSELECT( 'type, datestamp , expires' , "phone='".$phone."'" ) ;
        $return['status'] = 0 ;
        $return['type'] = null ;
        $return['last'] = null ;
        $return['expires'] = null ;

        if( $select['status'] == true ){

            $type = intval($select['result']['type']) ;
            $sub_date = $select['result']['datestamp'] ;

            if( !$this->subscriptionhasExpired($sub_date , $type) ){
                $return['status'] = 1 ;
                $return['type'] = $type.' days' ;
                $return['last'] = $this->formatDate($sub_date) ;
                $return['expires'] = $this->formatDate( $select['result']['expires']  ) ;
            }
        }
        return $return ;

    }

    /**
     * Format a date to readable form
     * @param $db
     * @return string
     */
    private function formatDate($db){
        $timestamp = strtotime($db);
        $date = date("F j, Y", $timestamp);
        return $date ;
    }


  /**
     * Create xml representation of subscription data
     * @param Array $array 
     * @return String $xml returns xml data
     */
   /* public function formatSubScriptionXML($array){
      $status = $array['status'] ;
      $type = $array['type'] ;
      $XML = "<?xml version=\"1.0\" encoding=\"utf-8\" ?> \r\n" ;
      $XML .= "<subscription> \r\n" ;
      $XML .= "<status>".$status."</status>  \r\n" ;
      $XML .= "<type>".$type."</type>  \r\n" ;
      $XML .= "</subscription> " ;
      return $XML ;
  }*/




}

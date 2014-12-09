<?php
/**
 * Google Cloud messenger class
 * Handles all cloud messenger stuff
 */

class gcm {

    private $project_id = "divine-mercy" ;
    private $project_number = 172649105065 ;
    private $api_key = "AIzaSyBNX2IG96Jcttb0o_Z-1FpodlIJG0rNnbQ" ;
    private $send_server = "https://android.googleapis.com/gcm/send" ;

    /*
     * Method to send message to GCM server
     * which in turn will broadcast to all devices
     * @param Array $message array of message
     * @param Array $reg_id array of registration id of devices
     * @return String $result CURL status message ;
     */
    public function sendBroadcast($message,$reg_id){
        $fields=array
        (
            'registration_ids' => $reg_id,
            'data'=> $message
        );

        $headers=array
        (
            'Authorization: key='. $this->$api_key . "'",
            'Content-Type: application/json'
        );

        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$this->send_server);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));
        $result=curl_exec($ch);
        curl_close($ch);
        return $result ;
    }

} 
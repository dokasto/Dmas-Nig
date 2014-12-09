<?php
/**
 * Created by PhpStorm.
 * User: ud
 * Date: 10/15/14
 * Time: 6:39 PM
 */

class appServer extends database{

    private $testimony_tbl = 'dm_testimonies' ;
    private $gallery_path = '../../../_app/gallery/' ;
    private $downloads_tbl = 'dm_app_downloads' ;
    private $resources_tbl = 'dm_app_resources' ;
    private $ticker_tbl = 'dm_app_ticker' ;


    /**
     *  Get scrolling texts
     * @return array $array and array of all scrolling text
     */
    public function getScrollingText(){
        $q = $this->query('SELECT tid,message FROM ' . $this->ticker_tbl . ' ORDER BY tid ASC') ;
        $array = array() ;
        while ( $row = $q->fetch_assoc() ) {
            $array[] = $row['message'] ;
        }
        return $array  ;
    }


    /**
     * @internal param $counter
     * @return array
     */
    public function fetchResources(){
        $data = array() ;
        $qry = $this->query("SELECT * FROM {$this->resources_tbl} ") ;
        while( $r = $qry->fetch_assoc() ){
            if($r){
                $data[] = array( "title" => $r['title'] , "price" => $r['price'], "link" => $r['link'], "picture" => $r['picture'] ) ;
            }
        }
        return $data ;
    }


    /**
     * @internal param $counter
     * @return array
     */
    public function fetchDownloads(){
        $data = array() ;
        $qry = $this->query("SELECT file,title FROM {$this->downloads_tbl}") ;
        while( $r = $qry->fetch_assoc() ){
            if($r){
                $data[] = array( "file" => $r['file'] , "title" => $r['title'] ) ;
            }
        }
        return $data ;
    }


    /**
     * @return array
     */
    public function fetchGallery(){
        $scanned_directory = array_diff(scandir($this->gallery_path), array('..', '.'));
        $array = array() ;
        foreach($scanned_directory as $value){
            $array[] = $value ;
        }
        return $array ;
    }

    /**
     * Fetch testimony from the server
     */
    public function fetchTestimonies(){
        $accounts = new accounts();
        $data = array() ;
        $i = 0 ;
        $qry = $this->query("SELECT testimony_id,uid,message,time FROM {$this->testimony_tbl} ") ;
        while( $r = $qry->fetch_assoc() ){
            if($r){
                $q = $this->dbSELECT('name',"uid='".$r['uid']."'",$accounts->accts_table) ;
                $name = $q['result']['name'] ;
                $data[] = array( "id" => $i , "name" => $name , "message" => $r['message'] , "time" => $this->readableTime($r['time'] )) ;
                $i++;
            }
        }
        return $data ;
    }

    /**
     * Add a new testimony
     */
    public function postNewTestimony($message,$uid){
        $set = "uid='".$uid."' , message='".$message."'";
        $insert = $this->dbINSERT($set,$this->testimony_tbl) ;
        return $insert ;
    }

    /*
     * Get time ago
     */
    private function readableTime($date,$granularity=1) {
        $date = strtotime($date);
        $difference = time() - $date;
        $retval = '' ;
        $periods = array('decade' => 315360000,
            'year' => 31536000,
            'month' => 2628000,
            'week' => 604800,
            'day' => 86400,
            'hour' => 3600,
            'minute' => 60,
            'second' => 1);
        if ($difference < 5) { // less than 5 seconds ago, let's say "just now"
            $retval = "just now";
            return $retval;
        } else {
            foreach ($periods as $key => $value) {
                if ($difference >= $value) {
                    $time = floor($difference/$value);
                    $difference %= $value;
                    $retval .= ($retval ? ' ' : '').$time.' ';
                    $retval .= (($time > 1) ? $key.'s' : $key);
                    $granularity--;
                }
                if ($granularity == '0') { break; }
            }
            return $retval.' ago';
        }
    }




} 
<?php
/// include global configuration

function curlUsingGet($url, $data) {

    if(empty($url) OR empty($data))
    {
    return 'Error: invalid Url or Data';
    }

    //url-ify the data for the get  : Actually create datastring
    $fields_string = '';

    foreach($data as $key=>$value){
    $fields_string[]=$key.'='.urlencode($value).'&amp;'; }
    $urlStringData = $url.'?'.implode('&amp;',$fields_string);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10); # timeout after 10 seconds, you can increase it
   curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
    curl_setopt($ch, CURLOPT_URL, $urlStringData ); #set the url and get string together

    $return = curl_exec($ch);
    curl_close($ch);


    return $return;
}

/*
function curlUsingPost($url, $data)
{

    if(empty($url) OR empty($data))
    {
        return 'Error: invalid Url or Data';
    }

    
    //url-ify the data for the POST
    $fields_string = '';
    foreach($data as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
    $fields_string = rtrim($fields_string,'&');


    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST,count($data));
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10); # timeout after 10 seconds, you can increase it
   //curl_setopt($ch,CURLOPT_HEADER,false);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);  # Set curl to return the data instead of printing it to the browser.
   curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); # Some server may refuse your request if you dont pass user agent

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);
    return $result;
}
*/


function SendEmail($email,$subject,$message,$from){
// compose headers
$mailheaders = "MIME-Version:1.0\r\n";
$mailheaders .= "Content-type:text/html; charset=ISO-8859-1\r\n";
$mailheaders .= "From: $from \r\n";
$mailheaders .= "Reply-To: $from \r\n";
// send message
if(@mail($email,$subject,$message,$mailheaders)){
$status = true ;
}
else{
$status = false ;
}
return $status ;
}
            
		   
	// DISPLAYS COMMENT POST TIME AS "1 year, 1 week ago" or "5 minutes, 7 seconds ago"
    function time_ago($date,$granularity=1) {
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


// function to generate random numbers
function get_random(){
/// get unique code 
//To Pull 8 Unique Random Values Out Of AlphaNumeric

//removed number 0, capital o, number 1 and small L
//Total: keys = 32, elements = 33
$characters = array(
"A","B","C","D","E","F","G","H","J","K","L","M",
"N","P","Q","R","S","T","U","V","W","X","Y","Z",
"1","2","3","4","5","6","7","8","9","0");

//make an "empty container" or array for our keys
$keys = array();

//first count of $keys is empty so "1", remaining count is 1-7 = total 8 times
while(count($keys) < 5) {
    //"0" because we use this to FIND ARRAY KEYS which has a 0 value
    //"-1" because were only concerned of number of keys which is 32 not 33
    //count($characters) = 33
    $x = mt_rand(0, count($characters));
    if(!in_array($x, $keys)) {
       $keys[] = $x ;
    }
}
@$random_chars = '' ;
foreach($keys as $key){
   @$random_chars .= $characters[$key];
}
return $random_chars;
}

function cleanInput($input) {
 
  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
  );
 
    $output = preg_replace($search, '', $input);
    return $output;
  }
  
  function Js($code){
  return  "$(function(){ ".$code." });" ;
  }

function sanitize($input) {
$link = mysqli_connect("localhost", "root", "", "dmasnigc_data");

    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    }
    else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $input  = cleanInput($input);
        $output = mysqli_real_escape_string($link,$input);
    }
    return $output;
}

?>
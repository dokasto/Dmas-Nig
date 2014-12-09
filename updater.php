<?php

  function subscriptionhasExpired($sub_date , $type){
    $expired = '' ;
    $now = time() ;
    $dStart = strtotime($sub_date) ;
    $diff = $now - $dStart ;
    $days = intval (ceil( $diff/(60*60*24) )  );
    $type = intval($type) ;

    if( $days > $type ){
      $expired = true ;
    }else{
      $expired = false ;
    }
    return $expired ;
  }


      $status = 'MYSTAT' ;
      $type = 'MYTYPE' ;
      $XML = "<?xml version=\"1.0\" encoding=\"utf-8\" ?> \r\n" ;
      $XML .= "<subscription> \r\n" ;
      $XML .= "<status>".$status."</status>  \r\n" ;
      $XML .= "<type>".$type."</type>  \r\n" ;
      $XML .= "</subscription> " ;
      echo $XML ;




?>
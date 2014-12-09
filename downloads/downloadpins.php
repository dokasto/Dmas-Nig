<?php 
function __autoload($class) { include_once('../engine/class/class.'.$class.'.php'); } 
if(!isset($_GET['batch'])) die();
include('../engine/functions.php') ;
$_GET = sanitize($_GET) ;  // clean all inputs

$pin = new pin();

$pin->batch = $_GET['batch'] ;
$data = $pin->GetPinCodes('code') ;

// SET PIN BATCH TO PRINTED
$pin->table = $pin->pin_btch_table ;
$pin->dbUPDATE( "printed='YES'" , "batch='".$pin->batch."'" ) ;

 function cleanData(&$str) {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }
  
  // filename for download
  $filename = 'DM-CODES-BATCH-#'.$pin->batch.'.xls';

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
  foreach($data as $row) {
    if(!$flag) {
      // display field/column names as first row
      echo implode("\t", array_keys($row)) . "\r\n";
      $flag = true;
    }
    array_walk($row, 'cleanData');
    echo implode("\t", array_values($row)) . "\r\n";
  }
  exit;
 ?>
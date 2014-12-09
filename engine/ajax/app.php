<?php
error_reporting(E_ALL); 
ini_set( 'display_errors','1');
    ini_set('max_execution_time', 3000); //3000 seconds = 5 minutes
    session_start();
	
	function __autoload($class) { include_once('../class/class.'.$class.'.php'); } 

	 include('../functions.php') ;

	 $action = "" ;	
	
	if(isset($_POST['action'])){
		$_POST = sanitize($_POST) ;  // clean all inputs
		$action = $_POST['action'] ;
	}elseif (isset($_GET['action'])) {
		$_GET = sanitize($_GET) ;  // clean all inputs
		$action = $_GET['action'] ;
	}else{
		die();
	}

	$app = new app() ;
	$js = '' ;
	$status = '' ;

    #### Fetch all gallery images
    if($action == 'fetch_gallery'){
        $images = $app->fetch_gallery() ;
        if(is_array($images) && !empty($images)){
            echo json_encode(array("status"=> true , "images"=> $images)) ;
        }else{
            echo json_encode(array("status"=> false)) ;
        }
        exit() ;
    }

    #### Add a new image to the gallery
    if($action == 'add_to_gallery'){
        $File = $_FILES['file'] ;
        $add = $app->add_image($File) ;
         echo json_encode($add) ;
    }

    ### Remove an image from the gallery
    if($action == 'del_from_gallery'){
        $del = $app->rem_image($_POST['filename']) ;
        echo json_encode(array("status"=>$del)) ;
    }


    #### add a new resource
     if($action == 'add_new_resource'){        
        if( !empty($_POST['title']) && isset($_FILES['file']) ){
            $title = $_POST['title'] ; 
            $link = $_POST['link'] ; 
            $price = $_POST['price'];
            $File = $_FILES['file'] ;
            echo json_encode( $app->add_resource($title,$link,$price,$File) ) ;
        }else{
            echo json_encode(array("status"=> false, "msg" => "some fields are empty")) ;
        }        
        exit();
     }

     #### delete a resource
     if($action == 'delete_resource'){
        $rid = $_POST['rid'] ;
        $delete =  $app->deleteResource($rid) ;
        echo json_encode( $delete ) ;
        exit();
     }

     #### fetch all resources
     if($action == 'fetch_resources'){
        $array =  $app->fetchResources() ;
            echo json_encode($array) ;
        exit() ;
     }

    #### Upload a new file
     if($action == 'upload_downloadable_file'){        
        if( !empty($_POST['title']) && isset($_FILES['file']) ){
            $title = $_POST['title'] ; 
            $File = $_FILES['file'] ;
            echo json_encode( $app->add_uploads($title,$File) ) ;
        }else{
            echo json_encode(array("status"=> false, "msg" => "some fields are empty")) ;
        }        
        exit();
     }

     #### delete an uploaded file
     if($action == 'delete_uploads'){
        $item_id = $_POST['item_id'] ;
        $delete =  $app->deleteUpload($item_id) ;
        echo json_encode( $delete ) ;
        exit();
     }

     #### fetch all uploads
     if($action == 'fetch_uploads'){
        $array =  $app->fetchUploads() ;
            echo json_encode($array) ;
        exit() ;
     }

      #### add a new scrolling text
     if($action == 'add_scrolling_text'){
     	$text = $_POST['text'] ;
     	$add = $app->addScrollingText($text) ;
     	echo json_encode(array("status" => $add )) ;
     	exit();
     }

     #### remove scrolling text
     if($action == 'remove_scrolling_text'){
     	$tid = $_POST['tid'] ;
     	$delete =  $app->delScrollingText($tid) ;
     	echo json_encode( array( "status" => $delete) ) ;
     	exit();
     }

     #### get all scrolling text
     if($action == 'get_scrolling_text'){
     	$text_array =  $app->getScrollingText() ;
     		echo json_encode($text_array) ;
     	exit() ;
     }

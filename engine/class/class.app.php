<?php

/**
*  Handles app functionalities
*/
class App extends database{

	var $table = 'dm_app_ticker' ; 
	var $downloads_table = 'dm_app_downloads' ;
	var $res_table = 'dm_app_resources' ;
	var $max_upload_size = 3145728 ; /// 3MB
	var $uploads_path = "../../_app/uploads/" ;  // path for uploads
	var $res_path = "../../_app/resources/" ;  // path for resources
    var $gallery_path = "../../_app/gallery/" ; // Path for gallery uploads


	/**
	 *  Add New Scrolling Text
	 *  @param String $text 
	 */
	public function addScrollingText($text){
		    $set = "message = '" . $text . "'" ;
	                     $insert = $this->dbINSERT($set) ;
	                     return $insert['status'] ;
	}

	/**
	 *  Delete a scrolling text
	 *  @param int $tid unique id of the text in the database
	 */
	public function delScrollingText($tid){
		$delete = $this->dbDELETE("tid='".$tid."'") ;
		return $delete['status'] ;
	}

    /**
     *  Get scrolling texts
     * @return array $array and array of all scrolling text
     */
	public function getScrollingText(){
		$q = $this->query('SELECT tid,message FROM ' . $this->table . ' ORDER BY tid ASC') ;
		$array = array() ;
		while ( $row = $q->fetch_assoc() ) {
			$array[ $row['tid']  ] = $row['message'] ;
		}
		return $array  ;
	}

	

	/**
	 *  Upload a file 
	 *  @param string $name name of the file
	 *  @param string $tmp temporay name/location/path
	 *  @param string $path where the file is uploaded to 
	 *  @return boolean/string $status if true returns the file new name : else return false ;
	 */
	private function upload( $name , $tmp , $path){
	        list($txt, $ext) = explode(".", $name);
            unset($txt) ;
			$ext = mb_strtolower($ext);   // change all extensgion to lowercase
			$newName = get_random().time().'.'.$ext;// Create Random Name
			if(move_uploaded_file($tmp, $path.$newName)){
                     $status = $newName ;
			  }
			else{
				$status = false ;
			  }			
			return  $status ;
    }

    /**
     * Add a downloadable file
     * @param string $title title of the item
     * @param $FILE
     * @internal param array $file array containing file data to be upload
     * @return array
     */
           public function add_uploads($title,$FILE){ 
           	$result = array("status"=> false) ;
           	// first try to upload the file
           	/*  Check file size */
           	if ( $FILE['size'] > $this->max_upload_size ) {
           		$result['msg'] = "your file exceeds the max file size ". $this->max_upload_size  ;
           	}else{
           		// Now try to upload the file
           		$upload = $this->upload( $FILE['name'] , $FILE['tmp_name'] , $this->uploads_path ) ;
           		if ( $upload == false ) {
           			$result['msg'] = "File upload failed" ;
           		}else{
           			/// Add the Data to the database
           			$set = "title = '" . $title . "' , file='". $upload ."', size='". $FILE['size']  ."'" ;
	                                   $insert = $this->dbINSERT( $set , $this->downloads_table ) ;
	                                   if ( $insert['status'] == true ){
	                                   	$result['status'] = true ;
	                                   }else{
	                                   	$result['msg'] = "a database occured: ". $insert['result'] ;
	                                   	if ( file_exists($this->uploads_path.$upload) ){
	                                   		unlink($this->uploads_path.$upload) ;
	                                   	}
	                                   }
           		}
           	}
                      return $result ;
           }

    /**
	 * Get list of all uploaded files
	 * @return array $array of all downloads
	 */
	public function fetchUploads(){
		$q = $this->query('SELECT item_id,file,title,size FROM ' . $this->downloads_table . ' ORDER BY item_id ASC') ;
		$array = array() ;
		while ( $row = $q->fetch_assoc() ) {
			list($name, $ext) = explode(".", $row['file']  );
            unset($name) ;
			unset($row['file']) ;
			$row['type'] = strtoupper($ext) ;
			$array[] = $row ;
		}
		return $array  ;
	}


    /**
     * Delete an Uploaded file
     * @param int $item_id unique id
     * @return array
     */
	public function deleteUpload($item_id){
		$result = array("status"=>false) ;
		$qry = $this->dbSELECT("file","item_id='$item_id'",$this->downloads_table) ;
		if( $qry['status'] == true ){
			$file = $this->uploads_path . $qry['result']['file'] ;
			$this->dbDELETE("item_id='$item_id'",$this->downloads_table) ; // delete record
			if (file_exists( $file )){
				unlink($file) ;
			}
			$result['status'] = true ;
		}
	      return $result ;
	}


    /**
     * Add a resouce
     * @param string $title title of the item
     * @param $link
     * @param $price
     * @param $FILE
     * @internal param array $file array containing file data to be upload
     * @return array
     */
           public function add_resource($title,$link,$price,$FILE){ 
           	$result = array("status"=> false) ;
           	// first try to upload the file
           	/*  Check file size */
           	if ( $FILE['size'] > $this->max_upload_size ) {
           		$result['msg'] = "your file exceeds the max file size ". $this->max_upload_size  ;
           	}else{
           		// Now try to upload the file
           		$upload = $this->upload( $FILE['name'] , $FILE['tmp_name'] , $this->res_path ) ;
           		if ( $upload == false ) {
           			$result['msg'] = "image upload failed" ;
           		}else{
           			/// Add the Data to the database
           			$set = "title = '" . $title . "' , price='". $price ."', link='". $link ."', picture='".$upload."'" ;
	                                   $insert = $this->dbINSERT( $set , $this->res_table ) ;
	                                   if ( $insert['status'] == true ){
	                                   	$result['status'] = true ;
	                                   }else{
	                                   	$result['msg'] = "a database occured: ". $insert['result'] ;
	                                   	if ( file_exists($this->res_path.$upload) ){
	                                   		unlink($this->res_path.$upload) ;
	                                   	}
	                                   }
           		}
           	}
                      return $result ;
           }

    /**
     * Fetch all resoucurce
     * @return array $array array of all resources
     */
           public function fetchResources(){
		$q = $this->query('SELECT * FROM ' . $this->res_table . ' ORDER BY rid DESC') ;
		$array = array() ;
		while ( $row = $q->fetch_assoc() ) {
			$array[] = $row ;
		}
		return $array  ;
           }

    /**
     * Delete a resource
     * @param int $rid unique id
     * @return array
     */
	public function deleteResource($rid){
		$result = array("status"=>false) ;
		$qry = $this->dbSELECT("picture","rid='$rid'",$this->res_table) ;
		if( $qry['status'] == true ){
			$file = $this->res_path . $qry['result']['picture'] ;
			$this->dbDELETE("rid='$rid'",$this->res_table) ; // delete record
			if (file_exists( $file )){
				unlink($file) ;
			}
			$result['status'] = true ;
		}
	      return $result ;
	}

    /**
     * Upload image to gallery
     * @param $FILE array of the image
     * @return array $result true/false boolean of the action
     */
    public function add_image($FILE){
        $result = array("status"=> false) ;
        // first try to upload the file
        /*  Check file size */
        if ( $FILE['size'] > $this->max_upload_size ) {
            $result['msg'] = "your image exceeds the max file size ". $this->max_upload_size  ;
        }else{
            // Now try to upload the file
            $upload = $this->upload( $FILE['name'] , $FILE['tmp_name'] , $this->gallery_path ) ;
            if ( $upload == false ) {
                $result['msg'] = "image upload failed" ;
            }else{
                $result['status'] = true ;
            }
        }
        return $result ;

    }

    /**
     * Fetch all images in the gallery folder
     * @internal param array $images of images in the folder
     * @return array
     */
    public function fetch_gallery(){
        $scanned_directory = array_diff(scandir($this->gallery_path), array('..', '.'));
        return $scanned_directory ;
    }

    /**
     * Delete a particular image from gallery
     * @param $file string name of the file
     * @return boolean
     */
    public function rem_image($file){
        $image = $this->gallery_path . $file ;
        if( file_exists($image)){
            unlink($image) ;
            return true ;
        }else{
            return false ;
        }
    }

}

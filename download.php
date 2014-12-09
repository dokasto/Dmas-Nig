<?php session_start(); ob_start("ob_gzhandler"); ?> 
<?php define("PAGE", "download") ; ?> 
<?php define("ICON", "download") ; ?> 
<?php define("DETAILS", "downloadable resources and materials") ; ?>
<?php include_once('inc/meta.php'); ?>
<?php function __autoload($class) { include_once('engine/class/class.'.$class.'.php'); }  ?>

     <script src="js/jquery.form.min.js"></script>

	  <style type="text/css">
	  #uploadsTable{
	  	font-size: 14px;
	  }

	  #uploadsTable tbody tr td:last-child{
	  	text-align: center;
	  }
	
	  </style>
	 
	   <?php include_once('inc/header.php'); ?>
		
		<div class="main">

			<div class="row">
				<div class="col-md-8">
					<table id="uploadsTable" class="table table-list-search custab">
                      <legend class="text-primary">Materials </legend>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>type</th>
                            <th>size</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Jerusalem Rosary</td>
                            <td>pdf</td>
                            <td>100mb</td>                            
                            <td>
                            <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> delete</a>
                          </td>
                        </tr>
                    </tbody>
                </table>   
				</div>

	<!--  Next column -->
	<div class="col-md-4" >
       <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="fa fa-bookmark"></span> Upload new</h3>
                </div>
                <div class="panel-body">

                  <form role="form" id="uploadForm">
  <fieldset>
    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" data-alert="please enter title" name="title" id="title" class="form-control req" />
    </div>
    <div class="form-group">
      <label for="file">Upload File</label>
      <input type="file" class="req" data-alert="please select a file" name="file" id="file" />
      <input type="hidden" id="action" name="action" value="" />
    </div>
    <button type="submit" class="btn btn-block btn-primary">Upload</button>
  </fieldset>
</form>                    
                    
                </div>
            </div>
    </div>
			</div>

	</div>

  <script type="text/javascript">

  function loadDownloads(){
      $("#uploadsTable tbody").html("<tr><td align=center>Loading please wait</td></tr> ") ;
    $.get('engine/ajax/app.php', {action: "fetch_uploads"}, function(data) {
      var array = $.parseJSON(data);
      var row = '' ;
      $("#uploadsTable tbody").hide() ;
      $.each(array , function(el,index) {
        var fileSize = index.size / 1000  ;
        row += "<tr id='item" + index.item_id + "' >" ;
        row += "<td>" + index.title + "</td>" ;
        row += "<td>" + index.type + "</td>" ;
        row += "<td>" + fileSize + " <small>KB</small></td>" ;
        row += "<td><a href='" + index.item_id + "' class='remove btn btn-danger btn-xs'><i class='fa fa-trash-o'></i> Remove</a></td>" ;
        row += "</tr>" ;
      });
      $("#uploadsTable tbody").html(row).fadeIn('fast') ;
    });
  }

  $(document).ready(function(){
    loadDownloads() ;

    // Delete Uploads
           $('body').on('click', '.remove' , function(e){
            e.preventDefault() ;
            var ID = $(this).attr('href')  ;
            $.post('engine/ajax/app.php', { action: 'delete_uploads' , item_id: ID }, function(data){
              var json = $.parseJSON(data);
              if( json.status == true ){
              $('#item' + ID).fadeOut('fast', function(){ 
                $(this).remove() ;
              }) ;
            }else{
              alert('delete failed') ;
            }
            });
           })

      var options = { 
        target:        '#output2',   // target element(s) to be updated with server response 
        success:       showResponse ,  // post-submit callback 
        url:       "engine/ajax/app.php"  ,       // override for form's 'action' attribute 
        type:      "post"  ,      // 'get' or 'post', override for form's 'method' attribute 
        dataType:  "json" ,       // 'xml', 'script', or 'json' (expected server response type) 
        resetForm: true        // reset the form after successful submit 
    }; 
 
    $('#uploadForm').submit(function() { 
      $("#action").val("upload_downloadable_file") ;
      $('#uploadForm').find('.btn').attr('disabled', 'disabled');
        $(this).ajaxSubmit(options); 
        // always return false to prevent standard browser submit and page navigation 
        return false; 
    }); 
 
// pre-submit callback 
function checkFields(formData, jqForm, options) {
    //check for empty fields
    $("#uploadForm").find('.req').each(function(){
        var str = $.trim( $(this).val() ) ;
        var msg = $(this).attr('data-alert');
        if ( str.length < 2 ) {
              alert(msg) ;
              $(this).focus() ;
                return false ;
      }else{
        return true ;
      }
      }); 
} 
 
// post-submit callback 
function showResponse(result, statusText, xhr, $form) { 
  $('#uploadForm').find('.btn').removeAttr('disabled') ;
 // var result = $.parseJSON(responseText);
  if( result.status == false ){
    alert( result.msg ) ;
  }else{
    //alert('added') ;
    loadDownloads() ;
  }

} 

});
  </script>
		
		
		
		<?php include_once('inc/footer.php'); ?>
		
        <?php ob_flush(); flush() ; ?>
<?php session_start(); ob_start("ob_gzhandler"); ?> 
<?php define("PAGE", "resources") ; ?> 
<?php define("ICON", "shopping-cart") ; ?> 
<?php define("DETAILS", "Resources and materials") ; ?>
<?php include_once('inc/meta.php'); ?>
<?php function __autoload($class) { include_once('engine/class/class.'.$class.'.php'); }  ?>

<script src="js/jquery.form.min.js"></script>

	  <style type="text/css">
	  #resourcesTable{
	  	font-size: 14px;
	  }

	  #resourcesTable tbody tr td img{
	  	width:30px;
	  	max-height: 30px;
	  }

	  #resourcesTable tbody tr td:last-child{
	  	text-align: center;
	  }

	  #resourcesTable tbody tr td:nth-child(3){
	  	color:green;
	  	font-weight: bold;
	  }

	  #resourcesTable tbody tr td:nth-child(3)::before{
	  	content: " \0020A6 " ;
	  }
	
	  </style>
	 
	   <?php include_once('inc/header.php'); ?>
		
		<div class="main">

			<div class="row">
				<div class="col-md-8">
					<table id="resourcesTable" class="table table-list-search custab">
                      <legend class="text-primary">Resources </legend>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>title</th>
                            <th>price</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td> <img src="img/dmlogo.png"> </td>
                            <td>Jerusalem Rosary</td>
                            <td>100</td>                            
                            <td>
                              <a href="#" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Edit</a>
                            <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Remove</a>
                          </td>
                        </tr>
                          <tr>
                            <td> <img src="img/dmlogo.png"> </td>
                            <td>Jerusalem Rosary</td>
                            <td>100</td>                            
                            <td>
                            <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Remove</a>
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
                        <span class="fa fa-bookmark"></span> New Resource</h3>
                </div>
                <div class="panel-body">

                  <form role="form" id="uploadForm">
  <fieldset>
    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" name="title" data-alert="enter title" id="title" class="form-control req" />
    </div>
    <div class="form-group">
      <label for="price">Price</label>
      <input type="text" name="price" data-alert="enter price" id="price" class="form-control req" />
    </div>
    <div class="form-group">
      <label for="link">Link location</label>
      <input type="text" id="link" name="link" data-alert="enter link to the merchant" class="form-control req" />
      <input type="hidden" name="action" id="action" />
    </div>
    <div class="form-group">
      <label for="file">Upload image</label>
      <input type="file" name="file" class="req" data-alert="select a picture for the item" id="file" />
    </div>
    <button type="submit" class="btn btn-block btn-primary">Add Resource</button>
  </fieldset>
</form>                    
                    
                </div>
            </div>
    </div>
			</div>
			 
		
		

	</div>


  <script type="text/javascript">

  function loadResouces(){
      $("#resourcesTable tbody").html("<tr><td align=center>Loading please wait</td></tr> ") ;
    $.get('engine/ajax/app.php', {action: "fetch_resources"}, function(data) {
      var array = $.parseJSON(data);
      var row = '' ;
      var imagePath = '_app/resources/'
      $("#resourcesTable tbody").hide() ;
      $.each(array , function(el,index) {
        row += "<tr id='item" + index.rid + "' >" ;
        row += "<td> <img src='" + imagePath + index.picture + "' /></td>" ;
        row += "<td>" + index.title + "</td>" ;
        row += "<td>" + index.price + "</td>" ;
        row += "<td><a href='" + index.rid + "' class='remove btn btn-danger btn-xs'><i class='fa fa-trash-o'></i> Remove</a></td>" ;
        row += "</tr>" ;
      });
      $("#resourcesTable tbody").html(row).fadeIn('fast') ;
    });
  }

  $(document).ready(function(){
    loadResouces() ;

    // Delete Uploads
           $('body').on('click', '.remove' , function(e){
            e.preventDefault() ;
            var ID = $(this).attr('href')  ;
            $.post('engine/ajax/app.php', { action: 'delete_resource' , rid: ID }, function(data){
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
 
    $('#uploadForm').submit(function(e){ 
      e.preventDefault() ;

    //  if ( checkFields() == true ) {
         checkFields()  ;
         $("#action").val("add_new_resource") ;
         $('#uploadForm').find('.btn').attr('disabled', 'disabled');
         $(this).ajaxSubmit(options); 
     // }      
        // always return false to prevent standard browser submit and page navigation 
        return false; 
    }); 
 
// pre-submit callback 
function checkFields() {
    //check for empty fields
    $("#uploadForm").find('.req').each(function(){
        var str = $.trim( $(this).val() ) ;
        var msg = $(this).attr('data-alert');
        if ( str.length < 2 ) {
              alert(msg) ;
              $(this).focus() ;
                exit() ;
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
    loadResouces() ;
  }

} 

});
  </script>
		
		
		
		<?php include_once('inc/footer.php'); ?>
		
        <?php ob_flush(); flush() ; ?>
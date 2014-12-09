<?php session_start(); ob_start("ob_gzhandler"); ?> 
<?php define("PAGE", "tickers") ; ?> 
<?php define("ICON", "comment") ; ?> 
<?php define("DETAILS", "Scrolling texts") ; ?>
<?php include_once('inc/meta.php'); ?>
<?php function __autoload($class) { include_once('engine/class/class.'.$class.'.php'); }  ?>

	  <style type="text/css">
	  #resourcesTable{
	  	font-size: 14px;
	  }

	  #resourcesTable tbody tr td:last-child{
	  	text-align: center;
	  }
	
	  </style>
	 
	   <?php include_once('inc/header.php'); ?>
		
		<div class="main">

			<div class="row">
				<div class="col-md-8">
					<table id="resourcesTable" class="table table-list-search custab">
                      <legend class="text-primary">Scrolling messages </legend>
                    <thead>
                        <tr>
                            <th>Text</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>   
				</div>

        <div class="col-md-4" >
       <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="fa fa-comment"></span> New Text</h3>
                </div>
                <div class="panel-body">

                  <form role="form" method="POST" action="engine/ajax/app.php" data-action="add_scrolling_text" class="autoform" >
  <fieldset>
    <div class="form-group">
      <input type="hidden" name="action" value="" />
      <textarea class="form-control req" name="text" data-alert="please enter text" placeholder="scrolling text here" input-lg rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-block btn-primary">Add </button>
  </fieldset>
</form>                    
                    
                </div>
            </div>
    </div>


			</div>

	</div>

  <script type="text/javascript">
  function formSuccess(data){
    if( data.status != true ){
           alert('Unable to add new text') ;
    }
      loadMessage() ;
  }

  function populateTable(tid,text){
    var row = "<tr id='" + tid + "'><td>" +  text + "</td><td><a href='" + tid + "' class='remove btn btn-danger btn-xs'><i class='fa fa-trash-o'></i> Remove</a></td></tr>"
                        $("#resourcesTable tbody").prepend(row) ;
  }

  function loadMessage(){
      $("#resourcesTable tbody").html("<tr><td align=center>Loading please wait</td></tr> ") ;
    $.get('engine/ajax/app.php', {action: "get_scrolling_text"}, function(data) {
      var array = $.parseJSON(data);
      var row = '' ;
      $("#resourcesTable tbody").hide() ;
      $.each(array , function(tid, text) {
        row += "<tr id='li" + tid + "'><td>" +  text + "</td><td><a href='" + tid + "' class='remove btn btn-danger btn-xs'><i class='fa fa-trash-o'></i> Remove</a></td></tr>"
      });
      $("#resourcesTable tbody").html(row).fadeIn('fast') ;
    });
  }

$(document).ready(function(){

             // Delete line of text
           $('body').on('click', '.remove' , function(e){
            e.preventDefault() ;
            var ID = $(this).attr('href')  ;
            $.post('engine/ajax/app.php', { action: 'remove_scrolling_text' , tid: ID }, function(data){
              var json = $.parseJSON(data);
              if( json.status == true ){
              $('#li' + ID).fadeOut('fast', function(){ 
                $(this).remove() ;
              }) ;
            }else{
              alert('delete failed') ;
            }
            });
           })



           loadMessage() ; // Load all messages
});

  </script>
	
	<?php include_once('inc/footer.php'); ?>
		
        <?php ob_flush(); flush() ; ?>
<?php session_start(); ob_start("ob_gzhandler"); ?> 
<?php define("PAGE", "sms") ; ?> 
<?php define("ICON", "envelope") ; ?> 
<?php define("DETAILS", "add, edit and remove text messages.") ; ?> 
<?php include_once('inc/meta.php'); ?>
<?php function __autoload($class) { include_once('engine/class/class.'.$class.'.php'); }  ?>
	  <style type="text/css">	 
	 .smsPanel{
	 position:relative;
	 width:48%;
	 float:left;
	 min-height:200px;
	 }
	 
	 .smsPanel .panel-body{
	 font-size:20px !important;
	 }
	 
	 
	 .Newsms{
	 position:relative;
	 width:48%;
	 float:right;
	 min-height:200px;
	 background-color:;
	 font-size:17px;
	 }
	 
	 .Newsms .nav .active{
	 width:100%;
	 }
	 
	 .Newsms .nav .active a{
	 border-radius:0;
	 }
	 
	 .Newsms textarea{
	 font-size:20px;
	 }
	 
	 date{
	 float:right;
	 font-size:10px;
	 }
	 
	 .panelHolder{
	 min-height:300px;
	 }
	 
	 #prev , #next{
	 display:none;
	 }
	 
	 #chooseDay{
	 padding:5px 10px;
	 text-transform:uppercase;
	 margin-bottom:15px;
	 width:100%;
	 padding-left:20px;
	 }
	
	  </style>
	 
	   <?php include_once('inc/header.php'); ?>
		
		<div class="main">
		
		<div class="smsPanel">
		<alert></alert>
			<div class="pagination">
			<a class="btn btn-info btn-square" id="prev" href="#">
               <i class="fa fa-chevron-circle-left fa-lg"></i> Previous</a>
			   &nbsp;&nbsp;&nbsp;&nbsp;
			   <a class="btn btn-default btn-square" href="#">
               <i class="fa fa-spinner fa-lg" id="loading"></i></a>
			   &nbsp;&nbsp;&nbsp;&nbsp;
			   <a class="btn btn-info btn-square" id="next" href="#">Next 
			   <i class="fa fa-chevron-circle-right fa-lg"></i></a>
			</div>
						<div class="panelHolder">
						<div class="panel-group panel-group-square" id="accordion">
						
						</div>
						</div>
		
		</div>
		
		<div class="Newsms">
		<alert></alert>
						<ul class="nav nav-pills nav-group-squar">
							<li class="active"><a href="#"><i class="fa fa-edit"></i> Write New Text Message 
							<span class="label label-danger label-square counter">0</span></a></li>
						</ul>
    				<div class="bf-example">
	<textarea class="form-control form-control-square" id="txtmsg" placeholder="enter text message here" rows="5" data-alert="text message field is empty !" spellcheck="true" ></textarea>
	<br />
	
	<select id="chooseDay" data-alert="you haven't selected a day for this message.">
	<option value="0" selected >Select Day</option>
	<?php
	$days = array('monday','tuesday','wednesday','thursday','friday','saturday');
	foreach($days as $value){
	echo "<option value='".$value."'>".$value."</option>" ;
	}
	?>
	</select>
	
	<br />
  <button id="button" class="btn btn-default btn-block btn-square" >
  <i class="fa fa-plus-square"></i> &nbsp;&nbsp;Add Text Message</button>
				    </div>
				
		
		</div>
		
		<div class="clear"></div>
		</div>
		<script type="text/javascript">
		maxDisplay = 10 ;
		
		function DeleteSuccess(mid){
		var panel = $( '#panel-' + mid ) ;
		panel.fadeOut('fast', function(){
		$(this).remove();
		LoadTxtMsg( localStorage['LoadCount'] ) ;
		});
		}
		
		function DeleteError(error){
		var div = $('.smsPanel alert') ;
		div.addClass('fa').addClass('error').html('error occured: ' + error ).show();
		}
		
		$('body').on('click', '.delete' , function(e){
		e.preventDefault();
		var verify = confirm('Are sure you want to delete this text message ? ');
		if( verify == true ){
		$('#loading').addClass('fa-spin') ;
		var mid = $(this).attr('data-mid');
        $.post( 'engine/ajax/sms.php' , { mid : mid , action : 'DeleteMsg'  } , function(response){
		$('#loading').removeClass('fa-spin') ;
	    eval(response);
	    });
        }
		});
		
		$('#loading').on('click',function(e){
		e.preventDefault();
		});
		
		$('#prev').on('click',function(e){
		e.preventDefault();
		var count = Number(localStorage['LoadCount']) ;
		count-- ;
		LoadTxtMsg(count);
		});
		
		$('#next').on('click',function(e){
		e.preventDefault();
		var count = Number(localStorage['LoadCount']) ;
		count++ ;
		LoadTxtMsg(count);
		});
		
		$(document).ready(function(){
		localStorage['LoadCount'] = 0 ;
		LoadTxtMsg( localStorage['LoadCount'] ) ;
		});
		
		function DisplayMsgs(count,textmessages,amount){
		if( amount == 0 ){
		$('#next').hide();
		}
		else{
		localStorage['LoadCount'] = count ; // increment counter on localstorage
		$('#accordion').html(textmessages);
		// If result is less than standard
		if( amount < maxDisplay ){
		$('#next').hide();
		}
		else{
		if( localStorage['LoadCount'] == 0 ){
		$('#prev').show();
		}
		$('#next').show();
		}
		}
		
		}
		
		function LoadTxtMsg(count){
		      var LoadData = { 'count' : count , action : 'LoadTextMessages' } ;
			  $('#loading').addClass('fa-spin') ;
		      $.ajax({
			  url: 'engine/ajax/sms.php',
			  data: LoadData ,
			  type: 'POST' ,
			  success: function(returnedData){
			  $('#loading').removeClass('fa-spin') ;
			  eval(returnedData);
			  },
			  error: function ( jqXHR, exception ) {
			  $('#loading').removeClass('fa-spin') ;
			  $('.smsPanel alert').removeAttr('class').addClass('error').html( Ajax_Error(jqXHR) ).show();
  			  }
			  });
		}
		
	
		
  $('#txtmsg').on('keyup', function(e){
  var count = $(this).val().length;
  // write text to counter
  $('.counter').html(count);
  });
		
		/// ADD NEW MESSAGE HANDLER
		$('#button').on('click', function(e){
		e.preventDefault();
		var text = $.trim($('#txtmsg').val()) ;
		var day = $.trim($('#chooseDay').val()) ;
		if( text.length == 0 ){
		$('.Newsms alert').addClass('fa').addClass('notice').html( $('#txtmsg').attr('data-alert') ).show();
	     exit();
		}
		else if( day == 0){
		$('.Newsms alert').addClass('fa').addClass('notice').html( $('#chooseDay').attr('data-alert') ).show();
	     exit();
		}
		
		var msgData = { text : text , day : day , action : 'AddTextMsg' } ;
		$('#button i').removeClass('fa-sign-in').addClass('fa-spinner').addClass('fa-spin');
	    $('#button').attr('disabled','disabled');
		
	          $.ajax({
			  url: 'engine/ajax/sms.php',
			  data: msgData ,
			  success: function(returnedData){
			  $('#button i').addClass('fa-sign-in').removeClass('fa-spinner').removeClass('fa-spin');
			  $('#button').removeAttr('disabled');
			  eval(returnedData);
			  LoadTxtMsg( localStorage['LoadCount'] ) ;
			  },
			  error: function ( jqXHR, exception ) {
			  $('#button').removeAttr('disabled');
			  $('alert').removeAttr('class').addClass('error').html( Ajax_Error(jqXHR) ).show();
  			  }
			  });
		});
		
		
		function PostSuccess(){
		var message = 'Text message has been added succesfully' ;
		$('.Newsms alert').removeAttr('class').addClass('fa').addClass('success').html(message).fadeIn('fast', function(){
	    $('#txtmsg').val('');
	    $('#chooseDay').val(0);
	    });
		}
		
		function PostFailed(msg){
		var message = 'Text message posting failed ! '.msg  ;
		$('.Newsms alert').removeAttr('class').addClass('fa').addClass('error').html(message).fadeIn('fast');
		}
		
		function PostDuplicate(){
		var message = 'This text message is a duplicate' ;
		$('.Newsms alert').removeAttr('class').addClass('fa').addClass('notice').html(message).fadeIn('fast');
		}
		
		
		
		</script>
		<?php include_once('inc/footer.php'); ?>
		
        <?php ob_flush(); flush() ; ?>
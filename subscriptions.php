<?php session_start(); ob_start("ob_gzhandler"); ?> 
<?php define("PAGE", "subscriptions") ; ?> 
<?php define("ICON", "random") ; ?> 
<?php define("DETAILS", "Manage subcribers") ; ?>
<?php include_once('inc/meta.php'); ?>
<?php function __autoload($class) { include_once('engine/class/class.'.$class.'.php'); }  ?>
	  <style type="text/css">
	 .searchdiv{
	 display:block;
	 margin-bottom:20px;
	 }
	 
	 .searchdiv form{
	 width:50%;
	 margin:2px auto;
	 }
	 
	  .table thead{
	  font-size:19px;
	  }
	 
	 .table tr td{
	 font-size:17px;
	 }
	 
	 .loading{
	 display:inline-block;
	 float:left;
	 color:#1b71a0;
	 }
	 
	 .prev{
	 display:none;
	 }
	 
	  </style>
	 
	   <?php include_once('inc/header.php'); ?>
		
		<div class="main container">
		
		     <div class="searchdiv">
					<form class="searchform">
							<h3 class="text-info">Search Subcribers</h3>
							<div class="loading">
								<i class="fa fa-spinner fa-spin fa-3x"></i>
								</div>
								<div class="input-group form-search">	
	<input type="text" data-toggle="tooltip" id="s" data-placement="top" title="enter a phone number to search for" placeholder="enter phone number" class="form-control form-control-square" />
									<span class="input-group-btn btn-group-square">
		<button type="submit" class="btn btn-primary form-control-square" data-type="last">Search</button>
									</span>
								</div>
					</form>
					</div>
					
					<div class="subscribers">
					
					<alert></alert>
					
<table class="table table-background table-hover table-striped">
<thead class="">
<tr>
<th>Phone Number</th>
<th>Type</th>
<th>Subscription Date</th>
<th>Expiration Date</th>
</tr>
</thead>
<tbody class="subscriptionsList">
<tr><td colspan=4 align=center>Loading</td></tr>
</tbody>
</table>							
								<button type="button" class="btn btn-primary prev">
								<i class="fa fa-chevron-left fa-lg"></i>
								</button>
							    &nbsp;&nbsp;&nbsp;&nbsp;
								<button type="button" class="btn btn-primary next">
								<i class="fa fa-chevron-right fa-lg"></i>
								</button>
					
				        
				
		
		</div>
		
		<script type="text/javascript">
		$(document).ready(function(){
		$('.loading').hide();
		localStorage['page'] = 0 ; 
		localStorage['search'] = 0 ; 
		FetchSubscribers(0) ; // Load first page on search 
		
		// Search Action
		$('.searchform').on('submit', function(e){
		e.preventDefault();
		var s = $.trim($('#s').val()) ;
		if( s.length != 0){
		localStorage['search'] = s ; 
		FetchSubscribers(0) ;
		}
		});
		
		// Previous Button
		$('.prev').on('click', function(e){
		e.preventDefault();
		var page = Number(localStorage['page']) ;
		page--;
		FetchSubscribers(page) ;
		});
		
		// Next Button
		$('.next').on('click', function(e){
		e.preventDefault();
		var page = Number(localStorage['page']) ;
		page++;
		FetchSubscribers(page) ;
		});
		
		}); // End of document .ready handler
		
		function FetchSubscribers(page){
		var search = localStorage['search']  ;
		var FetchData = { 'page' : page , 'search' : search , action : 'LoadSubscriptions'} ;
		$('.loading').fadeIn('fast');
		 $.ajax({
			  url: 'engine/ajax/subscriptions.php',
			  data: FetchData ,
			  success: function(returnedData){
			  $('.loading').fadeOut('fast');
			  eval(returnedData);
			  },
			  error: function ( jqXHR, exception ) {
			  $('.loading').fadeOut('fast');
			  $('alert').removeAttr('class').addClass('fa error').html(Ajax_Error(jqXHR)).show();
  			  }
			  });
		
		}
		
		function FetchSucccess(data,count,ListCount){	
		$('.subscriptionsList').html(data);
		localStorage['count'] = count ;
		   if( Number(ListCount) < 10 ){
		       $('.next').hide();
		    }
		    if( localStorage['count'] > 0 ){
			    $('.prev').show();
			}
			
		}
		
		function FetchError(error){
		$('alert').removeAttr('class').addClass('fa error').html(error).show();
		}
		
		</script>
		
		<?php include_once('inc/footer.php'); ?>
		
        <?php ob_flush(); flush() ; ?>
<?php session_start(); ob_start("ob_gzhandler"); ?> 
<?php define("PAGE", "pin") ; ?> 
<?php define("ICON", "credit-card") ; ?> 
<?php define("DETAILS", "generate and manage pins") ; ?> 
<?php include_once('inc/meta.php'); ?>
<?php function __autoload($class) { include_once('engine/class/class.'.$class.'.php'); }  ?>

	  <style type="text/css">
	 
	 .pingenerator{
	 margin-bottom:20px;
	 }
	 
	 .pin-list{
	 float:left;
	 width:32%;
	 height:300px;
	 overflow:auto;
	 padding:6px;
	 margin-right:50px;
	 }
	 
	 .pin-list .list-group{
	 font-size:17px;
	 }
	 
	 .pin-list .list-group a{
	 border-radius:0;
	 }
	 
	 .pininfo{
	 float:left;
	 }
	 
	  .pininfo .list-group{
	   font-size:18px;
	  }
	  
	   .pininfo .list-group span{
	   font-size:17px;
	   }
	   
	   .pininfo .list-group-item {
	 border-radius:0;
	 }
	 
	 #btchLoader{
	 display:none;
	 }
	
	.pinsView{
	width:50%;
	margin:3px auto;
	}
	
	.pinsView h4{
	display:block;
	margin:0 0 0 0;
	padding:6px;
	background-color:#f2f2f2;
	font-family:arial;
	text-transform:uppercase !important;
	}
	
	.pinsView h4 span{
	display:inline-block;
	margin-top:9px;
	font-weight:bold;
	font-size:15px;
	margin-left:6px;
	}
	
	.pinsView h4 button{
	float:right;
	margin-right:10px;
	}
	
	.pinsView table{
	margin:2px auto;
	width:100%;
	border:1px solid #f2f2f2;
	}
	
	.pinsView table tbody tr td{
	padding:5px;
	font-size:18px;
	color:#333;
	padding-left:20px;
	}
	
	.pinsView{
	display:none;
	}
	
	.pindisplaytable{
	max-height:400px;
	overflow: auto;
    overflow-x: hidden;
	-ms-overflow-x: hidden;
	}

	.checkPin{
		position:absolute;
		margin-top: -150px;
		left:50%;
		display: inline-block !important;
	}

	.checkPin table{
		visibility: hidden;
	}

	.checkPin table tr td{
		padding:6px;
		font-size: 16px;
		background-color: #f7f7f7;
		border-bottom:1px solid #ddd;
	}
	
	  </style>
	 
	   <?php include_once('inc/header.php'); ?>
		
		<div class="main">
		
		<div class="pinsView">
		<button type="button" id="closeView" class="btn btn-danger btn-square" data-toggle="dropdown"><i class="fa fa-times"></i> close</button>
		
		<br /><br /><br />
		
		<h4>
		<span>Secret Pin Codes</span>
		
		<button type="button" class="btn btn-primary btn-square downloadpins" data-toggle="dropdown"><i class="fa fa-download"></i> Download</button>
		
		<div class="clearfix"></div>
		</h4>
		
		<div class="pindisplaytable">
		<table>
		<thead><tr><td></td><td></td></tr></thead>
		<tbody class="PinCodeList">
		</tbody>
		</table>
		</div>
		
		</div>
		
		<div class="MAINPIN">
		
		<div class="pingenerator">
				    <h4><i class="fa fa-lock fa-lg"></i> Generate Pin</h4>
					<alert></alert>
					<form role="form">
						<div class="row">
							<div class="col-lg-4">
								<div class="input-group input-group-square">
					
						<input type="text" id="amount" placeholder="Number of Pins" class="form-control">
									<div class="input-group-btn">
										<button type="button" id="gnbtn" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">Generate 
										<i class="fa fa-spinner"></i></button>
										<ul class="dropdown-menu pull-right">
						<li><a data-type="30" href="#"  class="generatepin">30 days</a></li>
						<li><a data-type="60" href="#" class="generatepin">60 days</a></li>
						<li><a data-type="90" href="#" class="generatepin">90 days</a></li>
										</ul>
									</div><!-- /btn-group -->
								</div><!-- /input-group -->
							</div><!-- /.col-lg-6 -->
						</div><!-- /.row -->
					</form>	
		</div>


			<div class="checkPin">

			<table>
			<tr><td>Status</td><td>Invalid</td></tr>
			<tr><td>Subscriber</td><td>0809898098</td></tr>
			<tr><td>Subscibed on</td><td>098098098089</td></tr>				
			</table>			


					<form role="form" id="checkPinDetails" action="hellow">
						<div class="row">
							<div class="col-lg-4">
								<div class="input-group input-group-square">
					
						<input type="text" id="PinCodeHere" placeholder="Enter pin here" class="form-control">
									<div class="input-group-btn">
										<button type="submit" id="getpindetails" class="btn btn-default">Check Pin 
										<i class="fa fa-spinner"></i></button>
									</div><!-- /btn-group -->
								</div><!-- /input-group -->
							</div><!-- /.col-lg-6 -->
						</div><!-- /.row -->
					</form>
					</div>	

		
		<hr>
		
		<h4>Generated Pins</h4>
		<div class="pin-list">
						<div class="bf-example" style="max-width: 300px">
							<div class="list-group" id="pinLists">
							<i class="fa fa-spinner fa-spin fa-lg"></i>
							</div>
					</div>
		
		</div>
		
		<div class="pininfo">
		
		
		<h4 class="bf-example-title"><i class="fa fa-info"></i>
		<label class="btchName">Pin Batch Information</label>
		<alert></alert>
		 <i id="btchLoader" class="fa fa-spinner fa-lg fa-spin"></i></h4>
					<div class="bf-example" style="width: 300px;">
						<ul class="list-group" id="batchInfoView">
							
						</ul>
					</div>
				
		
		</div>
		
		<div class="clearfix"></div>
		
		
		</div>
		
		</div>
		
		<script type="text/javascript">
		
		function DownloadPins(batch){
		window.location = 'downloads/downloadpins.php?batch=' + batch  ;
		}
		
		$(document).ready(function(){
		LoadPinList();

		$('.checkPin').on('submit', function(e){
			e.preventDefault();
			var pincode = $.trim($('#PinCodeHere').val());
			if( pincode.length < 3 ){
				alert('Enter a valid pin code');
				$('#PinCodeHere').focus();
			}else{
				$('#getpindetails').attr('disabled','disabled').find('i').addClass('fa-spin') ;
				$.post( 'engine/ajax/pin.php' , { 'pincode' : pincode , 'action' : 'GetPinInfo'  } , 
				 function(response){
	                $('.checkPin').find('table').html(response).css('visibility', 'visible');;
	                $('#getpindetails').removeAttr('disabled').find('i').removeClass('fa-spin') ; 
	                $('#PinCodeHere').val('');
	             });
			}
		});
		
		$('body').on('click', '.deletePins' , function(e){
		e.preventDefault();
		var verify = confirm('are you sure you want to delete this batch ?') ;
		if( verify == true){
		var batch = $(this).attr('data-batch') ;
		$('.pininfo alert').removeAttr('class').addClass('fa info').html('Deleting pin....').show();
		$.post( 'engine/ajax/pin.php' , { 'batch' : batch , action : 'DeletePinBatch'  } , function(response){
	    eval(response);
	    });
		}
		});
		
		$('#closeView').on('click', function(e){
		e.preventDefault();
		$('.pinsView').fadeOut('fast', function(){
		$('.MAINPIN').fadeIn('fast') ;
		$('.PinCodeList').html(' ');
		});
		});
		
		$('body').on('click', '.viewPinbtch' , function(e){
		e.preventDefault();
		var batch = $(this).attr('data-batch');
		$('.MAINPIN').fadeOut('fast', function(){
		$('.pinsView').fadeIn('fast', function(){
		$(this).find('.downloadpins').attr('onClick',"DownloadPins('" + batch + "')") ;
		LoadBatchPinCodes(batch);
		}) ;
		});
		});
		
		$('body').on('click', '#pinLists a' , function(e){
		e.preventDefault();
		var batch = $(this).attr('href');
		var name = $(this).text();
		$('#pinLists a').each(function(){
		$(this).attr('disabled','disabled'); 
		$(this).removeClass('active'); 
		});
		$(this).addClass('active'); 
		LoadBatchInfo(batch,name);
		});
		
		});
		
		function LoadBatchPinCodes(batch){
		$('.PinCodeList').html('<tr><td>Loading....</td></tr>');
		$.post( 'engine/ajax/pin.php' , { 'batch' : batch , action : 'LoadBatchPinCodes'  } , function(response){
		$('.PinCodeList').html(response);
	    });
		}
		
		function PinDeleteSuccess(){
		var div = $('.pininfo alert') ;
		div.addClass('fa').addClass('success').html('Delete was successfull').show();
		$('#batchInfoView').html('');
		$('.btchName').html('');
		LoadPinList();
		}
		
		function PinDeleteError(){
		var div = $('.pininfo alert') ;
		div.addClass('fa').addClass('error').html('Delete was not successfull').show();
		}
		
		function LoadBatchInfo(batch,name){
		$('#btchLoader').show();
		$('.btchName').text(name + ' information' );
		$('#batchInfoView').html('Loading...');
		$.post( 'engine/ajax/pin.php' , { 'batch' : batch , action : 'LoadBatchInfo'  } , function(response){
		$('#btchLoader').hide();
		$('#pinLists a').each(function(){
		$(this).removeAttr('disabled'); 
		});
	    $('#batchInfoView').html(response);
	    });
		}
		
		function LoadPinList(){
		$.post( 'engine/ajax/pin.php' , {  action : 'LoadPinList'  } , function(response){
	    $('#pinLists').html(response);
	    });
		}
		
		$('.generatepin').on('click' , function(e){
		e.preventDefault();
		var amount = $('#amount').val();
		var div = $('.pingenerator alert') ;
		div.removeAttr('class') ;
		if( amount.length == 0){
		div.addClass('fa').addClass('notice').html('enter the amount of pins to generate').show();
		$('#amount').focus() ;
		}
		else if( !isInt(amount) ){
		div.addClass('fa').addClass('notice').html('You must enter the number of pins to generate numericaly. e.g 1 , 40, 500').show();
		$('#amount').focus() ;
		}
		else if( amount > 1000 ){
		div.addClass('fa').addClass('notice').html('You cannot generate more that 1,000 pins at a time.').show();
		exit();
		}
		else{
		var type = Number($(this).attr('data-type')) ;
		$('#gnbtn').attr('disabled','disabled').find('i').addClass('fa-spin') ;
		$.post( 'engine/ajax/pin.php' , { amount : amount , type : type , action : 'GeneratePins'  } , function(response){
		$('#gnbtn').removeAttr('disabled').find('i').removeClass('fa-spin') ;
	    eval(response);
	    });
		}
		
		});
		
		function PinGenerationSuccess(){
		$('#amount').val('') ;
		var div = $('.pingenerator alert') ;
		div.addClass('fa').addClass('success').html('Pins were successfully generated').show ;
		LoadPinList();
		}
		
		function PinGenerationFailure(msg){
		var div = $('.pingenerator alert') ;
		div.addClass('fa').addClass('error').html('Pins generation was not successfull: ' + msg).show();
		}
		
		
		</script>
		
		<?php include_once('inc/footer.php'); ?>
		
        <?php ob_flush(); flush() ; ?>
<?php session_start(); ob_start("ob_gzhandler"); ?> 
<?php define("PAGE", "dashboard") ; ?> 
<?php define("ICON", "desktop") ; ?> 
<?php define("DETAILS", "Overview of features and tools.") ; ?>
<?php include_once('inc/meta.php'); ?>
<?php function __autoload($class) { include_once('engine/class/class.'.$class.'.php'); }  ?>

	  <style type="text/css">
	 .widget-holder{
	 width:100%;
	 margin:3px auto;
	 text-align:center;
	 }
	 
	 .widget{
	 margin-right:20px;
	 }

	 .widget {
	  animation-name: myfirst;
	  animation-duration: 5s;
	   animation-timing-function: linear;
	  animation-delay: 0;
	  animation-iteration-count: infinite;
	  animation-direction: alternate;
	  animation-play-state: running;
	 
	 -webkit-animation-name: myfirst;
	 -webkit-animation-duration: 5s;
	 -webkit-animation-timing-function: linear;
	 -webkit-animation-delay: 0;
	 -webkit-animation-iteration-count: infinite;
	 -webkit-animation-direction: alternate;
	 -webkit-animation-play-state: running;
	 }
	 
	 @keyframes myfirst {
     0%   {box-shadow: 5px 18px 10px #a7a7a7, inset 0 1px 0 #fff;}
     25%  {box-shadow:none ; }
     50%  {box-shadow: 5px 18px 10px #a7a7a7, inset 0 1px 0 #fff;}
    100% {box-shadow:none;}
    }
	 
	 @-webkit-keyframes myfirst {
     0%   {box-shadow: 5px 18px 10px #a7a7a7, inset 0 1px 0 #fff;}
     25%  {box-shadow:none ; }
     50%  {box-shadow: 5px 18px 10px #a7a7a7, inset 0 1px 0 #fff;}
    100% {box-shadow:none;}
    }
	
	  </style>
	 
	   <?php include_once('inc/header.php'); ?>
		
		<div class="main">
		
		<?php
		$stats = new frontend();
        $subscribers = $stats->FetchSubscribers();	
		$messages = $stats->FetchMsgStats();
		$stats->FetchLog();
		$pins = $stats->PinLog();
		?>
		
		<div class="widget-holder">
		<div class="widget widget-green">
		<span class="icon"><i class="fa fa-random fa-5x"></i></span>
		<span class="title"><i>Total Subscribers</i> 
		<label><?php echo $subscribers['total'] ?></label></span>
		<div class="area">
		<div class="list"><i>1 month</i> 
		<span><?php echo $subscribers['one_month'] ?></span></div>
		<div class="list"><i>2 months</i> 
		<span><?php echo $subscribers['two_months'] ?></span></div>
		<div class="list"><i>3 months</i> 
		<span><?php echo $subscribers['three_months'] ?></span></div>
		</div>
		</div>
		
		<div class="widget widget-turquoise">
		<span class="icon"><i class="fa fa-envelope fa-5x"></i></span>
		<span class="title"><i>Total Messages</i> 
		<label><?php echo $messages['total'] ; ?></label></span>
		<div class="area">
		<div class="list"><i>Sent</i> 
		<span><?php echo $messages['sent'] ; ?></span></div>
		<div class="list"><i>Unsent</i> 
		<span><?php echo $messages['unsent'] ; ?></span></div>
		</div>
		</div>
		
		<div class="widget widget-red">
		<span class="icon"><i class="fa fa-credit-card fa-5x"></i></span>
		<span class="title"><i>Generated Pins</i> 
		<label><?php echo $pins['total'] ; ?></label></span>
		<div class="area">
		<div class="list"><i>Valid</i> 
		<span><?php echo $pins['valid'] ; ?></span></div>
		<div class="list"><i>Invalid</i> 
		<span><?php echo $pins['invalid'] ; ?></span></div>
		</div>
		</div>
		
		<div class="widget widget-brown">
		<span class="icon"><i class="fa fa-tasks fa-5x"></i></span>
		<span class="title"><i>Activity Log</i> </span>
		<div class="area">
		<div class="list"><i>Messages Sent</i> 
		<span><?php echo $stats->messagessent ; ?></span></div>
		<div class="list"><i>Pin Batches</i> 
		<span><?php echo $stats->pinsgenerated ; ?></span></div>
		</div>
		</div>
		
		</div>
		
		</div>
		
		<?php include_once('inc/footer.php'); ?>
		
        <?php ob_flush(); flush() ; ?>
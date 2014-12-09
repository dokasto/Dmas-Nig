<?php session_start(); ob_start("ob_gzhandler"); ?> 
<?php define("PAGE", "log") ; ?> 
<?php define("ICON", "tasks") ; ?> 
<?php define("DETAILS", "Log of all activites performed") ; ?> 
<?php include_once('inc/meta.php'); ?>
<?php function __autoload($class) { include_once('engine/class/class.'.$class.'.php'); }  ?>
	  <style type="text/css">
	 .log-info , .log-info span{
	 font-size:18px;
	 }
	
	  </style>
	 
	   <?php include_once('inc/header.php'); ?>
		
		<div class="main">
		<div class="log-info">
		
		<?php
		   $log = new frontend();
		   $log->FetchLog() ;
		?>
		
		
<h4><i class="fa fa-th-list"></i> Activites</h4>
					<div class="bf-example" style="width: 300px;">
						<ul class="list-group">
							<li class="list-group-item"><span class="badge badge-primary badge-square"><?php echo $log->pinsgenerated; ?></span>Sent Text Messages</li>
							<li class="list-group-item"><span class="badge badge-primary badge-square"><?php echo $log->messagessent; ?></span>Pins Generated</li>
						</ul>
					</div>
				
		
		</div>
		
		</div>
		
		<?php include_once('inc/footer.php'); ?>
		
        <?php ob_flush(); flush() ; ?>
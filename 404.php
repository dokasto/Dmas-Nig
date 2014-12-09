<?php session_start(); ob_start("ob_gzhandler"); ?> 
<?php define("PAGE", "404") ; ?> 
<?php define("ICON", "warning") ; ?> 
<?php define("DETAILS", "error") ; ?>
<?php include_once('inc/meta.php'); ?>
<?php function __autoload($class) { include_once('engine/class/class.'.$class.'.php'); }  ?>

	  <style type="text/css">
	   
	   .wrapper ul{
	   display:none;
	   }
	   
	   .main{
	   position:absolute;
	   left:10%;
	   text-align:center;
	   }
	
	  </style>
	  </head>
    <body>
 <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
		
        <div class="wrapper">
		
		<header>
		<h3><img src="img/dmlogo.png" width="28" alt="Logo" /> Divine Mercy Nigeria</h3>
		</header>
		
		
		<div class="top">
		<span><i class="fa fa-<?php echo ICON ; ?> fa-fw"></i> <?php echo PAGE ; ?></span>
		<label><?php echo DETAILS ; ?></label>
		</div>
		
		<section class="wrapper">		
		<div class="main">
		
		<h1>Page Not found :(</h1>
		<h3>Sorry, the requested page was not found !</h3>
		</div>
		
		<?php include_once('inc/footer.php'); ?>
		
        <?php ob_flush(); flush() ; ?>
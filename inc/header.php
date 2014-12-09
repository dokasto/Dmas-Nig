 </head>
    <body>
 <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
		
		<?php
		$user = new user();
		if($user->IsLoggedIn() == false){
		include('notloggedin.html.php');
		exit();
		}
		?>
		

        <div class="wrapper">
		
		<header>
		<h3><img src="img/dmlogo.png" width="28" alt="Logo" /> Divine Mercy Nigeria</h3>
		<ul>
		<li><a href="./settings" class="settings"><i class="fa fa-cogs fa-fw"></i> Settings</a></li>
		<li><a href="./engine/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
		</ul>
		<div class="clear"></div>
		</header>
		
		
		<div class="top">
		<span><i class="fa fa-<?php echo ICON ; ?> fa-fw"></i> <?php echo PAGE ; ?></span>
		<label><?php echo DETAILS ; ?></label>
		</div>
		
		<section class="wrapper">
		
		<nav class="navigation">
		<ul>
		
		<li><a href="./dashboard" class="dashboard">
		<i class="fa fa-desktop fa-2x"></i>
		 dashboard</a>
		</li>
		
		<li><a href="./subscriptions" class="subscriptions">
		<i class="fa fa-random fa-2x"></i>
		 subscriptions</a>
		</li>
		
		<li><a href="./sms" class="sms">
		<i class="fa fa-envelope fa-2x"></i>
		 text messages</a>
		</li>
		
		<li><a href="./pin" class="pin">
		<i class="fa fa-credit-card fa-2x"></i>
		 pin codes</a>
		</li>

		<li><a href="./resources" class="resources">
		<i class="fa fa-shopping-cart fa-2x"></i>
		 resources</a>
		</li>

        <li><a href="./gallery" class="gallery">
        <i class="fa fa-picture-o fa-2x"></i>
            gallery</a>
        </li>

		<li><a href="./download" class="download">
		<i class="fa fa-download fa-2x"></i>
		 downloads</a>
		</li>

		<li><a href="./tickers" class="tickers">
		<i class="fa fa-comment fa-2x"></i>
		tickers</a>
		</li>
		
		<li><a href="./log" class="log">
		<i class="fa fa-tasks fa-2x"></i>
		 Log</a>
		</li>
		
		</ul>
		</nav>		
		</nav>		
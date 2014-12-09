

        <div class="wrapper">
		
		<?php
		$ref = $_SERVER['REQUEST_URI'] ;
        setcookie("ref", $ref, time()+300);
		?>
		
		<div class="NotLoggedIn">
		<i class="fa fa-exclamation-triangle fa-fw"></i>
		You have to <a href="./login">Login</a> to view this page.
		</div>
		
			
		
		<?php include('footer.php') ?>
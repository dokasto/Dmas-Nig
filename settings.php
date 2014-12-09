<?php session_start(); ob_start("ob_gzhandler"); ?> 
<?php define("PAGE", "settings") ; ?> 
<?php define("ICON", "cogs") ; ?> 
<?php define("DETAILS", "Security Settings") ; ?> 
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
		
	
		<div>
		
		<h3><i class="fa fa-globe"></i> Logged in as 
		<label class="username"><?php echo $_SESSION['username'] ; ?></label></h3>
     <alert></alert>
	 <div class="tabbable tabs-left">
                            <ul class="nav nav-tabs">
                  <li class="active"><a class="tab-default" href="#3A" data-toggle="tab">
				  <i class="fa fa-user"></i> Change Username</a></li>
                   <li><a class="tab-default" href="#3B" data-toggle="tab">
				   <i class="fa fa-lock"></i> Change Password</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="3A">
                                    <p class="text-primary">
		                             Enter new username
									<div class="col-lg-4">
								<div class="input-group input-group-square">
									<input type="text" id="username" placeholder="enter new username.." class="form-control">
									<span class="input-group-btn">
										<button class="btn btn-success" id="saveusername" type="button">save</button>
									</span>
								</div><!-- /input-group -->
							</div>
									</p>
                                </div>
                                <div class="tab-pane" id="3B">
                                  
			<div class="col-lg-4">
			<form id="passwordForm" class="form input-group-square">
			<alert></alert>
			<p class="text-primary">Password <br />
		    <input type="password" name="oldpassword" id="oldpassword" placeholder="Password" data-alert="enter password" class="form-control req" /></p>
			<p class="text-primary">New Password <br />
			<input type="password" data-alert="enter new password" id="newpassword" name="newpassword" placeholder="New Password" class="form-control req" /></p>	
			<p class="text-primary">Confirm New Password <br />
			<input type="password" data-alert="confirm new password" id="confirmnewpassword" placeholder="Confirm New Password" class="form-control req" /> </p>
			<input type="hidden" name="action" value="changePassword" />
			<input type="submit" class="btn btn-block btn-success" value="save" />
			</form><!-- input-group -->
			</div>
									
                                </div>
                            </div>
                        </div>


       </div>

				
		
		</div>
		
		</div>
		
		<script type="text/javascript">
		
		$(document).ready(function(){
		
		$('#passwordForm').on('submit',function(e){
		e.preventDefault();
		req = $('#passwordForm .req') ;
        alertDiv = $('#passwordForm alert') ;
	    alertDiv.removeAttr('class').hide();
		req.each(function(){
		var value = $.trim ($(this).val());
		var msg = $(this).attr('data-alert');
		if(value.length == 0){
		alertDiv.addClass('fa notice').html(msg).show();
		$(this).focus();
		exit();
		}
	    });
		
		if ( $.trim($('#newpassword').val()) != $.trim($('#confirmnewpassword').val())  ){
		alertDiv.addClass('fa notice').html('new passwords do not match').show();
		}
		else{
		
		$(':submit').attr('disabled','disabled');
		var DatatoSend = $(this).serialize() ;
		     $.post('engine/ajax/user.php', DatatoSend , function(responseData){
			    $(':submit').removeAttr('disabled');
			     eval(responseData);
			 });
		
		}
		
		});
		
		$('#saveusername').on('click', function(e){
		e.preventDefault();
		var username = $.trim($('#username').val());
		  if( username.length > 3 ){
		  $('alert').removeAttr('class').addClass('fa info').html('saving...').show();
		  $('button').attr('disabled','disabled');
		  var DatatoSend = { 'username' : username , 'action' : 'changeUsername' } ;
		     $.post('engine/ajax/user.php', DatatoSend , function(responseData){
			    $('button').removeAttr('disabled');
			     eval(responseData);
			 });
		  }
		});
		
		});
		
		function PswdChangeSuccess(){
		$('#passwordForm alert').removeAttr('class').addClass('fa success').html('password has been changed').show();
		$('#oldpassword').val('') ;
		$('#newpassword').val('') ;
		$('#confirmnewpassword').val('') ;
		}
		
		function WrongPassword(){
		$('#passwordForm alert').removeAttr('class').addClass('fa error').html('the old password you entered was incorrect !').show();
		}
		
		function PswdChangeError(){
		$('#passwordForm alert').removeAttr('class').addClass('fa error').html('unable to save changes, please try again.').show();
		}
		
		function ChangeUsernameSucces(){
		var username = $.trim($('#username').val());
		$('alert').removeAttr('class').addClass('fa success').html('Username has been changed').show();
		$('.username').html(username);
		$('#username').val('') ;
		}
		
		function ChangeUsernameError(){
		$('alert').removeAttr('class').addClass('fa error').html('unable to save changes, please try again.').show();
		}
		
		</script>
		
		<?php include_once('inc/footer.php'); ?>
		
        <?php ob_flush(); flush() ; ?>
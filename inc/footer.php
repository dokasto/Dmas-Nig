<div class="clear"></div>
		</section>
		
		
		<footer>
		<p>
		&copy; Divine Mercy Nigeria <?php echo date('Y'); ?> 
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Developed by 
		<a href="http://www.udonline.net" style="color:#fff;">UDCreate</a>
		</p>
		</footer>
		
		</div><!-- End of Container -->

        
        <script src="js/bootstrap.min.js"></script>
        <script src="js/plugins.js"></script>
	
		<script type="text/javascript">
		$(document).ready(function(){
		var page = $('.<?php echo PAGE ; ?>') ;  // get current page
		page.addClass('current');
		});
		</script>

		  <script type="text/javascript">
     $(document).ready(function(){

        $('.autoform').on('submit', function(e) {
         e.preventDefault();

         /* Act on the event */
          var form = $(this) ;

        /// Step 1. check for empty fields
        form.find('.req').each(function(){
        var str = $.trim( $(this).val() ) ;
        var msg = $(this).attr('data-alert');
        if ( str.length < 2 ) {
              alert(msg) ;
              $(this).focus() ;
              exit();
      }
      }); 

        /// Step 2. Ensure form is submitted and get all data
        form. find('[name="action"]').val( $(form).attr('data-action') ) ;
        var formData = form.serialize() ;
        var formUrl = $(form).attr('action') ;

        /// Step 3. Send data via ajax to server
         $.ajax({
        url: formUrl ,
        data: formData ,
        dataType: "json" ,
        beforeSend: function (){          
        form.find('.btn').attr('disabled', 'disabled');
        } ,
        success: function(retData){
          formSuccess(retData) ;
        } ,
        error: function ( jqXHR, exception ) {
          alert( Ajax_Error(jqXHR)  ) ;
        },
        complete: function(){
          $(form)[0].reset();
        form.find('.btn').removeAttr('disabled');
        }
        });

     });// End of form submission

});
     </script>
    </body>
</html>
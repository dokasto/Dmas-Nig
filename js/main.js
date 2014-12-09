
    $('alert').hide();

    $(document).ready(function() {
    $.ajaxSetup({
    type: 'POST',
    timeout: 1000000,
    cache: false
    });
	
	});

    //AJAX LOADING EFFECT
    $(document).ajaxStart(function(){
        $('body').prepend('<div class="load"><img src="img/loading.gif"> Working...</div>')
    }).ajaxStop(function() {
        $('.load').remove();
    });
	
	function Ajax_Error(jqXHR){
	       if (jqXHR.status === 0) {
                alerts = 'Not connected Verify Network.' ;
            } else if (jqXHR.status == 404) {
                alerts = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                alerts = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                alerts = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
               alerts = 'Time out error.';
            } else if (exception === 'abort') {
               alerts = 'Ajax request aborted';
            } else {
               alerts = 'Uncaught Error.\n' + jqXHR.responseText ;
            }
			return alerts ;
	}
	
	//Validate interger value input
function isInt(n){
	var reInt = new RegExp(/^-?\d+$/);
	if (!reInt.test(n)) {
		return false;
	}
	return true;
}

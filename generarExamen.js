$(document).ready(function(){
	
	$(function () {
		$('#datetimepicker4').datetimepicker({
        	format: 'L',
        	viewMode: 'years'
    	});
	});

	/*$('#form-generar').submit(function(event) {
    	var form_data = $(this).serialize();
    	//$(this)[0][7].value
    	form_data = form_data.substr(0,106);
    	form_data = form_data+$(this)[0][7].value;
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'generarExamenProcesamiento.php', // the url where we want to POST
            data        : form_data, // our data object
            success: function(respuesta) {
          		
			}
        })
    	event.preventDefault();
    });*/
});
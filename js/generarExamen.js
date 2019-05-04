$(document).ready(function(){
	
    //Función que muestra un selector de fecha empezando por el año
	$(function () {
		$('#datetimepicker4').datetimepicker({
        	format: 'L',
        	viewMode: 'years'
    	});
	});

    //Si no se escoge cuatrimestre al cargar la página, no se podrá generar el fichero pdf
    if($('#cuatrimestre').val() == null) {
        $('#botonGenerar').prop("disabled", true);
    }

    //Si al cambiar de cuatrimestre, el valor es nulo, no se podrá generar el fichero pdf
    $('#cuatrimestre').bind('change click', function () {
        if ($(this).val() != '0' && $(this).val() != null) {
            $('#botonGenerar').prop("disabled", false);
        }
    });

});
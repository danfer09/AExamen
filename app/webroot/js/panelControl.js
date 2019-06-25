$(document).ready(function(){

	/*
	* Listener que captura el evento de click para obtener más info de una petición de registro
	*/
    $('.masInfo').click(function(){
        var funcion = "getPeticion";
        var idPeticion = $(this).attr("idPeticion");
        var fecha = $(this).attr("fechaPeticion");
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : '/panelescontroles/funcionesajaxpanelcontrol', // the url where we want to POST
            data        : 'funcion=' + funcion + '&idPeticion=' + idPeticion, // our data object
            success: function(respuesta) {
                if(respuesta){
                    $("#modalFecha").html('');
                    $("#modalFecha").append('<p>'+fecha+'</p>');
                    $("#modalFecha").append('<hr>');
                    if (respuesta[0]['texto'] == null || respuesta[0]['texto'] == "") {
                        $("#modalFecha").append('<p>Sin texto</p>');
                    } else {
                        $("#modalFecha").append('<p>"'+respuesta[0]['texto']+'"</p>');
                    }
                }
                else{
                    location.reload();
                }

            },
            dataType:"json"
        })
    });

    /*
    * Listener que en función de si se hace click en aceptar o denegar
    * una petición de registro lo hace efectivo mediante una llamada AJAX con POST
    */
    $('.opciones button').click(function(){
        var idPeticion = $(this).attr("idPeticion");
        var aceptar = $(this).attr("aceptar");
        if (aceptar == 0) {
            const mensaje = "¿Está seguro de que desea denegar la petición de registro?";
            if(window.confirm(mensaje)){
                let funcion = "borrarPeticion"
                $.ajax({
                    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url         : '/panelescontroles/funcionesajaxpanelcontrol', // the url where we want to POST
                    data        : 'funcion=' + funcion + '&idPeticion=' + idPeticion, // our data object
                    success: function(respuesta) {
                        if (respuesta) {
                            location.reload();
                        } else {
                            location.reload();
                        }
                    }
                })
                event.preventDefault();
            }
        } else {
            const mensaje = "¿Está seguro de que desea aceptar la petición de registro?";
            if(window.confirm(mensaje)){
                let funcion = "aceptarPeticion"
                $.ajax({
                    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url         : '/panelescontroles/funcionesajaxpanelcontrol', // the url where we want to POST
                    data        : 'funcion=' + funcion + '&idPeticion=' + idPeticion, // our data object
                    success: function(respuesta) {
                        if (respuesta) {
                            location.reload();
                        } else {
                            location.reload();
                        }
                    }
                })
                event.preventDefault();
            }
        }
    });

    /*
    * Listener que captura el evento de click en "reiniciarLog" que provoca que se
    * realice la llamada AJAX con POST para reiniciar el log correctamente
    */
    $('button#reiniciarLog').click(function(){
        const mensaje = "¿Está seguro de que desea REINICIAR el archivo de log? Esta acción no se puede deshacer.";
        if(window.confirm(mensaje)){
            let funcion = "reiniciarLog";
            $.ajax({
                type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url         : '/panelescontroles/funcionesajaxpanelcontrol', // the url where we want to POST
                data        : 'funcion=' + funcion, // our data object
                success: function(respuesta) {
                    if (respuesta) {
                        location.reload();
                    } else {
                        location.reload();
                    }
                }
            })
            event.preventDefault();
        }
    });

    /*
    * Listener que captura el evento de click en "eliminarLog" que provoca que se
    * realice la llamada AJAX con POST para eliminar el log correctamente
    */
    $('button#eliminarLog').click(function(){
        const mensaje = "¿Está seguro de que desea ELIMINAR el archivo de log? Esta acción no se puede deshacer.";
        if(window.confirm(mensaje)){
            let funcion = "eliminarLog";
            $.ajax({
                type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url         : '/panelescontroles/funcionesajaxpanelcontrol', // the url where we want to POST
                data        : 'funcion=' + funcion, // our data object
                success: function(respuesta) {
                    if (respuesta) {
                        location.reload();
                    } else {
                        location.reload();
                    }
                }
            })
            event.preventDefault();
        }
    });



});

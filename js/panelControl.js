$(document).ready(function(){
    $('.masInfo').click(function(){
        var funcion = "getPeticion";
        var idPeticion = $(this).attr("idPeticion");
        var fecha = $(this).attr("fechaPeticion");
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'panelControlProcesamiento.php', // the url where we want to POST
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
                    //alert("Fallo al editar");
                    location.reload();
                }

            },
            dataType:"json"
        })
    });

    $('.opciones button').click(function(){
        var idPeticion = $(this).attr("idPeticion");
        var aceptar = $(this).attr("aceptar");
        if (aceptar == 0) {
            const mensaje = "¿Está seguro de que desea denegar la petición de registro?";
            if(window.confirm(mensaje)){ 
                let funcion = "borrarPeticion"
                $.ajax({
                    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url         : 'panelControlProcesamiento.php', // the url where we want to POST
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
                    url         : 'panelControlProcesamiento.php', // the url where we want to POST
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

    $('button#reiniciarLog').click(function(){
        const mensaje = "¿Está seguro de que desea REINICIAR el archivo de log? Esta acción no se puede deshacer.";
        if(window.confirm(mensaje)){ 
            let funcion = "reiniciarLog";
            $.ajax({
                type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url         : 'panelControlProcesamiento.php', // the url where we want to POST
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

    $('button#eliminarLog').click(function(){
        const mensaje = "¿Está seguro de que desea ELIMINAR el archivo de log? Esta acción no se puede deshacer.";
        if(window.confirm(mensaje)){ 
            let funcion = "eliminarLog";
            $.ajax({
                type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url         : 'panelControlProcesamiento.php', // the url where we want to POST
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
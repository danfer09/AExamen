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
                    if (respuesta[0]['texto'] == null) {
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

});
$(document).ready(function(){
    $('.masInfo').click(function(){
        var funcion = "getPeticion";
        var idPeticion = $(this).attr("idPeticion");
        
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'panelControlProcesamiento.php', // the url where we want to POST
            data        : 'funcion=' + funcion + '&idPeticion=' + idPeticion, // our data object
            success: function(respuesta) {
                if(respuesta){
                    //var date = new Date(respuesta['fecha']);
                    //console.log(date.toISOString());
                    $("#modalFecha").append('<p>'+respuesta['fecha'].toString()+'</p>');

                    if (respuesta['texto'] == null) {
                        $("#modalFecha").append('<p>Sin texto</p>');
                    } else {
                        $("#modalFecha").append('<p>'+respuesta['texto']+'</p>');
                    }
                }
                else{
                    //alert("Fallo al editar");
                    location.reload();
                }

            }
        })
    });

});
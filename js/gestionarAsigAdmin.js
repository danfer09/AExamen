/*Listener que escucha las filas de la tabla y cuando
se pincha en una de ellas redirige a la asignatura
de la fila que se haya clickado*/
$(document).ready(function(){
	$('#tabla_asignaturas td.asigClick').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = href;
        }
    });

	$('.botonCoordinadores').click(function() {
		/*-----------------Abrir modal -------*/
        var idAsig = $(this).attr("idAsig");
        console.log(idAsig);
       	$('#modalCoordinadorAsig').modal('show');

       	/*--------------------añadir los coordinadore al popup-----------------------------*/
        var funcion = "getProfesoresAdmin";
          
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'gestionarAsigAdminProcesamiento.php', // the url where we want to POST
            data        : 'funcion=' + funcion + '&idAsig=' + idAsig, // our data object
            success:function(respuesta){
                if(respuesta){
                    //alert(respuesta);
                    console.log(respuesta);
                    //console.log("llega");
                    $('#boton_aniadir').attr('disabled',false);
                    $('#tableCoordinadores').children('tr,td').remove();
                    //$("#infoTodosProfAdd").hide();
                    //console.log($('#numeradorTema'+$tema).text()+'  '+$('#denominadorTema'+$tema).text());
     
                	for (var i = 0; i< respuesta['profSiCoord'].length; i++) {
                		console.log(respuesta['profSiCoord'][i]);
                		$("#tableCoordinadores").append('<tr><td><input type="checkbox" checked name="profesor" value="'+respuesta['profSiCoord'][i]["id"]+'"></td><td>'+respuesta['profSiCoord'][i]["nombre"]+'</td><td>'+respuesta['profSiCoord'][i]["apellidos"]+'</td><td>'+respuesta['profSiCoord'][i]["email"]+'</td></tr>');
                	}
                	for (var i = 0; i< respuesta['profNoCoord'].length; i++) {
                		console.log(respuesta['profNoCoord'][i]);
                		$("#tableCoordinadores").append('<tr><td><input type="checkbox" name="profesor" value="'+respuesta['profNoCoord'][i]["id"]+'"></td><td>'+respuesta['profNoCoord'][i]["nombre"]+'</td><td>'+respuesta['profNoCoord'][i]["apellidos"]+'</td><td>'+respuesta['profNoCoord'][i]["email"]+'</td></tr>');

                	}

                            //$("#tableCoordinadores").append('<tr><td><input type="radio" name="profesor" value="'+respuesta[i]["id"]+'"></td><td>'+respuesta[i]["nombre"]+'</td><td>'+respuesta[i]["apellidos"]+'</td><td>'+respuesta[i]["email"]+'</td></tr>');
                  
                }
                else{
                    //alert("Fallo al editar");
                    console.log("falla");
                    //location.reload();
                }
            },
            dataType:"json"
        })
        event.preventDefault();
        
    });



});
/*Listener que escucha las filas de la tabla y cuando
se pincha en una de ellas redirige a la asignatura
de la fila que se haya clickado*/
$(document).ready(function(){
    $('#tableAsignaturas').children("tr").children("td").click(function() {
        //alert($(this).attr('class'));
        //alert($(this).attr("href"));
        if($(this).attr('class')!="botonCoordinadores"){
            var href = $(this).attr("href");
            if(href) {
                window.location = href;
            }
        }
    });
    $('#idPrueba').click(function() {
        alert("dedse"+ $('#idPrueba').attr('class'));
        /*var href = $(this).find("a").attr("href");
        if(href) {
            window.location = href;
        }*/
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
                    $("#formAsigCoord").attr("idAsig", idAsig);
                    //alert(respuesta);
                    console.log(respuesta);
                    //console.log("llega");
                    $('#boton_aniadir').attr('disabled',false);
                    $('#tableCoordinadores').children('tr,td').remove();
                    //$("#infoTodosProfAdd").hide();
                    //console.log($('#numeradorTema'+$tema).text()+'  '+$('#denominadorTema'+$tema).text());
                    //$("#tableCoordinadores").append('<from id="formC">');

                	for (var i = 0; i< respuesta['profSiCoord'].length; i++) {
                		console.log(respuesta['profSiCoord'][i]);
                		$("#tableCoordinadores").append('<tr><td><input type="checkbox" checked name="profesor" value="'+respuesta['profSiCoord'][i]["id"]+'"></td><td>'+respuesta['profSiCoord'][i]["nombre"]+'</td><td>'+respuesta['profSiCoord'][i]["apellidos"]+'</td><td>'+respuesta['profSiCoord'][i]["email"]+'</td></tr>');
                	}
                	for (var i = 0; i< respuesta['profNoCoord'].length; i++) {
                		console.log(respuesta['profNoCoord'][i]);
                		$("#tableCoordinadores").append('<tr><td><input type="checkbox" name="profesor" value="'+respuesta['profNoCoord'][i]["id"]+'"></td><td>'+respuesta['profNoCoord'][i]["nombre"]+'</td><td>'+respuesta['profNoCoord'][i]["apellidos"]+'</td><td>'+respuesta['profNoCoord'][i]["email"]+'</td></tr>');

                	}
                    //$("#tableCoordinadores").append('<input type="submit" value="Submit">');
                    //$("#tableCoordinadores").append('</from>');

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


    $( "#formAsigCoord" ).submit(function( event ) {
        let idProfSelect = [];
        let idProfNoSelect = [];
        let contSelect = 0;
        let contNoSelect = 0;
        $(":checkbox").each(function () {
            var ischecked = $(this).is(":checked");
            if (ischecked) {
                idProfSelect[contSelect]= $(this).val();
                contSelect++;
            }
            else if (!ischecked) {
                idProfNoSelect[contNoSelect]= $(this).val();
                contNoSelect++;
            }
        });
        //idProfSelect contiene los id de los profesores seleccionados
        console.log('Id profesores seleccionados');
        console.log(idProfSelect);
        console.log('Id profesores no seleccionados');
        console.log(idProfNoSelect);
        //Obtenemos el id de la asignatura, de un atributo del formulario del modal. Este atributo se lo
        //ponemos en $('.botonCoordinadores').click(function()
        const idAsig = $("#formAsigCoord").attr("idAsig");
        console.log(idAsig);
        //Definimos el nombre de la funcion a la que vamos a llamar en el PHP
        const funcion = 'setCoodinadores';

        var idProfSelectParam = JSON.stringify(idProfSelect);
        var idProfNoSelectParam = JSON.stringify(idProfNoSelect);

        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'gestionarAsigAdminProcesamiento.php', // the url where we want to POST
            data        : 'funcion=' + funcion + '&idProfSelect=' + idProfSelectParam + '&idAsig=' + idAsig +'&idProfNoSelect=' + idProfNoSelectParam, // our data object
            success:function(respuesta){
                if(respuesta){
                    console.log(respuesta);
                    //console.log("llega");  
                    location.reload();
                
                }
                else{
                    console.log("falla");
                    location.reload();
                }
            }
        })

      event.preventDefault();
    });



});
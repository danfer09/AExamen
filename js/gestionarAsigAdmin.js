/*Listener que escucha las filas de la tabla y cuando
se pincha en una de ellas redirige a la asignatura
de la fila que se haya clickado*/
$(document).ready(function(){
    $('#tableAsignaturas').children("tr").children("td").click(function() {
        if($(this).attr('class')!="botonCoordinadores"){
            var href = $(this).attr("href");
            if(href) {
                window.location = href;
            }
        }
    });
    $('#idPrueba').click(function() {
        alert("dedse"+ $('#idPrueba').attr('class'));
    });

	$('.botonCoordinadores').click(function() {
		/*-----------------Abrir modal -------*/
        var idAsig = $(this).attr("idAsig");
       	$('#modalCoordinadorAsig').modal('show');
       	/*--------------------añadir los coordinadore al popup-----------------------------*/
        var funcion = "getProfesoresAdmin";

        $.ajax({
            type        : 'POST',
            url         : 'gestionarAsigAdminProcesamiento.php',
            data        : 'funcion=' + funcion + '&idAsig=' + idAsig,
            success:function(respuesta){
                if(respuesta){
                    $("#formAsigCoord").attr("idAsig", idAsig);
                    console.log(respuesta);
                    $('#boton_aniadir').attr('disabled',false);
                    $('#tableCoordinadores').children('tr,td').remove();
                    let hayProf = false;
                	for (var i = 0; i< respuesta['profSiCoord'].length; i++) {
                    hayProf = true;
                		console.log(respuesta['profSiCoord'][i]);
                		$("#tableCoordinadores").append('<tr><td><input type="checkbox" checked name="profesor" value="'+respuesta['profSiCoord'][i]["id"]+'"></td><td>'+respuesta['profSiCoord'][i]["nombre"]+'</td><td>'+respuesta['profSiCoord'][i]["apellidos"]+'</td><td>'+respuesta['profSiCoord'][i]["email"]+'</td></tr>');
                	}
                	for (var i = 0; i< respuesta['profNoCoord'].length; i++) {
                    hayProf = true;
                		console.log(respuesta['profNoCoord'][i]);
                		$("#tableCoordinadores").append('<tr><td><input type="checkbox" name="profesor" value="'+respuesta['profNoCoord'][i]["id"]+'"></td><td>'+respuesta['profNoCoord'][i]["nombre"]+'</td><td>'+respuesta['profNoCoord'][i]["apellidos"]+'</td><td>'+respuesta['profNoCoord'][i]["email"]+'</td></tr>');
                	}
                  if(!hayProf){
                    $("#message").append('<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>No hay ningún profesor en el sistema</div>';);
                  }
                }
                else{
                    console.log("falla");
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
        const funcion = 'setCoordinadores';

        var idProfSelectParam = JSON.stringify(idProfSelect);
        var idProfNoSelectParam = JSON.stringify(idProfNoSelect);

        $.ajax({
            type        : 'POST',
            url         : 'gestionarAsigAdminProcesamiento.php',
            data        : 'funcion=' + funcion + '&idProfSelect=' + idProfSelectParam + '&idAsig=' + idAsig +'&idProfNoSelect=' + idProfNoSelectParam,
            success:function(respuesta){
                if(respuesta){
                    console.log(respuesta);
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

    $(document).on('click', '#tableCoordinadores input[type="checkbox"]',function() {
        let contSelect = 0;
        let contNoSelect = 0;
        $(":checkbox").each(function () {
            var ischecked = $(this).is(":checked");
            if (ischecked) {
                contSelect++;
            }
            else if (!ischecked) {
                contNoSelect++;
            }
        });
        if (contSelect <= 0) {
            $('#boton_aniadir').attr('disabled',true);
        } else {
            $('#boton_aniadir').attr('disabled',false);
        }
    });


});

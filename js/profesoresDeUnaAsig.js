$(document).ready(function(){
    $('#opciones a').click(function() {
        var id = $(this).attr("id");
        console.log(id);
        if(id == "boton_modalBorrar"){
        	$("#boton_borrar").attr("id_profesor",$(this).attr("idProfesor"));
        	//alert($(this).attr("idExamens"));
        	//alert($("#boton_borrar").attr("id_Examen"));
        	$('#modal_borrarProfesor').modal('show');
        } 
    });

    $()


    $('#form_delete').submit(function(event) {
        var funcion = "borrarProfesorDeAsig";
        var form_data = $(this).serialize();
      $.ajax({
          type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
          url         : 'profesoresDeUnaAsigProcesamiento.php', // the url where we want to POST
          data        : form_data + '&funcion=' + funcion + '&idProfesor=' + $("#boton_borrar").attr("id_profesor") + '&idAsig=' + $("h2").attr("idAsig"), // our data object
          success: function(respuesta) {
                if(respuesta){
                    //alert("Borrada con exito");
                    location.reload();
                }
                else{
                    //alert("Fallo al borrar");
                    location.reload();
                }
             }
      })
        event.preventDefault();

    });


  $('#boton_borrar').click(function() {
    const mensaje = "¿Esta accion no se puede retornar, está seguro de que desea quitar este profesor de la asignatura?";
    if(window.confirm(mensaje)){ 
        $("#form_delete").submit(); 
      }
    return false;
  });



    $(".fa-plus-circle").click(function() {
        $idAsig=$('h2').attr("idAsig");
        var funcion = "getProfesoresFueraAsig";
        let idProfesores = [];
        let i = 0;
        $('#tabla_profesores > tbody > tr #idProfesor').each(function(){
            idProfesores[i] = $(this).text();
            i++;
        });
        
  
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'profesoresDeUnaAsigProcesamiento.php', // the url where we want to POST
            data        : 'funcion=' + funcion + '&idAsig=' + $idAsig + '&idProfesores=' + idProfesores, // our data object
            success:function(respuesta){
                console.log(respuesta);
                if(respuesta){
                    //alert(respuesta);
                    console.log(respuesta);
                    //console.log("llega");
                    $('#boton_aniadir').attr('disabled',false);
                    $('#tableAniadirProfesor').children('tr,td').remove();
                    $("#infoTodosProfAdd").hide();
                    //console.log($('#numeradorTema'+$tema).text()+'  '+$('#denominadorTema'+$tema).text());
                    if(respuesta.length>0){
                        var pinta = false;
                        for (i = 0; i < respuesta.length; i++) {
                            $("#tableAniadirProfesor").append('<tr><td><input type="radio" name="profesor" value="'+respuesta[i]["id"]+'"></td><td>'+respuesta[i]["nombre"]+'</td><td>'+respuesta[i]["apellidos"]+'</td><td>'+respuesta[i]["email"]+'</td></tr>');
                        }
                    }
                    else{
                        $("#infoTodosProfAdd").show();
                        //$("#info_aniadirPreg").text('No hay ninguna pregunta de este tema').addClass('badge badge-pill badge-danger');
                    }
                    $('#modalAniadirProfesor').modal('show');
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

    $("#formAniadirProfesor").submit(function(event) {
    //console.log("entra aniadir");
    $idAsig=$('h2').attr("idAsig");
    var funcion = "aniadirProfesor";
    $('#modalAniadirProfesor').modal('hide');
    $("#modalAniadirProfesor .close").click();
    var form_data = $(this).serialize();
  
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'profesoresDeUnaAsigProcesamiento.php', // the url where we want to POST
            data        : form_data + '&funcion=' + funcion + '&idAsig=' + $idAsig, // our data object
            success:function(respuesta){
            if(respuesta){
                console.log(respuesta);
            }
            else{
                console.log("ha fallado");
            }
        },
        dataType:"json"
        })
      location.reload();
      event.preventDefault();
  });

});
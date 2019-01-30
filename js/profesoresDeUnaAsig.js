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
        //console.log("entra al click");
        console.log($idAsig);
        var funcion = "getProfesoresFueraAsig";
  
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'profesoresDeUnaAsigProcesamiento.php', // the url where we want to POST
            data        : 'funcion=' + funcion + '&idAsig=' + $idAsig, // our data object
            success:function(respuesta){
                if(respuesta){
                    //alert(respuesta);
                    console.log(respuesta);
                    //console.log("llega");
                    $('#boton_aniadir').attr('disabled',false);
                    $('#tableAniadirProfesor').children('tr,td').remove();
                    $('#tableAniadirProfesor').attr("tema", $tema);
                    $("#infoTodosProfAdd").hide();
                    //console.log($('#numeradorTema'+$tema).text()+'  '+$('#denominadorTema'+$tema).text());
                    if(respuesta.length>0){
                        var pinta = false;
                        for (i = 0; i < respuesta.length; i++) {
                            $("#tableAniadirProfesor").append('<tr><td><input type="radio" value="'+respuesta[i]["id"]+'"></td><td>'+respuesta[i]["nombre"]+'</td><td>'+respuesta[i]["apellidos"]+'</td><td>'+respuesta[i]["correo"]+'</td></tr>');
                        }
                        $('#modalAniadirProfesor').modal('show');
                    }
                    else{
                        $("#info_aniadirPreg_vacio").show();
                        //$("#info_aniadirPreg").text('No hay ninguna pregunta de este tema').addClass('badge badge-pill badge-danger');
                    }
                }
                else{
                    //alert("Fallo al editar");
                    console.log("falla");
                    location.reload();
                }
            },
            dataType:"json"
        })
        event.preventDefault();
    });

});
$(document).ready(function(){

    //Muestra el modal de borrar profesor
    $('#opciones a').click(function() {
        var id = $(this).attr("id");
        //console.log(id);
        if(id == "boton_modalBorrar"){
        	$("#boton_borrar").attr("id_profesor",$(this).attr("idProfesor"));
        	$('#modal_borrarProfesor').modal('show');
        }
    });

    $()

    //Formulario del formulario de borrar profesor
    $('#form_delete').submit(function(event) {
        var funcion = "borrarProfesorDeAsig";
        var form_data = $(this).serialize();
      $.ajax({
          type        : 'POST',
          url         : '/profesores/funcionesAjaxProfesores',
          data        : form_data + '&funcion=' + funcion + '&idProfesor=' + $("#boton_borrar").attr("id_profesor") + '&idAsig=' + $("h2").attr("idAsig"),
          success: function(respuesta) {
                if(respuesta){
                    location.reload();
                }
                else{
                    location.reload();
                }
             }
      })
        event.preventDefault();

    });

  //Confirmación antes de borrar al profesor
  $('#boton_borrar').click(function() {
    const mensaje = "¿Esta accion no se puede retornar, está seguro de que desea quitar este profesor de la asignatura?";
    if(window.confirm(mensaje)){
        $("#form_delete").submit();
      }
    return false;
  });



    $(".fa-plus-circle").click(function() {
        var idAsig=$('#titulo').attr("idAsig");
        var funcion = "getProfesoresFueraAsig";
        let idProfesores = [];
        let i = 0;
        console.log($('h1'));
        $('#tabla_profesores > tbody > tr #idProfesor').each(function(){
            idProfesores[i] = $(this).text();
            i++;
        });


        $.ajax({
            type        : 'POST',
            url         : '/profesores/funcionesAjaxProfesores',
            data        : 'funcion=' + funcion + '&idAsig=' + idAsig + '&idProfesores=' + idProfesores,
            success:function(respuesta){
                console.log(respuesta);
                if(respuesta){
                    console.log(respuesta);
                    $('#boton_aniadir').attr('disabled',false);
                    $('#tableAniadirProfesor').children('tr,td').remove();
                    $("#infoTodosProfAdd").hide();
                    if(respuesta.length>0){
                        var pinta = false;
                        for (i = 0; i < respuesta.length; i++) {
                            $("#tableAniadirProfesor").append('<tr><td><input type="radio" name="profesor" value="'+respuesta[i]["id"]+'"></td><td>'+respuesta[i]["nombre"]+'</td><td>'+respuesta[i]["apellidos"]+'</td><td>'+respuesta[i]["email"]+'</td></tr>');
                        }
                    }
                    else{
                        $("#infoTodosProfAdd").show();
                    }
                    $('#modalAniadirProfesor').modal('show');
                }
                else{
                    console.log("falla");
                }
            },
            dataType:"json"
        })
        event.preventDefault();
    });

    //Submit del formulario de añadir profesor
    $("#formAniadirProfesor").submit(function(event) {
    $idAsig=$('h2').attr("idAsig");
    var funcion = "aniadirProfesor";
    $('#modalAniadirProfesor').modal('hide');
    $("#modalAniadirProfesor .close").click();
    var form_data = $(this).serialize();

        $.ajax({
            type        : 'POST',
            url         : '/profesores/funcionesAjaxProfesores',
            data        : form_data + '&funcion=' + funcion + '&idAsig=' + $idAsig,
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

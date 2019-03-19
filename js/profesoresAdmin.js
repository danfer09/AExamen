$(document).ready(function(){
    $('#opciones a').click(function() {
        var id = $(this).attr("id");
        if(id == "boton_modalBorrar"){
        	$("#boton_borrar").attr("id_profesor",$(this).attr("idProfesor"));
        	//alert($(this).attr("idExamens"));
        	//alert($("#boton_borrar").attr("id_Examen"));
        	$('#modal_borrarProfesor').modal('show');
        } else if (id == "boton_modalEditar") {
            $("#boton_editar").attr("id_profesor",$(this).attr("idProfesor"));
            $("#nombre").val($('#nombreProfesor'+$(this).attr("idProfesor")).text());
            $("#apellidos").val($('#apellidosProfesor'+$(this).attr("idProfesor")).text());
            $("#email").val($('#emailProfesor'+$(this).attr("idProfesor")).text());

            $('#modal_editarProfesor').modal('show');
        }
        
    });


    $('#form_delete').submit(function(event) {
        var funcion = "borrarProfesor";
        var form_data = $(this).serialize();
        $.ajax({
          type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
          url         : 'profesoresAdminProcesamiento.php', // the url where we want to POST
          data        : form_data + '&funcion=' + funcion + '&idProfesor=' + $("#boton_borrar").attr("id_profesor"), // our data object
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

    $('#form_mod').submit(function(event) {
        var funcion = "editarProfesor";
        var form_data = $(this).serialize();
        /*var formDataAndFunction = {
            'titulo'              : $('input[name=titulo]').val(),
            'cuerpo'              : $('input[name=cuerpo]').val(),
            'funcion'             : $('input[name=cuerpo]').val(),
            'tema'                : $('input[name=tema]').val()
        };*/
        //$('#myForm').serialize() + "&moredata=" + morevalue
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'profesoresAdminProcesamiento.php', // the url where we want to POST
            data        : form_data + '&funcion=' + funcion + '&idProfesor=' + $("#boton_editar").attr("id_profesor"), // our data object
            success: function(respuesta) {
                if(respuesta){
                    //alert("Editada con exito");
                    location.reload();
                
                }
                else{
                    //alert("Fallo al editar");
                    location.reload();
                }

            }
        })
        event.preventDefault();

    });


  $('#boton_borrar').click(function() {
    const mensaje = "Esta acción no se puede retornar, ¿está seguro de que desea eliminar este profesor?";
    if(window.confirm(mensaje)){ 
        $("#form_delete").submit(); 
      }
    return false;
  });

  $('#boton_editar').click(function() {
    const mensaje = "¿Está seguro de que desea editar este profesor?";
    if(window.confirm(mensaje)){ 
        $("#form_mod").submit(); 
      }
      return false;
  });

});
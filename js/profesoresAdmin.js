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


  $('#boton_borrar').click(function() {
    const mensaje = "¿Esta accion no se puede retornar, está seguro de que desea quitar este profesor de la asignatura?";
    if(window.confirm(mensaje)){ 
        $("#form_delete").submit(); 
      }
    return false;
  });


});
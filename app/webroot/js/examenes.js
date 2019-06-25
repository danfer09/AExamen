$(document).ready(function(){
    // jquery para mostrar los modales de editar y de borrar examen
    $('#opciones a').click(function() {
        var id = $(this).attr("id");
        if(id == "boton_modalEditar") {
        	$("#boton_editar").attr("id_examen",$(this).attr("idExamen"));
        	$('#modal_editarExamen').modal('show');
        }
        else if(id == "boton_modalBorrar"){
        	$("#boton_borrar").attr("id_examen",$(this).attr("idExamen"));
        	$('#modal_borrarExamen').modal('show');
        }

    });

    // Ajax ejecutado cuando queremos borrar un examen
     $('#form_delete').submit(function(event) {
    	var funcion = "borrarExamen";
    	var form_data = $(this).serialize();
      $.ajax({
          type        : 'POST',
          url         : '/examenes/ajaxexamenes',
          data        : form_data + '&funcion=' + funcion + '&id_examen=' + $("#boton_borrar").attr("id_examen"),
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

  //Funcionamiento del boton borrar (papelera roja)
  $('#boton_borrar').click(function() {
    //Pedimos confirmacion antes de borrar el examen
    const mensaje = "¿Esta seguro de que desea borrar este examen?";
    if(window.confirm(mensaje)){
        $("#form_delete").submit();
      }
      return false;
  });

});

function cambiarLinkGenerarExamen(value) {
    if (value) {
      //si seleccionamos una asignatura
      if (value != "-") {
        let siglas=value.split(",")[0];
        let id=value.split(",")[1];
        $("#boton_modalAñadir").removeAttr("hidden");
        $("#boton_modalAñadir").attr('href', null);
        $("#boton_modalAñadir").attr('href', '/crearexamenes/index?asignatura='+siglas+'&idAsignatura='+id);
      } else if (!$("#boton_modalAñadir").attr("hidden")  && value == "-") {
        $("#boton_modalAñadir").attr("hidden", true);
      }
    }
  }

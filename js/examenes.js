$(document).ready(function(){

    $('#opciones a').click(function() {
        var id = $(this).attr("id");
        if(id == "boton_modalEditar") {
        	$("#boton_editar").attr("id_examen",$(this).attr("idExamen"));
        	$('#modal_editarExamen').modal('show');
        }
        else if(id == "boton_modalBorrar"){
        	$("#boton_borrar").attr("id_examen",$(this).attr("idExamen"));
        	//alert($(this).attr("idExamens"));
        	//alert($("#boton_borrar").attr("id_Examen"));
        	$('#modal_borrarExamen').modal('show');
        }
        
    });

    
     $('#form_delete').submit(function(event) {
    	var funcion = "borrarExamen";
    	var form_data = $(this).serialize();
      $.ajax({
          type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
          url         : 'examenesProcesamiento.php', // the url where we want to POST
          data        : form_data + '&funcion=' + funcion + '&id_examen=' + $("#boton_borrar").attr("id_examen"), // our data object
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
    const mensaje = "¿Esta seguro de que desea borrar este examen?";
    if(window.confirm(mensaje)){ 
        $("#form_delete").submit(); 
      }
      return false;
  });

  

  //$("#selGenera").change($("#selGenera").val(), cambiarLinkGenerarExamen);
  
});

function cambiarLinkGenerarExamen(value) {
   
    if (value) {
      //si seleccionamos una asignatura
      if (value != "-") {
        let siglas=value.split(",")[0];
        let id=value.split(",")[1];
        $("#boton_modalAñadir").removeAttr("hidden");
        $("#boton_modalAñadir").attr('href', null);
        $("#boton_modalAñadir").attr('href', 'crearExamen.php?asignatura='+siglas+'&idAsignatura='+id);
      } else if (!$("#boton_modalAñadir").attr("hidden")  && value == "-") {
        $("#boton_modalAñadir").attr("hidden", true);
      }
    }
    
    
    //crearExamen.php?asignatura='.$_GET["asignatura"].'&idAsignatura='.$_GET["idAsignatura"].'
  }
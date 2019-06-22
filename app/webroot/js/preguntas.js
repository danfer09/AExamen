$(document).ready(function(){

    //Mostrar los diversos modales según los seleccionemos
    $('#boton_modalAñadir').click(function(){
    	$("#boton_añadir").attr("class", "btn btn-primary disabled");
	    $("#boton_añadir").attr("disabled", true);
        $('#modal_aniadirPregunta').modal('show');
    });
    $('#opciones a').click(function() {
        var id = $(this).attr("id");
        if(id == "boton_modalEditar") {
        	$("#boton_editar").attr("id_pregunta",$(this).attr("idPreguntas"));
        	$('#modal_editarPregunta').modal('show');
        }
        else if(id == "boton_modalBorrar"){
        	$("#boton_borrar").attr("id_pregunta",$(this).attr("idPreguntas"));
        	$('#modal_borrarPregunta').modal('show');
        }
    });

    //Submit del formulario de modificar
    $('#form_mod').submit(function(event) {
    	var funcion = "editarPregunta";
    	var form_data = $(this).serialize();
        $.ajax({
            type        : 'POST',
            url         : '/preguntas/funcionesajaxpreguntas',
            data        : form_data + '&funcion=' + funcion + '&id_pregunta=' + $("#boton_editar").attr("id_pregunta"),
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


    //Submit del formulario de añadir
    $('#form_add').submit(function(event) {
    	var funcion = "aniadirPregunta";
    	var form_data = $(this).serialize();
        $.ajax({
            type        : 'POST',
            url         : '/preguntas/funcionesajaxpreguntas',
            data        : form_data + '&funcion=' + funcion,
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

    //Submit del formulario de borrar
     $('#form_delete').submit(function(event) {
    	var funcion = "borrarPregunta";
    	var form_data = $(this).serialize();
      $.ajax({
          type        : 'POST',
          url         : '/preguntas/funcionesajaxpreguntas',
          data        : form_data + '&funcion=' + funcion + '&id_pregunta=' + $("#boton_borrar").attr("id_pregunta"),
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


    //Validación de los diversos campos del formulario de añadir una pregunta
    function validarInsert($mensaje){
    	var camposVacios = "Hay campos vacíos, rellénelos";
    	var temaNoNumero = "El tema debe ser un número, ponga un número para continuar";
    	if($("#titulo").val()=="" || $("#cuerpo").val()=="" || $("#tema").val()==""){
    		$("#boton_añadir").attr("class", "btn btn-primary disabled");
	    	$("#boton_añadir").attr("disabled", true);
    		mensaje.show();
	    	mensaje.text(camposVacios).addClass('badge badge-pill badge-danger');
    	}
    	else if(!/^([0-9])*$/.test($("#tema").val())){
    		$("#boton_añadir").attr("class", "btn btn-primary disabled");
	    	$("#boton_añadir").attr("disabled", true);
    		mensaje.show();
	    	mensaje.text(temaNoNumero).addClass('badge badge-pill badge-danger');

    	}
    	else{
    		$("#boton_añadir").attr("class", "btn btn-primary active");
			$("#boton_añadir").attr("disabled", false);
    		mensaje.hide();
    	}
    }
    $("#tema").after("<span id='mensaje'></span><br>");
	var mensaje = $("#mensaje");
	$("#titulo").bind("change keyup input", function(){
		validarInsert(mensaje);
	});
	$("#cuerpo").bind("change keyup input", function(){
		validarInsert(mensaje);
	});
	$("#tema").bind("change keyup input", function(){
		validarInsert(mensaje);
	});


  $('#boton_borrar').click(function() {
    const mensaje = "¿Está seguro de que desea borrar esta pregunta?";
    if(window.confirm(mensaje)){
        $("#form_delete").submit();
      }
      return false;
  });

  $('#boton_editar').click(function() {
    const mensaje = "¿Está seguro de que desea editar esta pregunta?";
    if(window.confirm(mensaje)){
        $("#form_mod").submit();
      }
      return false;
  });
});

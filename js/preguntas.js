$(document).ready(function(){


	//Esto es para que muestre los detalles cuando se pulsa en al fila,pero si se activa, no funcionan los demás botones
	/*$('#tabla_preguntas tr').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = "detallePregunta.php?id="+href;
        }
    });*/
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
        	//alert($(this).attr("idPreguntas"));
        	//alert($("#boton_borrar").attr("id_pregunta"));
        	$('#modal_borrarPregunta').modal('show');

        }
        /*
        else if(id =="boton_modalAñadir"){
   			$("#boton_añadir").attr("class", "btn btn-primary disabled");
	    	$("#boton_añadir").attr("disabled", true);
        	$('#modal_aniadirPregunta').modal('show');
        }*/
    });

    $('#form_mod').submit(function(event) {
    	var funcion = "editarPregunta";
    	var form_data = $(this).serialize();
    	/*var formDataAndFunction = {
            'titulo'              : $('input[name=titulo]').val(),
            'cuerpo'              : $('input[name=cuerpo]').val(),
            'funcion'			  : $('input[name=cuerpo]').val(),
            'tema'                : $('input[name=tema]').val()
        };*/
        //$('#myForm').serialize() + "&moredata=" + morevalue
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'preguntasProcesamiento.php', // the url where we want to POST
            data        : form_data + '&funcion=' + funcion + '&id_pregunta=' + $("#boton_editar").attr("id_pregunta"), // our data object
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

    $('#form_add').submit(function(event) {
    	var funcion = "aniadirPregunta";
    	var form_data = $(this).serialize();
    	/*var formDataAndFunction = {
            'titulo'              : $('input[name=titulo]').val(),
            'cuerpo'              : $('input[name=cuerpo]').val(),
            'funcion'			  : $('input[name=cuerpo]').val(),
            'tema'                : $('input[name=tema]').val()
        };*/
        //$('#myForm').serialize() + "&moredata=" + morevalue
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'preguntasProcesamiento.php', // the url where we want to POST
            data        : form_data + '&funcion=' + funcion, // our data object
            success: function(respuesta) {
          		if(respuesta){
          			//alert("Insertada con exito");
          			location.reload();
          		}
          		else{
          			//alert("fallo al insertar");
          			location.reload();
          		}
			}
        })
    	event.preventDefault();

    });

     $('#form_delete').submit(function(event) {
    	var funcion = "borrarPregunta";
    	var form_data = $(this).serialize();
      $.ajax({
          type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
          url         : 'preguntasProcesamiento.php', // the url where we want to POST
          data        : form_data + '&funcion=' + funcion + '&id_pregunta=' + $("#boton_borrar").attr("id_pregunta"), // our data object
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
	$("#titulo").keyup(function(){
		validarInsert(mensaje);
	});
	$("#cuerpo").keyup(function(){
		validarInsert(mensaje);
	});
	$("#tema").keyup(function(){
		validarInsert(mensaje);
	});


  $('#boton_borrar').click(function() {
    const mensaje = "¿Esta seguro de que desea borrar esta pregunta?";
    if(window.confirm(mensaje)){ 
        $("#form_delete").submit(); 
      }
      return false;
  });

  $('#boton_editar').click(function() {
    const mensaje = "¿Esta seguro de que desea editar esta pregunta?";
    if(window.confirm(mensaje)){ 
        $("#form_mod").submit(); 
      }
      return false;
  });
});
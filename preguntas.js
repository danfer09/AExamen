$(document).ready(function(){
	$('#tabla_preguntas tr').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = "detallePregunta.php?id="+href;
        }
    });

    $('#opciones a').click(function() {
        var clase = $(this).attr("class");
        if(clase == "fas fa-edit") {
        	alert("desea editar?");
        }
        else if(clase == "fas fa-trash-alt"){
        	alert("desea borrar?");
        }
        else if(clase =="fas fa-plus-circle"){
   			$("#boton_añadir").attr("class", "btn btn-primary disabled");
	    	$("#boton_añadir").attr("disabled", true);
        	$('#modal_aniadirPregunta').modal('show');
        }
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
          			alert("insertada con exito");
          			location.reload();
          		}
          		else{
          			alert("fallo al insertar");
          			location.reload();
          		}
			}
        })
    	event.preventDefault();

    });

    function validarInsert($mensaje){
    	var camposVacios = "Hay campos vacios, rellenelos";
    	var temaNoNumero = "Es tema no es un numero, ponga un numero para continuar";
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
});
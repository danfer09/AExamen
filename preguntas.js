$(document).ready(function(){
	$('#tabla_preguntas tr').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = "detallePregunta.php?id="+href;
        }
    });

    $('#opciones a').click(function() {
        var clase = $(this).attr("class");
        alert(clase);
        if(clase == "fas fa-edit") {
        	alert("desea editar?");
        }
        else if(clase == "fas fa-trash-alt"){
        	alert("desea borrar?");
        }
        else if(clase =="fas fa-plus-circle"){
        	//alert($('#modal_aniadirPregunta').attr("funcion"));
        	alert($('h1[name=funcion]').attr("name"));
        	$('#modal_aniadirPregunta').modal('show');
        }
    });
    $('#form_add').submit(function(event) {
    	var funcion = "aniadirPregunta";
    	var formDataAndFunction = {
            'titulo'              : $('input[name=titulo]').val(),
            'cuerpo'              : $('input[name=cuerpo]').val(),
            'funcion'			  : 'aniadirPregunta',
            'tema'                : $('input[name=tema]').val()
        };

        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'preguntasProcesamiento.php', // the url where we want to POST
            data        : formDataAndFunction, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
                        encode          : true,
            success: function(respuesta) {
          		if(respuesta){
          			alert("insertada con exito");
          		}
          		else{
          			alert("fallo al insertar");
          		}
			}
        })

    });
});
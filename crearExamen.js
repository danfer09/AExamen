$(document).ready(function(){

	$('#openNav').click(function() {
		 document.getElementById("mySidenav").style.width = "250px";
		 document.getElementById("overlay").style.display = "block";
	});
	$('#overlay').click(function() {
		document.getElementById("mySidenav").style.width = "0px";
		document.getElementById("overlay").style.display = "none";
	});

	$('#closeNav').click(function() {
		 document.getElementById("mySidenav").style.width = "0px";
		 document.getElementById("overlay").style.display = "none";
	});

	/*$('#boton_aniadirPregunta').click(function() {
		 console.log($(this).attr("tema"));
		 console.log($(this).attr("asignatura"));
	});*/
	$(".fa-plus-circle").click(function() {
		$tema=$(this).attr("tema");
		$idAsignatura=$(this).attr("asignatura");
		console.log("entra al click");
		console.log($tema);
		console.log($idAsignatura);
		var funcion = "getPregAsigTema";
  
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'crearExamenProcesamiento.php', // the url where we want to POST
            data        : 'funcion=' + funcion + '&idAsignatura=' + $idAsignatura + '&tema=' + $tema, // our data object
            success:function(respuesta){
		        if(respuesta){
          			//alert(respuesta);
          			//console.log(respuesta);
          			console.log("llega");
          			for (i = 0; i < respuesta.length; i++) {
					    console.log(respuesta[i]["titulo"]);
					    $("#table_añadirPreguntas").append( '<tr><td>'+respuesta[i]["titulo"]+'</td><td>'+respuesta[i]["cuerpo"]+'</td><td>'+respuesta[i]["tema"]+'</td></tr>');
					}
					//location.reload();
					$('#modal_aniadirPreguntas').modal('show');
          		}
          		else{
          			//alert("Fallo al editar");
          			console.log("falla");
          			location.reload();
          		}
		    },
		    dataType:"json"
        })
    	//event.preventDefault();
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
	//fas fa-plus-circle
});
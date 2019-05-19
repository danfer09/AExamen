$(document).ready(function(){

  $('#botonRestablecer').click(function(){
    location.reload();
  });

	$(document).bind("change keyup mouseup", ".puntosTemaForm, .puntosExamenTotal, .numTemasForm", function() {
		let sumaPuntos = 0;
		$( ".puntosTemaForm" ).each(function() {
		  sumaPuntos += parseInt($( this ).val(), 10);
		});
    console.log(sumaPuntos);
    console.log($( "#maximoPuntos" ).val());
		if($( "#maximoPuntos" ).val() != sumaPuntos){
			$("#botonGuardar").attr("class", "btn btn-primary disabled");
	    $("#botonGuardar").attr("disabled", true);
	    $("#mensajePuntosPorTema").show();
	    $("#mensajePuntosPorTema").text("La cantidad de puntos por tema tiene que coincidir con el total de puntos").addClass('badge badge-pill badge-danger');
		}
		else{
			$("#botonGuardar").attr("class", "btn btn-primary active");
	    $("#botonGuardar").attr("disabled", false);
	    $("#mensajePuntosPorTema").hide();
		}
	});

  //aniade temas de forma dinamica segun al formulario segun se van aniadiendo
  $('.numTemasForm').bind('keyup mouseup',function() {
    let contadorTemas = 0;
    $( ".puntosTemaForm" ).each(function() {
      contadorTemas += 1;
    });
    //aniadimos un tema si el numero de temas se incrementa
    if ($(this).val()>contadorTemas) {
      let dif = $(this).val()-contadorTemas;
      for (var i = 0; i < dif; i++) {
        $('#filaPuntosPorTema').append('<div id="div_tema'+(contadorTemas+1+i)+'" class="form-group col-4">'+
        '<label>Tema '+(contadorTemas+1+i)+':</label>'+
        '<input type="number" class="form-control puntosTemaForm" id="tema'+(contadorTemas+1+i)+'" value="0" min="0">'+
        '</div>');
      }

    }
    //eliminamos un tema si el numero de temas se incrementa
    else if ($(this).val()<contadorTemas) {
      while($(this).val()!=contadorTemas){
        $('#div_tema'+contadorTemas).remove();
        contadorTemas--;
      }
    }
  });

  $('#formParametros').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
      e.preventDefault();
      return false;
    }
  });


	$('#formParametros').submit(function(event) {
        var funcion = "updateParametrosAsig";
        var jsonNuevo = new Object();
        jsonNuevo["numeroTemas"] = $("#numeroTemas").val();
        jsonNuevo["maximoPuntos"] = $("#maximoPuntos").val();
        $( ".puntosTemaForm" ).each(function() {
    		  jsonNuevo[$( this ).attr("id")] = $(this).val();
    		});
        let textoInicial = $("#textoInicialForm").val();
        console.log(textoInicial);
        let espaciado = $(".espaciado:checked").val();
        console.log(espaciado);
        if(espaciado == "pequenio"){
          espaciado = 2;
        }
        else if(espaciado == "medio"){
          espaciado = 10;
        }
        else{
          espaciado = 100;
        }
		    var idAsig = $(".idAsignatura").attr("idAsig");
        console.log(idAsig);
        var form_data = $(this).serialize();
      $.ajax({
          type        : 'POST',
          url         : 'definirParametrosExamProcesamiento.php',
          data        : form_data + '&funcion=' + funcion + '&jsonParametros=' +  JSON.stringify(jsonNuevo) + "&idAsig=" + idAsig + "&espaciado=" + espaciado + "&textoInicial=" + textoInicial, // our data object
          success: function(respuesta) {
                if(respuesta){
                    $('#message').append('<div class="alert alert-success contenidoMensaje"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Parámetros guardados con éxito</div>');
                    $('.contenidoMensaje').fadeOut(3000);
                }
                else{
                  $('#message').append('<div class="alert alert-danger contenidoMensaje"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Los parametros no se han podido guardar correctamente</div>');
                  $('.contenidoMensaje').fadeOut(3000);
                }
             }
      })
        event.preventDefault();

    });


});

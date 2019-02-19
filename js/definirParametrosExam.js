$(document).ready(function(){

  $('#botonRestablecer').click(function(){
    location.reload();
  });

	$(".puntosTemaForm, .puntosExamenTotal, .numTemasForm").bind('change keyup mouseup', function() {
		let sumaPuntos = 0;
		$( ".puntosTemaForm" ).each(function() {
		  //console.log($( this ).val());
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

  $('.numTemasForm').bind('keyup mouseup',function() {
    let contadorTemas = 0;
    $( ".puntosTemaForm" ).each(function() {
      contadorTemas += 1;
    });
    if ($(this).val()>contadorTemas) {
      let dif = $(this).val()-contadorTemas;
      for (var i = 0; i < dif; i++) {
        $('#filaPuntosPorTema').append('<div id="div_tema'+(contadorTemas+1+i)+'" class="form-group col-4">'+
        '<label>Tema '+(contadorTemas+1+i)+':</label>'+
        '<input type="number" class="form-control puntosTemaForm" id="tema'+(contadorTemas+1+i)+'" value="0">'+
        '</div>');
      }
    } else if ($(this).val()<contadorTemas) {
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
          type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
          url         : 'definirParametrosExamProcesamiento.php', // the url where we want to POST
          data        : form_data + '&funcion=' + funcion + '&jsonParametros=' +  JSON.stringify(jsonNuevo) + "&idAsig=" + idAsig + "&espaciado=" + espaciado + "&textoInicial=" + textoInicial, // our data object
          success: function(respuesta) {
                if(respuesta){
                    console.log(respuesta);
                    console.log("hola");
                    location.reload();
                }
                else{
                	console.log("falla");
                    //alert("Fallo al borrar");
                    location.reload();
                }
             }
      })
        event.preventDefault();

    });


});
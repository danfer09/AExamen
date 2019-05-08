function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}
$(document).ready(function(){

	$('#openNav').click(function() {
		 document.getElementById("mySidenav").style.width = "250px";
	});
	$('#closeNav').click(function() {
		 document.getElementById("mySidenav").style.width = "0px";
	});

  /*
  * Carga todas las preguntas de un tema mediante un POST con AJAX y las pinta por pantalla
  */
	$(".fa-plus-circle").click(function() {
		$tema=$(this).attr("tema");
		$idAsignatura=$(this).attr("asignatura");
		var funcion = "getPregAsigTema";

        $.ajax({
            type        : 'POST',
            url         : 'crearExamenProcesamiento.php',
            data        : 'funcion=' + funcion + '&idAsignatura=' + $idAsignatura + '&tema=' + $tema,
            success:function(respuesta){
		        if(respuesta){
                $('#boton_añiadir').attr('disabled',false);
          			$('#table_aniadirPreguntas').children('tr,td').remove();
                $('#table_aniadirPreguntas').attr("tema", $tema);
          			$("#info_aniadirPreg_vacio").hide();
                $("#info_aniadirPreg_limite").hide();
                $("#info_aniadirPreg_todas").hide();
                var preguntas = [];

                $('.preguntaTema'+$tema).each(function( index ) {
                  preguntas[index] = $(this).attr("id");
                });

          			if(respuesta.length>0){
                  var pinta = false;
                  if ($('#numeradorTema'+$tema).text() == $('#denominadorTema'+$tema).text()) {
                    for (i = 0; i < respuesta.length; i++) {
                      if (preguntas.indexOf(respuesta[i]["id"]) == -1) {
                        pinta = true;
                        $("#table_aniadirPreguntas").append('<tr><td><input disabled id="checkbox-'+$tema+'-'+i+'" type="radio" name="preguntas[]" value="'+respuesta[i]["id"]+'"></td><td>'+respuesta[i]["titulo"]+'</td><td>'+respuesta[i]["cuerpo"]+'</td><td>'+respuesta[i]["tema"]+'</td></tr>');
                      }
                    }
                    $('#boton_añiadir').attr('disabled',true);
                    $("#info_aniadirPreg_limite").show();
                  } else {
                    for (i = 0; i < respuesta.length; i++) {
                      if (preguntas.indexOf(respuesta[i]["id"]) == -1) {
                        pinta = true;
                        $("#table_aniadirPreguntas").append('<tr><td><input id="checkbox-'+$tema+'-'+i+'" type="radio" name="preguntas[]" value="'+respuesta[i]["id"]+'"></td><td>'+respuesta[i]["titulo"]+'</td><td>'+respuesta[i]["cuerpo"]+'</td><td>'+respuesta[i]["tema"]+'</td></tr>');
                      }
                    }
                  }
                  if (!pinta) {
                    $("#info_aniadirPreg_todas").show();
                  }
      					}
      					else{
      						$("#info_aniadirPreg_vacio").show();
      					}

      					$('#modal_aniadirPreguntas').modal('show');
          	}
        		else{
        			console.log("Error");
        			location.reload();
        		}
		    },
		    dataType:"json"
        })
    	event.preventDefault();
	});

  /*
  * Añade la pregunta seleccionada al examen mediante llamada POST (AJAX)
  */
  $("#form_aniadirPregunta").submit(function(event) {
    var funcion = "aniadirPreguntas";
    var tema=$('#table_aniadirPreguntas').attr("tema");
    $('#modal_aniadirPreguntas').modal('hide');
    $("#modal_aniadirPreguntas .close").click();
    var form_data = $(this).serialize();

        $.ajax({
            type        : 'POST',
            url         : 'crearExamenProcesamiento.php',
            data        : form_data + '&funcion=' + funcion,
            success:function(respuesta){
            if(respuesta){
                console.log(respuesta);
            }
            else{
                console.log("ha fallado");
            }
        },
        dataType:"json"
        })
				sleep(300);
    		location.reload();

      event.preventDefault();
  });

  /*
  * Guarda el nuevo examen (llamada POST) a no ser que no se haya rellenado el campo de nombre, en cuyo caso recuerda que ha de introducirlo
  */
  $('#guardarNuevoExamen').click(function() {
    let siglas = $('#nombreExamen').attr('siglas');
    var nombreExamen=$('#nombreExamen').val();
    if (!nombreExamen) {
      alert("Por favor, introduzca un nombre de examen");
    } else {
      let funcion = 'guardarExamen';
      $.ajax({
          type        : 'POST',
          url         : 'crearExamenProcesamiento.php',
          data        : 'funcion=' + funcion + '&nombreExamen=' + nombreExamen,
          success:function(respuesta){
            if(respuesta){
                console.log(respuesta);
            }
            else{
                console.log("ha fallado");
            }
        },
      dataType:"json"
      })
			sleep(300);
  		window.location = 'examenes.php?asignatura='+siglas+'&autor=todos&successCreate=true';

      event.preventDefault();
    }
  });



  /*
  * Guarda el examen editado (llamada POST) a no ser que no se haya rellenado el campo de nombre, en cuyo caso recuerda que ha de introducirlo
  */
  $('#guardarModificarExamen').click(function() {
    var nombreExamen=$('#nombreExamen').val();
    let siglas = $('#nombreExamen').attr('siglas');
    if (!nombreExamen) {
      alert("Por favor, introduzca un nombre de examen");
    } else {
      let funcion = 'guardarModificarExamen';
          $.ajax({
              type        : 'POST',
              url         : 'modificarExamenProcesamiento.php',
              data        : 'funcion=' + funcion + '&nombreExamen=' + nombreExamen,
              success:function(respuesta){
                if(respuesta){
                    console.log(respuesta);
                }
                else{
                    console.log("ha fallado");
                }
              },
          dataType:"json"
          })
          window.location = 'examenes.php?asignatura='+siglas+'&autor=todos&successEdit=true';
          event.preventDefault();
    }
  });

  /*
  * Aumenta en +1 los puntos de la pregunta para la que se ha hecho click en su flecha de incremento
  */
  $('[id^=masPuntosPregunta]').click(function() {
    let idFull = $(this).parent().attr('id');
    let idPregunta = idFull.substring(14, 14+(idFull.length-14));
    let puntosPregunta = $(this).parent().find('.puntos').text();
    let str = $(this).parent().parent().parent().parent().attr('id');
    let tema = str.substring(13, 13+(str.length-13));
    let numerador = $('#numeradorTema'+tema).text();
    let denominador = $('#denominadorTema'+tema).text();
    let funcion = "cambiarPuntosPregunta";
    if (Number(numerador)+1 <= Number(denominador)) {
      $(this).parent().find('.puntos').html("<b>"+(Number(puntosPregunta)+1)+"</b>");
      $('#numeradorTema'+tema).text(Number(numerador)+1);
      $('#numeradorTotal').text(Number($('#numeradorTotal').text())+1);
      $.ajax({
          type        : 'POST',
          url         : 'crearExamenProcesamiento.php',
          data        : 'funcion=' + funcion + '&idPregunta=' + idPregunta + '&puntos=' + (Number(puntosPregunta)+1) + '&tema=' + tema,
          success:function(respuesta){
            if(respuesta){
                console.log(respuesta);
            }
            else{
                console.log("ha fallado");
            }

          },
      dataType:"json"
      })
      event.preventDefault();
    }

  });

  /*
  * Disminuye en -1 los puntos de la pregunta para la que se ha hecho click en su flecha de incremento
  */
  $('[id^=menosPuntosPregunta]').click(function() {
    let idFull = $(this).parent().attr('id');
    let idPregunta = idFull.substring(14, 14+(idFull.length-14));
    let puntosPregunta = $(this).parent().find('.puntos').text();
    let str = $(this).parent().parent().parent().parent().attr('id');
    let tema = str.substring(13, 13+(str.length-13));
    let numerador = $('#numeradorTema'+tema).text();
    let denominador = $('#denominadorTema'+tema).text();
    let funcion = "cambiarPuntosPregunta";
    if (Number(puntosPregunta)-1 >= 1) {
      $(this).parent().find('.puntos').html("<b>"+(Number(puntosPregunta)-1)+"</b>");
      $('#numeradorTema'+tema).text(Number(numerador)-1);
      $('#numeradorTotal').text(Number($('#numeradorTotal').text())-1);
      $.ajax({
          type        : 'POST',
          url         : 'crearExamenProcesamiento.php',
          data        : 'funcion=' + funcion + '&idPregunta=' + idPregunta + '&puntos=' + (Number(puntosPregunta)-1) + '&tema=' + tema,
          success:function(respuesta){
            if(respuesta){
                console.log(respuesta);
            }
            else{
                console.log("ha fallado");
            }

          },
      dataType:"json"
      })
      event.preventDefault();
    }
  });

  /*
  * Pide confirmación sobre eliminar una pregunta y en caso afirmativo la elimina
  */
  $('[id^=boton-eliminar]').click(function() {
    var r = confirm("¿Estás seguro de querer eliminar esta pregunta?");
    if (r == true) {
      let str = $(this).parent().parent().parent().parent().attr('id');
      let tema = str.substring(13, 13+(str.length-13));
      let numerador = $('#numeradorTema'+tema).text();
      let puntosPregunta = $(this).parent().parent().parent().attr('puntos');
      $('#numeradorTema'+tema).text(Number(numerador)-puntosPregunta);
      $('#numeradorTotal').text(Number($('#numeradorTotal').text())-puntosPregunta);
      let idPregunta = $(this).attr('pregunta');
      let div = document.getElementById(idPregunta);
      div.parentNode.removeChild(div);
      let funcion = 'eliminarPregunta';
      $.ajax({
          type        : 'POST',
          url         : 'crearExamenProcesamiento.php',
          data        : 'funcion=' + funcion + '&idPregunta=' + idPregunta + '&tema=' + tema,
          success:function(respuesta){
            if(respuesta){
                console.log(respuesta);
            }
            else{
                console.log("ha fallado");
            }
          },
      dataType:"json"
      })
      event.preventDefault();

    }
  });



});

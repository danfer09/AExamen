$(document).ready(function(){

	$('#openNav').click(function() {
		 document.getElementById("mySidenav").style.width = "250px";
	});
	$('#closeNav').click(function() {
		 document.getElementById("mySidenav").style.width = "0px";
	});
	/*$('#form_añiadirPregunta').submit(function(event) {

	});*/


	/*$('#boton_aniadirPregunta').click(function() {
		 console.log($(this).attr("tema"));
		 console.log($(this).attr("asignatura"));
	});*/
	$(".fa-plus-circle").click(function() {
		$tema=$(this).attr("tema");
		$idAsignatura=$(this).attr("asignatura");
		//console.log("entra al click");
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
          			console.log(respuesta);
          			//console.log("llega");
                $('#boton_añiadir').attr('disabled',false);
          			$('#table_aniadirPreguntas').children('tr,td').remove();
                $('#table_aniadirPreguntas').attr("tema", $tema);
          			$("#info_aniadirPreg_vacio").hide();
                $("#info_aniadirPreg_limite").hide();
                $("#info_aniadirPreg_todas").hide();
                var preguntas = [];
                $('.preguntaTema'+$tema).each(function( index ) {
                  preguntas[index] = $(this).attr("id");
                  //console.log($(this).attr("id"));
                  //console.log( index + ": " + $( this ).text() );
                });
                //console.log($('#numeradorTema'+$tema).text()+'  '+$('#denominadorTema'+$tema).text());
          			if(respuesta.length>0){
                  var pinta = false;
                  if ($('#numeradorTema'+$tema).text() == $('#denominadorTema'+$tema).text()) {
                    for (i = 0; i < respuesta.length; i++) {
                      if (preguntas.indexOf(respuesta[i]["id"]) == -1) {
                        //console.log(respuesta[i]["titulo"]+"  "+respuesta[i]["id"]+"/n");
                        pinta = true;
                        $("#table_aniadirPreguntas").append('<tr><td><input disabled id="checkbox-'+$tema+'-'+i+'" type="radio" name="preguntas[]" value="'+respuesta[i]["id"]+'"></td><td>'+respuesta[i]["titulo"]+'</td><td>'+respuesta[i]["cuerpo"]+'</td><td>'+respuesta[i]["tema"]+'</td></tr>');
                      }
                    }
                    $('#boton_añiadir').attr('disabled',true);
                    $("#info_aniadirPreg_limite").show();
                  } else {
                    for (i = 0; i < respuesta.length; i++) {
                      if (preguntas.indexOf(respuesta[i]["id"]) == -1) {
                        //console.log(respuesta[i]["titulo"]+"  "+respuesta[i]["id"]+"/n");
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
      						//$("#info_aniadirPreg").text('No hay ninguna pregunta de este tema').addClass('badge badge-pill badge-danger');
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
    	event.preventDefault();
	});


  $("#form_aniadirPregunta").submit(function(event) {
    //console.log("entra aniadir");
    var funcion = "aniadirPreguntas";
    var tema=$('#table_aniadirPreguntas').attr("tema");
    $('#modal_aniadirPreguntas').modal('hide');
    $("#modal_aniadirPreguntas .close").click();
    var form_data = $(this).serialize();
  
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'crearExamenProcesamiento.php', // the url where we want to POST
            data        : form_data + '&funcion=' + funcion, // our data object
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
      location.reload();
      event.preventDefault();
  });

$('#guardarNuevoExamen').click(function() {
  let siglas = $('#nombreExamen').attr('siglas');
  var nombreExamen=$('#nombreExamen').val();
  if (!nombreExamen) {
    alert("Por favor, introduzca un nombre de examen");
  } else {
    let funcion = 'guardarExamen';
    //console.log("entra a js");
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'crearExamenProcesamiento.php', // the url where we want to POST
            data        : 'funcion=' + funcion + '&nombreExamen=' + nombreExamen, // our data object
            success:function(respuesta){
            if(respuesta){
                console.log(respuesta);
                /*
                for(i=0; i<respuesta.length; i++){
                  $('#preguntasTema'+ tema).append('<div class="col-12">'+ respuesta[i].titulo+' '+ respuesta[i].cuerpo +'</div>');
                }
                //$("#modal_aniadirPreguntas").modal('hide');*/
                //location.reload();
            }
            else{
                console.log("ha fallado");
            }
            //window.location = 'examenes.php?asignatura=todas&autor=todos';
        },
        dataType:"json"
        })
        window.location = 'examenes.php?asignatura='+siglas+'&autor=todos&successCreate=true';
        event.preventDefault();
  }
});

$('#guardarModificarExamen').click(function() {
  var nombreExamen=$('#nombreExamen').val();
  let siglas = $('#nombreExamen').attr('siglas');
  console.log(nombreExamen);
  if (!nombreExamen) {
    alert("Por favor, introduzca un nombre de examen");
  } else {
    let funcion = 'guardarModificarExamen';
    //console.log("entra a js");
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'modificarExamenProcesamiento.php', // the url where we want to POST
            data        : 'funcion=' + funcion + '&nombreExamen=' + nombreExamen, // our data object
            success:function(respuesta){
            if(respuesta){
                console.log(respuesta);
                /*
                for(i=0; i<respuesta.length; i++){
                  $('#preguntasTema'+ tema).append('<div class="col-12">'+ respuesta[i].titulo+' '+ respuesta[i].cuerpo +'</div>');
                }
                //$("#modal_aniadirPreguntas").modal('hide');*/
                //location.reload();
            }
            else{
                console.log("ha fallado");
            }
            //window.location = 'examenes.php?asignatura=todas&autor=todos';
        },
        dataType:"json"
        })
        window.location = 'examenes.php?asignatura='+siglas+'&autor=todos&successEdit=true';
        event.preventDefault();
  }
});

$('[id^=masPuntosPregunta]').click(function() {
  //alert($(this).parent().find('.puntos').text());
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
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'crearExamenProcesamiento.php', // the url where we want to POST
            data        : 'funcion=' + funcion + '&idPregunta=' + idPregunta + '&puntos=' + (Number(puntosPregunta)+1) + '&tema=' + tema, // our data object
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
        //location.reload();
        event.preventDefault();
  }

});

$('[id^=menosPuntosPregunta]').click(function() {
  //alert($(this).parent().find('.puntos').text());
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
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'crearExamenProcesamiento.php', // the url where we want to POST
            data        : 'funcion=' + funcion + '&idPregunta=' + idPregunta + '&puntos=' + (Number(puntosPregunta)-1) + '&tema=' + tema, // our data object
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
        //location.reload();
        event.preventDefault();
  }
});


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
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'crearExamenProcesamiento.php', // the url where we want to POST
            data        : 'funcion=' + funcion + '&idPregunta=' + idPregunta + '&tema=' + tema, // our data object
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
        //location.reload();
        event.preventDefault();

  }
});

/*$('#nombreExamen').bind('change keydown keyup', function() {
  let funcion = 'guardarNombreExamenJSON';
  let nombre = $(this).val();
  let idExamen = getUrlParameter('id');
  console.log($('#nombreExamen').val());  
      $.ajax({
              type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
              url         : 'crearExamenProcesamiento.php', // the url where we want to POST
              data        : 'funcion=' + funcion + '&nombreExamen=' + nombre + '&idExamen' + idExamen, // our data object
              dataType: 'json',
              success: function (respuesta) {
                if(respuesta){
                //console.log(respuesta);
                   
                }
                else{
                    console.log("ha fallado");
                }
                
              },
              complete: function (data) {
                      // Schedule the next
              }
      });
  });

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};*/

/*$('#nombreExamen').bind('change mouseup mousedown mouseout keydown keyup', function() {
  let funcion = 'guardarNombreExamenJSON';
  let nombre = $(this).val();
  //console.log($('#nombreExamen').val());
  var interval = 500;  // 1000 = 1 second, 3000 = 3 seconds
  function doAjax() {
      $.ajax({
              type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
              url         : 'crearExamenProcesamiento.php', // the url where we want to POST
              data        : 'funcion=' + funcion + '&nombreExamen=' + nombre, // our data object
              dataType: 'json',
              success: function (respuesta) {
                if(respuesta){
                //console.log(respuesta);
                   
                }
                else{
                    console.log("ha fallado");
                }
                
              },
              complete: function (data) {
                      // Schedule the next
                      
                      setTimeout(doAjax, interval);
              }
      });
  }
  setTimeout(doAjax, interval);
});*/
	
});
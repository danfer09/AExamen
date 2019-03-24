$(document).ready(function(){
    $('#opciones a').click(function() {
        var id = $(this).attr("id");
        if(id == "boton_modalBorrar"){
        	$("#boton_borrar").attr("id_profesor",$(this).attr("idProfesor"));
        	//alert($(this).attr("idExamens"));
        	//alert($("#boton_borrar").attr("id_Examen"));
        	$('#modal_borrarProfesor').modal('show');
        } else if (id == "boton_modalEditar") {
            $("#boton_editar").attr("id_profesor",$(this).attr("idProfesor"));
            $("#nombre").val($('#nombreProfesor'+$(this).attr("idProfesor")).text());
            $("#apellidos").val($('#apellidosProfesor'+$(this).attr("idProfesor")).text());
            $("#email").val($('#emailProfesor'+$(this).attr("idProfesor")).text());

            $('#modal_editarProfesor').modal('show');
        }
        
    });


    $('#form_delete').submit(function(event) {
        var funcion = "borrarProfesor";
        var form_data = $(this).serialize();
        $.ajax({
          type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
          url         : 'profesoresAdminProcesamiento.php', // the url where we want to POST
          data        : form_data + '&funcion=' + funcion + '&idProfesor=' + $("#boton_borrar").attr("id_profesor"), // our data object
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

    $('#form_mod').submit(function(event) {
        var funcion = "editarProfesor";
        var form_data = $(this).serialize();
        /*var formDataAndFunction = {
            'titulo'              : $('input[name=titulo]').val(),
            'cuerpo'              : $('input[name=cuerpo]').val(),
            'funcion'             : $('input[name=cuerpo]').val(),
            'tema'                : $('input[name=tema]').val()
        };*/
        //$('#myForm').serialize() + "&moredata=" + morevalue
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'profesoresAdminProcesamiento.php', // the url where we want to POST
            data        : form_data + '&funcion=' + funcion + '&idProfesor=' + $("#boton_editar").attr("id_profesor"), // our data object
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


  $('#boton_borrar').click(function() {
    const mensaje = "Esta acción no se puede retornar, ¿está seguro de que desea eliminar este profesor?";
    if(window.confirm(mensaje)){ 
        $("#form_delete").submit(); 
      }
    return false;
  });

  $('#boton_editar').click(function() {
    const mensaje = "¿Está seguro de que desea editar este profesor?";
    if(window.confirm(mensaje)){ 
        $("#form_mod").submit(); 
      }
      return false;
  });

$('.modalAsignaturas').click(function() {
    /*-----------------Abrir modal -------*/
    var idProfesor = $(this).attr("idProfesor");
    console.log(idProfesor);
    $('#modalAsignaturas').modal('show');

    /*--------------------añadir las asignaturas al popup-----------------------------*/
    var funcion = "getAsignaturas";
      
    $.ajax({
        type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url         : 'profesoresAdminProcesamiento.php', // the url where we want to POST
        data        : 'funcion=' + funcion + '&idProfesor=' + idProfesor, // our data object
        success:function(respuesta){
            if(respuesta){
                $("#formAsigCoord").attr("idProfesor", idProfesor);
                //alert(respuesta);
                console.log(respuesta);
                //console.log("llega");
                


                

                $('#boton_aniadir').attr('disabled',false);
                $('#tableAsignaturas').children('tr,td').remove();
                //$("#infoTodosProfAdd").hide();
                //console.log($('#numeradorTema'+$tema).text()+'  '+$('#denominadorTema'+$tema).text());
                //$("#tableAsignaturas").append('<from id="formC">');

                for (var i = 0; i< respuesta['asigSiCoord'].length; i++) {
                    console.log(respuesta['asigSiCoord'][i]);
                    $("#tableAsignaturas").append('<tr><td><input type="checkbox" checked name="asignatura" value="'+respuesta['asigSiCoord'][i]["id"]+'"></td><td>'+respuesta['asigSiCoord'][i]["siglas"]+'</td><td>'+respuesta['asigSiCoord'][i]["nombre"]);
                }
                for (var i = 0; i< respuesta['asigNoCoord'].length; i++) {
                    console.log(respuesta['asigNoCoord'][i]);
                    $("#tableAsignaturas").append('<tr><td><input type="checkbox" name="asignatura" value="'+respuesta['asigNoCoord'][i]["id"]+'"></td><td>'+respuesta['asigNoCoord'][i]["siglas"]+'</td><td>'+respuesta['asigNoCoord'][i]["nombre"]);

                }
                //$("#tableAsignaturas").append('<input type="submit" value="Submit">');
                //$("#tableAsignaturas").append('</from>');

                        //$("#tableAsignaturas").append('<tr><td><input type="radio" name="profesor" value="'+respuesta[i]["id"]+'"></td><td>'+respuesta[i]["nombre"]+'</td><td>'+respuesta[i]["apellidos"]+'</td><td>'+respuesta[i]["email"]+'</td></tr>');




              
            }
            else{
                //alert("Fallo al editar");
                console.log("falla");
                //location.reload();
            }
        },
        dataType:"json"
    })
    event.preventDefault();
    
});

/*AJAX para cuando hace submit al formulario que de las asignaturas*/
  $( "#formAsigCoord" ).submit(function( event ) {
        let idAsigSelect = [];
        let idAsigNoSelect = [];
        let contSelect = 0;
        let contNoSelect = 0;
        $(":checkbox").each(function () {
            var ischecked = $(this).is(":checked");
            if (ischecked) {
                idAsigSelect[contSelect]= $(this).val();
                contSelect++;
            }
            else if (!ischecked) {
                idAsigNoSelect[contNoSelect]= $(this).val();
                contNoSelect++;
            }
        });
        //idAsigSelect contiene los id de los profesores seleccionados
        console.log('Id asignaturas seleccionadas');
        console.log(idAsigSelect);
        console.log('Id asignaturas no seleccionadas');
        console.log(idAsigNoSelect);
        //Obtenemos el id de la asignatura, de un atributo del formulario del modal. Este atributo se lo
        //ponemos en $('.botonCoordinadores').click(function()
        const idProfesor = $("#formAsigCoord").attr("idProfesor");
        console.log(idProfesor);
        //Definimos el nombre de la funcion a la que vamos a llamar en el PHP
        const funcion = 'setCoordinadores';

        var idAsigSelectParam = JSON.stringify(idAsigSelect);
        var idAsigNoSelectParam = JSON.stringify(idAsigNoSelect);

        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'profesoresAdminProcesamiento.php', // the url where we want to POST
            data        : 'funcion=' + funcion + '&idAsigSelect=' + idAsigSelectParam + '&idProfesor=' + idProfesor +'&idAsigNoSelect=' + idAsigNoSelectParam, // our data object
            success:function(respuesta){
                if(respuesta){
                    console.log(respuesta);
                    //console.log("llega");  
                    location.reload();
                
                }
                else{
                    console.log("falla");
                    location.reload();
                }
            }
        })

      event.preventDefault();
    });

});
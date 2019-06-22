$(document).ready(function(){

    //Muestra los modales de las diversas opciones cuando se clickan
    $('#opciones a').click(function() {
        var id = $(this).attr("id");
        if(id == "boton_modalBorrar"){
        	$("#boton_borrar").attr("id_profesor",$(this).attr("idProfesor"));
        	$('#modal_borrarProfesor').modal('show');
        } else if (id == "boton_modalEditar") {
            $("#boton_editar").attr("id_profesor",$(this).attr("idProfesor"));
            $("#nombreForm").val($('#nombreProfesor'+$(this).attr("idProfesor")).text());
            $("#apellidosForm").val($('#apellidosProfesor'+$(this).attr("idProfesor")).text());
            $("#emailForm").val($('#emailProfesor'+$(this).attr("idProfesor")).text());

            $('#modal_editarProfesor').modal('show');
        }
    });

    //Funcion que comprueba que los campos no estén vacíos
    function comprobarCamposRellenados(){
        let resultado=true;
        if($("#nombreForm").val()==""||$("#apellidosForm").val()=="" ||$("#emailForm").val()=="")
            resultado=false;
        return resultado;
    }

    //Funcion que comprueba que el email tenga un formato correcto
    function comprobarEmailValido(){
        var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
        const email = $("#emailForm").val();
        let resultado=true;
        if (caract.test(email) == false)
            resultado=false;
        return resultado;
    }

    //Cuando se suelta una tecla se llevan a cabo diversas comprobaciones
    $('#nombreForm,#apellidosForm,#emailForm').keyup(function() {
        if(comprobarEmailValido() && comprobarCamposRellenados()){
            $("#boton_editar").attr("class", "btn btn-primary active");
            $("#boton_editar").attr("disabled", false);
            $("#mensajeEditar").hide();
        }
        else if(!comprobarCamposRellenados()){
            $("#boton_editar").attr("class", "btn btn-primary disabled");
            $("#boton_editar").attr("disabled", true);
            $("#mensajeEditar").show();
            $("#mensajeEditar").text("No puede dejar ningún campo vacío").addClass('badge badge-pill badge-danger');
        }
        else if(!comprobarEmailValido()){
            $("#boton_editar").attr("class", "btn btn-primary disabled");
            $("#boton_editar").attr("disabled", true);
            $("#mensajeEditar").show();
            $("#mensajeEditar").text("El correo electrónico tiene que ser valido").addClass('badge badge-pill badge-danger');
        }

    });


    //muestra el modal de añadir profesor
    $('#boton_modalAñadir').click(function(){
        $('#modalAniadirProfesor').modal('show');
    });

    //submit del formulario de borrar profesor
    $('#form_delete').submit(function(event) {
        var funcion = "borrarProfesor";
        var form_data = $(this).serialize();
        $.ajax({
          type        : 'POST',
          url         : '/profesores/funcionesAjaxProfesores',
          data        : form_data + '&funcion=' + funcion + '&idProfesor=' + $("#boton_borrar").attr("id_profesor"),
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

    //submit del formulario de editar profesor
    $('#form_mod').submit(function(event) {
        var funcion = "editarProfesor";
        var form_data = $(this).serialize();

        $.ajax({
            type        : 'POST',
            url         : '/profesores/funcionesAjaxProfesores',
            data        : form_data + '&funcion=' + funcion + '&idProfesor=' + $("#boton_editar").attr("id_profesor"),
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

  //Mensaje de confirmacion antes de borrar un profesor
  $('#boton_borrar').click(function() {
    const mensaje = "Esta acción no se puede revertir, ¿está seguro de que desea eliminar este profesor?";
    if(window.confirm(mensaje)){
        $("#form_delete").submit();
      }
    return false;
  });

  //Mensaje de confirmacion antes de editar un profesor
  $('#boton_editar').click(function() {
    const mensaje = "¿Está seguro de que desea editar este profesor?";
    if(window.confirm(mensaje)){
        $("#form_mod").submit();
      }
      return false;
  });

//Modal de añadir asignatura
$('.modalAsignaturas').click(function() {
    /*-----------------Abrir modal -------*/
    var idProfesor = $(this).attr("idProfesor");
    console.log(idProfesor);
    $('#modalAsignaturas').modal('show');

    /*--------------------añadir las asignaturas al popup-----------------------------*/
    var funcion = "getAsignaturas";

    $.ajax({
        type        : 'POST',
        url         : 'profesoresAdminProcesamiento.php',
        data        : 'funcion=' + funcion + '&idProfesor=' + idProfesor,
        success:function(respuesta){
            if(respuesta){
                console.log(idProfesor);
                $("#formAsig").attr("idProfesor", idProfesor);
                console.log(respuesta);
                console.log("llega");

                $('#tableAsignaturas').children('tr,td').remove();

                for (var i = 0; i< respuesta['asigSiCoord'].length; i++) {
                    console.log(respuesta['asigSiCoord'][i]);
                    $("#tableAsignaturas").append('<tr><td><input type="checkbox" checked name="asignatura" value="'+respuesta['asigSiCoord'][i]["id"]+'" class="asigCheckbox"></td><td>'+respuesta['asigSiCoord'][i]["siglas"]+'</td><td>'+respuesta['asigSiCoord'][i]["nombre"]);
                }
                for (var i = 0; i< respuesta['asigNoCoord'].length; i++) {
                    console.log(respuesta['asigNoCoord'][i]);
                    $("#tableAsignaturas").append('<tr><td><input type="checkbox" name="asignatura" value="'+respuesta['asigNoCoord'][i]["id"]+'" class="asigCheckbox"></td><td>'+respuesta['asigNoCoord'][i]["siglas"]+'</td><td>'+respuesta['asigNoCoord'][i]["nombre"]);
                }
            }
            else{
                console.log("falla");
            }
        },
        dataType:"json"
    })
    event.preventDefault();

});

    const asigConCoord = [];
    $(document).on('click', '#tableAsignaturas input.asigCheckbox',function() {
        const isCheck = $(this).is(":checked")
        let idAsig =$(this).val();
        let funcion = 'isAsigWithCoord';
        let idProfesor = $("#formAsig").attr("idProfesor");
        $.ajax({
            type        : 'POST',
            url         : 'profesoresAdminProcesamiento.php',
            data        : 'funcion=' + funcion + '&idAsig=' + idAsig + '&idProfesor=' + idProfesor,
            success:function(respuesta){
                if(respuesta){
                    console.log(respuesta);
                    if(respuesta == 0 && isCheck){
                        console.log("entra primero");
                        console.log(asigConCoord);
                        asigConCoord [idAsig] = 1;
                        let todasAsigConCoord = true;

                        asigConCoord.forEach(element => {
                            if(element==0){
                                todasAsigConCoord = false;
                            }
                        });
                        if(todasAsigConCoord){
                            $('#boton_aniadir_asig').attr('disabled',false);
                        }
                        else{
                            $('#boton_aniadir_asig').attr('disabled',true);
                        }
                    }
                    else if (respuesta == 0 && !isCheck){
                        console.log("entra segundo");
                        console.log(asigConCoord);

                        asigConCoord [idAsig] = 0;

                        $('#boton_aniadir_asig').attr('disabled',true);
                    }
                }
                else{
                    console.log("falla");
                }
            }
        })
    });



    /*AJAX para cuando hace submit al formulario que de las asignaturas*/
    $( "#formAsig" ).submit(function( event ) {
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

        //Obtenemos el id de la asignatura, de un atributo del formulario del modal. Este atributo se lo
        //ponemos en $('.botonCoordinadores').click(function()
        const idProfesor = $("#formAsig").attr("idProfesor");
        //Definimos el nombre de la funcion a la que vamos a llamar en el PHP
        const funcion = 'setCoordinadores';

        var idAsigSelectParam = JSON.stringify(idAsigSelect);
        var idAsigNoSelectParam = JSON.stringify(idAsigNoSelect);
        $.ajax({
            type        : 'POST',
            url         : 'profesoresAdminProcesamiento.php',
            data        : 'funcion=' + funcion + '&idAsigSelect=' + idAsigSelectParam + '&idProfesor=' + idProfesor +'&idAsigNoSelect=' + idAsigNoSelectParam,
            success:function(respuesta){
                if(respuesta){
                    console.log(respuesta);
                    console.log("llega");
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

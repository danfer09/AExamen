$(document).ready(function(){
	$("#btn_cambiarNombre").on( "click", function() {
	    $("#boton_cambiarNombre").attr("class", "btn btn-primary disabled");
	    $("#boton_cambiarNombre").attr("disabled", true);
	    $('#modal_cambiarNombre').modal('show');
	});

	$("#btn_cambiarApellidos").on( "click", function() {
	    $("#boton_cambiarApellidos").attr("class", "btn btn-primary disabled");
	    $("#boton_cambiarApellidos").attr("disabled", true);
	    $('#modal_cambiarApellidos').modal('show');
	});

	$("#btn_cambiarClave").on( "click", function() {
	    $("#boton_cambiarClave").attr("class", "btn btn-primary disabled");
	    $("#boton_cambiarClave").attr("disabled", true);
	    $('#modal_cambiarClave').modal('show');
	    
		
	});

	$("#nombre").after("<span id='mensaje'></span><br>");
	var mensaje = $("#mensaje");
	function compruebaNombreOApellidos(mensaje, nombre_o_apellidos,nombreBoton){
		var vacio = "El campo no puede estar vacio";
		mensaje.hide();
		if((nombre_o_apellidos =="nombre" && $("#nombre").val() == "")|| (nombre_o_apellidos=="apellidos" && $("#apellidos").val() == "")){
			$("#"+nombreBoton).attr("class", "btn btn-primary disabled");
	    	$("#"+nombreBoton).attr("disabled", true);
	    	mensaje.show();
	    	mensaje.text(vacio).addClass('badge badge-pill badge-danger');
		}
		if((nombre_o_apellidos =="nombre" && $("#nombre").val() != "")|| (nombre_o_apellidos=="apellidos" && $("#apellidos").val() != "")){
			$("#"+nombreBoton).attr("class", "btn btn-primary active");
			$("#"+nombreBoton).attr("disabled", false);
	    	mensaje.hide();
		}
	}
	$("#nombre").after("<span id='mensajeNombre'></span><br>");
	$("#nombre").keyup(function(){
		const mensaje = $("#mensajeNombre");
		compruebaNombreOApellidos(mensaje,"nombre","boton_cambiarNombre");
	});

	$("#apellidos").after("<span id='mensajeApellidos'></span><br>");
	$("#apellidos").keyup(function(){
		const mensaje = $("#mensajeApellidos");
		compruebaNombreOApellidos(mensaje,"apellidos","boton_cambiarApellidos");
	});


	$("#repitaClave").after("<span id='mensaje'></span><br>");
	var mensaje = $("#mensaje");
	function compruebaClaves(mensaje){
		var coinciden = "Las contraseñas si coinciden";
		var noCoinciden = "No coinciden las contraseñas";
		var vacio = "La contraseña no puede estar vacía";
		mensaje.hide();
		if($("#clave").val() == $("#repitaClave").val()){
			console.log( "click" );
			$("#boton_cambiarClave").attr("class", "btn btn-primary active");
			$("#boton_cambiarClave").attr("disabled", false);
			mensaje.hide();
			console.log(mensaje);
		}
		else if($("#clave").val()=="" || $("#repitaClave").val()==""){
			console.log( "campoVacio" );
			$("#boton_cambiarClave").attr("class", "btn btn-primary disabled");
	    	$("#boton_cambiarClave").attr("disabled", true);
	    	mensaje.show();
	    	mensaje.text(vacio).addClass('badge badge-pill badge-danger');
	    	console.log(mensaje);
		}
		else{
			console.log( "campoDiferente" );
			$("#boton_cambiarClave").attr("class", "btn btn-primary disabled");
	    	$("#boton_cambiarClave").attr("disabled", true);
	    	mensaje.show();
	    	mensaje.text(noCoinciden).addClass('badge badge-pill badge-danger');
	    	console.log(mensaje);

		}
	}
	$("#clave").keyup(function(){
		compruebaClaves(mensaje);
	});
	$("#repitaClave").keyup(function(){
		compruebaClaves(mensaje);
	});



	$('#boton_cambiarNombre').click(function() {
		const mensaje = "¿Esta seguro de que desea cambiar su nombre?";
		if(window.confirm(mensaje)){ 
	    	$("#form_cambiarNombre").submit(); 
	    }
	    return false;
	});

	$('#boton_cambiarApellidos').click(function() {
		const mensaje = "¿Esta seguro de que desea cambiar sus apellidos?";
		if(window.confirm(mensaje)){ 
	    	$("#form_cambiarApellidos").submit(); 
	    }
	    return false;
	});

	$('#boton_cambiarClave').click(function() {
		const mensaje = "¿Esta seguro de que desea cambiar la contraseña?";
		if(window.confirm(mensaje)){ 
	    	$("#form_cambiarClave").submit(); 
	    }
	    return false;
	});

	/*$("#form_cambiarClave").on("submit", function() {
	    if(window.confirm("Asdf")){ 
	    	$("#form_cambiarClave").submit(); 
	    }
	    return false;
	});*/


});

/*
BOTON PARA PEDIR CONFIRMACIÓN DEL CAMBIO QUE SE VA A REALIZAR, NO FUNCIONA
$("#boton_cambiar").on( "click", function() {
    console.log( "click" );
    $('#modal_confirmar').modal('show');
});
*/

/*
UTIL PARA OCULTAR Y MOSTRAR COSAS
$('#myModal').modal('toggle');
$('#myModal').modal('show');
$('#myModal').modal('hide');
*/
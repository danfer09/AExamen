$(document).ready(function(){
	$("#btn_cambiarNombre").on( "click", function() {
	    console.log( "click" );
	    $('#modal_cambiarNombre').modal('show');
	});

	$("#btn_cambiarApellidos").on( "click", function() {
	    console.log( "click" );
	    $('#modal_cambiarApellidos').modal('show');
	});

	$("#btn_cambiarClave").on( "click", function() {
	    $('#modal_cambiarClave').modal('show');
	    $("#boton_cambiarClave").attr("class", "btn btn-primary disabled");
	    $("#boton_cambiarClave").attr("disabled", true);
	    
		
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
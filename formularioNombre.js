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

	
	$("#clave").change(function(){
		if($("#clave").val() == $("#repitaClave").val()){
			console.log( "click" );
			$("#boton_cambiarClave").attr("class", "btn btn-primary active");
			$("#boton_cambiarClave").attr("disabled", false);
		}
	});
	$("#repitaClave").change(function(){
		if($("#clave").val() == $("#repitaClave").val()){
			console.log( "click" );
			$("#boton_cambiarClave").attr("class", "btn btn-primary active");
			$("#boton_cambiarClave").attr("disabled", false);
		}
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
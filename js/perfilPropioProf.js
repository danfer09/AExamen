$(document).ready(function(){

	/*Listener sobre los botones para cambiar nombre, apellidos y clave
	, cuando se pulsan se muestra el modal correspondiente y se bloquea 
	el boton de cambiar para que no lo pulse hasta que no este relleno los
	campos correspondientes*/
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

	$("#btn_cambiarCorreo").on( "click", function() {
	    //$("#boton_cambiarCorreo").attr("class", "btn btn-primary disabled");
	    //$("#boton_cambiarCorreo").attr("disabled", true);
	    $('#modal_cambiarCorreo').modal('show');
	});

	/*Añadimos un elemento span para mostrar mensajes y lo guardamos
	en una variable para que sea más facil acceder a el*/
	$("#nombre").after("<span id='mensaje'></span><br>");
	var mensaje = $("#mensaje");

	/**
	 * Muestra un mensaje si el formulario esta vacío y bloquea el boton
	 de submit, si esta relleno oculta el mensaje y desbloquea el 
	 boton

	 * @mensaje {elementHTML}: elemento HTML en el cual escribimos el 
	 mensaje informativo
	 * @nombre_o_apellidos {string}: indica si lo que tenemos que 
	 comprobar el nombre o el apellidos
	 * @nombreBoton {string}: Nombre del boton que tenemos que bloquear
	 o desbloquear

	 * @return  {void}

	 */
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
	/*Insertamos un elemento en el cual vamos a mostrar mensajes*/
	$("#nombre").after("<span id='mensajeNombre'></span><br>");
	/*Listener que se activa cuando el usuario suelta el boton 
	#nombre y comprueba si el campo donde deberia ir el nombre esta
	vacio o no llamando a la funcion compruebaNombreOApellidos*/
	$("#nombre").keyup(function(){
		const mensaje = $("#mensajeNombre");
		compruebaNombreOApellidos(mensaje,"nombre","boton_cambiarNombre");
	});
	/*Insertamos un elemento en el cual vamos a mostrar mensajes*/
	$("#apellidos").after("<span id='mensajeApellidos'></span><br>");
	/*Listener que se activa cuando el usuario suelta el boton 
	#apellidos y comprueba si el campo donde deberia ir el apellido esta
	vacio o no llamando a la funcion compruebaNombreOApellidos*/
	$("#apellidos").keyup(function(){
		const mensaje = $("#mensajeApellidos");
		compruebaNombreOApellidos(mensaje,"apellidos","boton_cambiarApellidos");
	});

	/*Añadimos un elemento span para mostrar mensajes y lo guardamos
	en una variable para que sea más facil acceder a el*/
	$("#repitaClave").after("<span id='mensaje'></span><br>");
	var mensaje = $("#mensaje");
	/**
	 * Comprueba si los campos #clave y #repitaClave son iguales
	 o estan vacio alguno de ellos o los dos. Consecuentemente 
	 muestra mensajes

	 * @mensaje {elementHTML}: elemento HTML en el cual escribimos el 
	 mensaje informativo

	 * @return  {void}

	 */
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

	/*Cuando el usuario escribe en el formulario #clave o #repitaClave
	llamamos a la funcion compruebaClaves para que compruebe si el 
	valor de los dos formularios son iguales */
	$("#clave").keyup(function(){
		compruebaClaves(mensaje);
	});
	$("#repitaClave").keyup(function(){
		compruebaClaves(mensaje);
	});


	/*Listeners en los distintos botones para cambiar diversos
	campos, para que una vez pulsados, le muestre al usuarios
	una ventana emergente para que confirme si quiere realizar
	dichos cambios*/
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

	$('#boton_cambiarCorreo').click(function() {
		const mensaje = "¿Esta seguro de que desea cambiar el correo?";
		if(window.confirm(mensaje)){ 
	    	$("#form_cambiarCorreo").submit(); 
	    }
	    return false;
	});
	

});

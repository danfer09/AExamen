$(document).ready(function(){

	var password = document.getElementById("clave")
	  , confirm_password = document.getElementById("repetirClave");

	function validatePassword(){
	  if(password.value != confirm_password.value) {
	    confirm_password.setCustomValidity("Las contraseñas no coinciden");
	  } else {
	    confirm_password.setCustomValidity('');
	  }
	}

	password.onchange = validatePassword;
	confirm_password.onkeyup = validatePassword;

});
$(document).ready(function(){

	//Garantiza que las contraseñas introducidas son iguales y no nulas
	var password = document.getElementById("pass1")
	  , confirm_password = document.getElementById("pass2");

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
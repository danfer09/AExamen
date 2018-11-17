$(document).ready(function(){

	$('#openNav').click(function() {
		 document.getElementById("mySidenav").style.width = "250px";
		 document.getElementById("overlay").style.display = "block";
	});
	$('#overlay').click(function() {
		document.getElementById("mySidenav").style.width = "0px";
		document.getElementById("overlay").style.display = "none";
	});

	$('#closeNav').click(function() {
		 document.getElementById("mySidenav").style.width = "0px";
		 document.getElementById("overlay").style.display = "none";
	});
});
$(document).ready(function(){
	$('#tabla_asignaturas tr').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = href;
        }
    });

});
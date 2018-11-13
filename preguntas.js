$(document).ready(function(){
	$('#tabla_preguntas tr').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = "detallePregunta.php?id="+href;
        }
    });

});
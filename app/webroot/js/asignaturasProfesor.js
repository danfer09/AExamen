/*Listener que escucha las filas de la tabla y cuando
se pincha en una de ellas redirige a la asignatura
de la fila que se haya clickado*/
$(document).ready(function(){
	$('#tabla_asignaturas tr').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = href;
        }
    });

});
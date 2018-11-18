
<?php
	if(!empty($_POST['preguntas[]'])){
	// Loop to store and display values of individual checked checkbox.
		foreach($_POST['preguntas[]'] as $selected){
			echo $selected."</br>";
		}
	}

?>

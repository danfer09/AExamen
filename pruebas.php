<?php
  $preguntas = $_POST['preguntas'];
  if(empty($preguntas)) 
  {
    echo("You didn't select any buildings.");
  } 
  else
  {
    $total = count($preguntas);

    //echo("You selected $N door(s): ");
    for($i=0; $i < $total; $i++)
    {
      echo($preguntas[$i] . " ");
    }
  }
?>
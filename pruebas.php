<?php
  $credentialsStr = file_get_contents('json/credentials.json');
    $credentials = json_decode($credentialsStr, true);
    $db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
    
    if($db){
      $total = count($preguntas);
      echo json_encode($filas);
        for($i=0; $i < $total; $i++){
          $sql ="SELECT * FROM `preguntas` WHERE id=".$preguntas[$i];
          $consulta=mysqli_query($db,$sql);
          $filas[$i]=mysqli_fetch_assoc($consulta);
          //
          // NO CONSIGO COGER DE $filas[$i] el tema y el id de la pregunta...
          //
          //echo json_encode($filas);
          //insertarPreguntaJSON($fila['tema'], $fila['id'], 1);
          //esta llamada a insertarPreguntaJSON tiene que estar descomentada, pero la he comentado porque añade al json algo con el tema a null
      }
      //echo json_encode($filas);
    }
    else{
      $_SESSION['error_BBDD']=true;
      //header('Location: loginFormulario.php');
    }
    mysqli_close($db);
    //echo json_encode($filas);
?>
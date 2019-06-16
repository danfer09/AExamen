<?php
App::uses('AppModel', 'Model');

class Perfil extends AppModel {
  public $useTable = 'profesores';

  public function cambioNombre($nuevoNombre) {
    if(!$_SESSION['administrador'])
      $sql = "UPDATE profesores SET nombre= '".$nuevoNombre."' WHERE id=".$_SESSION['id'];
    else
      $sql = "UPDATE administradores SET nombre= '".$nuevoNombre."' WHERE id=".$_SESSION['id'];

    $consulta=$this->query($sql);
    //Comprobamos los distintos errores que se pueden producir y ponemos a true los session que corresponden
    if($consulta){
      //Registramos el cambio de nombre en el log
      $log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].' (nombre anterior)'.
              " | ACTION --> Cambio de nombre a ".$nuevoNombre.PHP_EOL.
              "-----------------------------------------------------------------".PHP_EOL;
      file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);
      $_SESSION['nombre']=$nuevoNombre;
      return true;
    } else {
      return false;
    }
  }

  public function cambioApellidos($nuevoApellidos) {
    if(!$_SESSION['administrador']){
      $sql = "UPDATE profesores SET apellidos= '".$nuevoApellidos."' WHERE id=".$_SESSION['id'];
    }
    else{
      $sql = "UPDATE administradores SET apellidos= '".$nuevoApellidos."' WHERE id=".$_SESSION['id'];
    }
    $consulta=$this->query($sql);

    if($consulta){
      //Registramos el cambio de apellidos en el log
      $log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].' (apellidos anteriores), '.$_SESSION['nombre'].
              " | ACTION --> Cambio de apellidos a ".$nuevoApellidos.PHP_EOL.
              "-----------------------------------------------------------------".PHP_EOL;
      file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);
      $_SESSION['apellidos']=$nuevoApellidos;
      return true;
    } else {
      return false;
    }
  }

  public function cambioClave($nuevoClave) {
    //Haseamos la nueva clave, que nos llega en texto plano
    $hashed_clave = password_hash($nuevoClave, PASSWORD_BCRYPT);

    if(!$_SESSION['administrador']){
      $sql = "UPDATE profesores SET clave= '".$hashed_clave."' WHERE id=".$_SESSION['id'];
    }
    else{
      $sql = "UPDATE `administradores` SET `clave`= '".$hashed_clave."' WHERE id=".$_SESSION['id'];
    }
    $consulta=$this->query($sql);

    if($consulta) {
    //Registramos el cambio de contraseña en el log
      $log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
            " | ACTION --> Cambio de contraseña".PHP_EOL.
            "-----------------------------------------------------------------".PHP_EOL;
      file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);
      return true;
    } else {
      return false;
    }
  }

  public function cambioCorreo($nuevoCorreo) {
    $sql = "UPDATE `administradores` SET `email`='".$nuevoCorreo."' WHERE `id`=".$_SESSION['id'];
    $consulta=mysqli_query($db,$sql);
    //Comprobamos los distintos errores que se pueden producir y ponemos a true los session que corresponden
    if($consulta) {
      $_SESSION["email"] = $nuevoCorreo;
      return true;
    } else {
      return false;
    }
  }

}

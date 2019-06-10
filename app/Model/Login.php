<?php
App::uses('AppModel', 'Model');

class Login extends AppModel {
  public $useTable = 'profesores';

  public function acceso($email, $clave){
				$sql = "SELECT * FROM profesores";
				$fila=$this->query($sql);
				$encontrado=false;
        $i=0;
				/*Buscamos un correo que coincida con el que nos a introducido el usuario, en caso de que no se encuentre sale con la variable $encontrado a false*/
				while(!$encontrado && $fila[$i]['profesores']){
					if($email==$fila[$i]['profesores']['email']){
						$encontrado=true;
            $filaEncontradaUser = $fila[$i]['profesores'];
					}
          else{
            $i++;
          }
				}

				$sql = "SELECT * FROM administradores";
        $fila=$this->query($sql);
				$encontradoAdmin=false;
        $i=0;

				/*Buscamos un correo que coincida con el que nos a introducido el usuario, en caso de que no se encuentre sale con la variable $encontrado a false*/
				while(!$encontradoAdmin && $fila[$i]['administradores']){
					if($email==$fila[$i]['administradores']['email']){
						$encontradoAdmin=true;
            $filaEncontradaAdmin = $fila[$i]['administradores'];
					}
					else{
            $i++;
					}
				}
				/*Si no encotramos el nombre del usario ponemos a true la variable de error al autenticar y redirigimos a loginFormulario.php donde la tratamos*/
				if(!$encontrado && !$encontradoAdmin){
					$_SESSION['error_autenticar']=true;
					return false;
				}
				else{
					//Verificamos la clave con esta funcion ya que en la BBDD esta encriptada, en caso de que se verifique, declaramos e inicializamos todas las variables de session de usuario.
          $datos = ($encontrado) ? $filaEncontradaUser : $filaEncontradaAdmin;
					if(password_verify($clave, $datos['clave'])){
						$_SESSION['logeado']=true;
						$_SESSION["email"] = $email;
						$_SESSION["nombre"]=$datos['nombre'];
						$_SESSION['apellidos']=$datos['apellidos'];
						$_SESSION['id']=$datos['id'];
						$_SESSION['administrador'] = $encontradoAdmin;
						date_default_timezone_set("Europe/Madrid");
						//Registramos este login en el log
						$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
						        " | ACTION --> Inicio de sesión ".' de '.$_SESSION['email'].PHP_EOL.
						        "-----------------------------------------------------------------".PHP_EOL;
						file_put_contents('log/log_AExamen.log', utf8_decode($log), FILE_APPEND);


            return true;
					}
					/*En caso de que la clave no coincida con el usuairo ponemos a true la variable de error al autentiacar y redirigimos a loginFormulario.php donde la tratamos*/
					else{
						$_SESSION['error_autenticar']=true;
            return false;
          }
				}



    }

}

<?php
App::uses('AppModel', 'Model');

class Login extends AppModel {
  public $useTable = 'profesores';

  public function login($email){
    //NO RECIBIMOS $email
				$sql = "SELECT * FROM profesores";
				$fila=$this->query($sql);
				$encontrado=false;
        $i=0;
				/*Buscamos un correo que coincida con el que nos a introducido el usuario, en caso de que no se encuentre sale con la variable $encontrado a false*/
				while(!$encontrado && $fila[$i]['profesores']){
					if($email==$fila[$i]['profesores']['email']){
						$encontrado=true;
					}
          else{
            $i++;
          }
				}
				$sql = "SELECT * FROM administradores";
        $fila=$this->query($sql);
				$encontradoAdmin=false;
				/*Buscamos un correo que coincida con el que nos a introducido el usuario, en caso de que no se encuentre sale con la variable $encontrado a false*/
				while(!$encontradoAdmin && $filaAdmin){
					if($email==$filaAdmin['email']){
						$encontradoAdmin=true;
					}
					else{
						$filaAdmin=mysqli_fetch_assoc($consulta);
					}
				}
				/*Si no encotramos el nombre del usario ponemos a true la variable de error al autenticar y redirigimos a loginFormulario.php donde la tratamos*/

				if(!$encontrado && !$encontradoAdmin){
					$_SESSION['error_autenticar']=true;
					header('Location: loginFormulario.php');
				}
				else{
					//Verificamos la clave con esta funcion ya que en la BBDD esta encriptada, en caso de que se verifique, declaramos e inicializamos todas las variables de session de usuario.
					$datos = ($encontrado) ? $fila : $filaAdmin;
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
						file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);

						header('Location: paginaPrincipalProf.php');
					}
					/*En caso de que la clave no coincida con el usuairo ponemos a true la variable de error al autentiacar y redirigimos a loginFormulario.php donde la tratamos*/
					else{
						$_SESSION['error_autenticar']=true;
						header('Location: loginFormulario.php');
					}
				}



    }

}

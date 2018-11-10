<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="estilo.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <!-- Modal de Nombre -->
    <span id="a">adios</span>
    <input type="button" id="boton" value="boton">
    <input type="button" id="boton1" value="boton 1">

  </div>


  
  <script src="jquery-3.3.1.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script type="text/javascript" src="formularioNombre.js"></script>
  <script type="text/javascript">
    
    $(document).ready(function(){
      $("#a").after("<span id='b'>hola</span>");
      var mensaje=$("#b");
      var hola="hola";
      var adios="adios";
      function tonteria(hola,mensaje){
        console.log(hola);
        mensaje.hide();
      }
      function muestra(adios,mensaje){
        console.log(adios);
        mensaje.show();
      }
        $("#boton").click(function(){
          tonteria(hola,mensaje);
        }); 
        $("#boton1").click(function(){
          muestra(adios,mensaje);
        }); 
    });
  </script>

</body>
</html>
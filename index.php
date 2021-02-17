<?php 

  require_once("controller/controller.php");
  
/*
Script de validacion de Session de la aplicacion y redireccionamiento en caso de no existir sesion alguna
*/
    $controller = new Control();
    $usuariosAll = $controller->allUsuarios();
/*
  Interfas de comunicacion para el modulo de usuarios altas
*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Usuarios</title>
  <link href="lib/bootstrap/css/bootstrap.css?v=1.1.1.1" rel="stylesheet">
  <link href="lib/font-awesome/css/font-awesome.css?v=1.1.1.1" rel="stylesheet" type="text/css">
  <link href="css/style.css?v=1.1.1.1" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
</head>
<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <?php include("layout/header.php");?>
  <div class="content-wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12" style="background: #E91E63!important;padding: 5px 5px;color: aliceblue;margin-top: 3rem;margin-bottom: 1rem;">
            <div class="" style="display: inline-block;vertical-align: middle;">
                <h2 id="usuarioNombre" style=" margin-top: 0.5rem;margin-left: 80px;">
                  <i class="fa fa-user-circle-o" aria-hidden="true" style="display: block; position: absolute; top: -40px; font-size: 3rem; padding: 10px 10px;  background: #03191d; border-radius: 6px; left: 10px;"></i>Registro de nuevos usuarios</h2>
            </div>
        </div>
        <div class="col-10">
          <h1>Usuarios</h1>
        </div>
        <div class="col-2" style="text-align: end;vertical-align: middle;">
          <a id="nuevoUsuario" style="display: inline-block; vertical-align: middle; font-size: 1.7rem; color: azure; background: #03A9F4; padding: 5px 7px; border-radius: 2px; cursor: pointer;"><i class="fa fa-plus-square" aria-hidden="true" style="display: block;vertical-align: middle;"></i></a>
        </div>
        <div class="col-12">
            <table class="table">
              <thead class="thead-dark">
                  <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Telefono</th>
                    <th>control</th>
                </tr>
              </thead>
              <tbody id="tbodyAllUsuarios">
              </tbody>
            </table>
        </div>                       
        </div>
    </div>
   <?php include("layout/footer.php");?>
  </div>
  <script  src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
  <script type="text/javascript">var allUsuarios = <?php echo json_encode($usuariosAll)?>;</script>
  <script src="js/usuario.js"></script>
</body>
</html>
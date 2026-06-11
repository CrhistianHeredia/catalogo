<?php 

  require_once("controller/controller.php");
  require_once("controller/auth.php");

  requireAuth();

  $adminUser = $_SESSION['admin_user'];
  
  $controller = new Control();
  $usuariosAll = $controller->allUsuarios();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Usuarios</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="lib/bootstrap/css/bootstrap.css?v=2.1.1.1" rel="stylesheet">
  <link href="lib/font-awesome/css/all.css?v=2.1.1.1" rel="stylesheet" type="text/css">
  <link href="css/style.css?v=2.3.0" rel="stylesheet">
  <link href="lib/jquery-confirm/jquery-confirm.min.css" rel="stylesheet">
</head>
<body class="fixed-nav sticky-footer bg-light" id="page-top">
  <?php include("layout/header.php");?>
  <div class="content-wrapper">
    <div class="container-fluid py-4">

      <!-- Page header card -->
      <div class="card border-0 shadow-sm rounded-3 mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body p-4 text-white">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0 me-3">
              <i class="fa-solid fa-circle-user fa-3x" aria-hidden="true" style="opacity: 0.85;"></i>
            </div>
            <div class="flex-grow-1">
              <h2 class="h4 mb-1 fw-bold" id="usuarioNombre">Registro de nuevos usuarios</h2>
              <p class="mb-0 small opacity-75">Gestión del catálogo de usuarios del sistema</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Toolbar: title + add button -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 fw-bold text-dark mb-0">
          <i class="fa-solid fa-users text-primary me-2"></i>Usuarios
        </h1>
        <a id="nuevoUsuario" class="btn btn-primary rounded-pill shadow-sm px-4" role="button">
          <i class="fa-solid fa-plus me-1"></i>Nuevo
        </a>
      </div>

      <!-- Users table -->
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0">
              <thead class="table-dark">
                <tr>
                  <th class="ps-4">Nombre</th>
                  <th>Email</th>
                  <th>Teléfono</th>
                  <th class="text-center" style="width: 140px;">Control</th>
                </tr>
              </thead>
              <tbody id="tbodyAllUsuarios">
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
   <?php include("layout/footer.php");?>
  </div>
  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="lib/jquery-confirm/jquery-confirm.min.js"></script>
  <script type="text/javascript">var allUsuarios = <?php echo json_encode($usuariosAll)?>;</script>
  <script src="js/usuario.js?v=2.2.0"></script>
</body>
</html>

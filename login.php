<?php
require_once "controller/auth.php";

ensureSession();

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Por favor ingrese usuario y contraseña.';
    } elseif (loginUser($username, $password)) {
        header("Location: index.php");
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Ingresar — Admin usuarios</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="lib/font-awesome/css/all.css" rel="stylesheet" type="text/css">
  <link href="css/style.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
    }
    .login-card {
      border: 0;
      border-radius: 1rem;
      box-shadow: 0 1rem 2rem rgba(0,0,0,0.15);
    }
    .login-card .card-body {
      padding: 2.5rem;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-11 col-sm-8 col-md-6 col-lg-4">
        <div class="card login-card">
          <div class="card-body">
            <div class="text-center mb-4">
              <i class="fa-solid fa-circle-user fa-4x text-primary mb-3" aria-hidden="true"></i>
              <h4 class="fw-bold">Admin usuarios</h4>
              <p class="text-muted small">Ingrese sus credenciales</p>
            </div>

            <?php if ($error): ?>
              <div class="alert alert-danger py-2 small d-flex align-items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></span>
              </div>
            <?php endif; ?>

            <form method="post" action="login.php" novalidate>
              <div class="mb-3">
                <label for="username" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="username" name="username"
                       placeholder="Ingrese su usuario" required autofocus>
              </div>
              <div class="mb-4">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="••••••" required>
              </div>
              <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold py-2">
                <i class="fa-solid fa-right-to-bracket me-1"></i>Ingresar
              </button>
            </form>

            <p class="text-center text-muted small mt-4 mb-0">
              <i class="fa-solid fa-database me-1"></i>Catálogo de usuarios
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

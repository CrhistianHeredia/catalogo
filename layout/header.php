<nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm" id="mainNav" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container-fluid px-4">
      <a class="navbar-brand fw-bold" href="index.php">
        <i class="fa-solid fa-database me-2"></i>Admin usuarios
      </a>
      <div class="d-flex align-items-center ms-auto order-lg-last">
        <span class="text-white-50 small me-2 d-none d-sm-inline">
          <i class="fa-regular fa-circle-user me-1"></i><?php echo htmlspecialchars($adminUser, ENT_QUOTES, 'UTF-8'); ?>
        </span>
        <a href="logout.php" class="btn btn-outline-light btn-sm rounded-pill px-3">
          <i class="fa-solid fa-right-from-bracket me-1"></i>Salir
        </a>
        <button class="navbar-toggler border-0 ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fa-solid fa-bars fa-lg" aria-hidden="true"></i>
        </button>
      </div>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link px-3 active" href="index.php">
              <i class="fa-regular fa-address-book me-1" aria-hidden="true"></i>Usuarios
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

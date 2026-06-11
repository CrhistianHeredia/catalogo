<?php
/*
 * Configuracion local de la base de datos.
 * Copia este archivo como config.local.php y ajusta los valores.
 * config.local.php NO se versiona (esta en .gitignore).
 */

// Solo definir si no estan ya definidas (por campo.php)
defined('DB_HOST') || define('DB_HOST', 'localhost');
defined('DB_USER') || define('DB_USER', 'root');
defined('DB_PASS') || define('DB_PASS', '');
defined('DB_NAME') || define('DB_NAME', 'prueba');

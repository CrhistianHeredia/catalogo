<?php

/*
	Se definen los atributos de la base de datos
*/

// Load optional local config FIRST so it can override
$localConfig = __DIR__ . '/../../config.local.php';
if (file_exists($localConfig)) {
    require_once $localConfig;
}

// Fallback defaults if config.local.php was not loaded or didn't define them
defined('DB_HOST') || define('DB_HOST', 'localhost');
defined('DB_USER') || define('DB_USER', 'root');
defined('DB_PASS') || define('DB_PASS', '');
defined('DB_NAME') || define('DB_NAME', 'prueba');

// Legacy constants for backward compatibility
define('host', DB_HOST);
define('user', DB_USER);
define('password', DB_PASS);
define('database', DB_NAME);

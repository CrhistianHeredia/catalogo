<?php
/**
 * Database connection factory.
 * Returns a PDO instance configured from the constants defined in campo.php.
 * No class to extend — just a function.
 */

require_once __DIR__ . '/campo.php';

/**
 * Open a PDO connection using the configured credentials.
 * Returns a PDO instance on success.
 *
 * @return PDO
 * @throws PDOException
 */
function openConnection(): PDO {
    $conexion = new PDO(
        'mysql:host=' . host . ';dbname=' . database,
        user,
        password,
        [Pdo\Mysql::ATTR_INIT_COMMAND => "SET NAMES utf8"]
    );
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conexion;
}

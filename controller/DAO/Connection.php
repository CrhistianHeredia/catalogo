<?php

declare(strict_types=1);

require_once __DIR__ . '/campo.php';

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

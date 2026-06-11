<?php
/**
 * Router script for AJAX requests from js/usuario.js.
 * This file is the AJAX endpoint for CRUD operations.
 * It validates the method against a whitelist before dispatching,
 * and requires authentication.
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/controller.php';

requireAuth();

// Whitelist of allowed method names that can be called remotely
$allowedMethods = ['altaUsuarios', 'editarUsuarios', 'eliminaUsuario'];

if (isset($_REQUEST['request']) && !empty($_REQUEST['request']) && isset($_REQUEST['arg']) && !empty($_REQUEST['arg'])) {
    $request = $_REQUEST['request'];
    $arg = json_decode($_REQUEST['arg'], true);

    if (!in_array($request, $allowedMethods, true)) {
        http_response_code(403);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }

    $control = new Control();
    echo $control->$request($arg);
}

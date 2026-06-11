<?php
require_once __DIR__ . "/DAO/admin.php";

/**
 * Start a PHP session if not already started.
 */
function ensureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Redirect to login if the user is not authenticated.
 */
function requireAuth() {
    ensureSession();
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Verify login credentials against the admins table.
 * Returns true on success (sets session vars), false on failure.
 *
 * @param string $username
 * @param string $password
 * @return bool
 */
function loginUser($username, $password) {
    $admin = new Admin();
    $row = $admin->findByUsername($username);

    if (!$row) {
        return false;
    }

    if (!password_verify($password, $row['password'])) {
        return false;
    }

    ensureSession();
    $_SESSION['admin_id']  = (int)$row['id'];
    $_SESSION['admin_user'] = $row['username'];
    session_regenerate_id(true);
    return true;
}

/**
 * Destroy the session and log out.
 */
function logoutUser() {
    ensureSession();
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

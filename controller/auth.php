<?php

declare(strict_types=1);

require_once __DIR__ . "/DAO/admin.php";

function ensureSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function requireAuth(): void {
    ensureSession();
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit;
    }
}

function loginUser(string $username, string $password): bool {
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

function logoutUser(): void {
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

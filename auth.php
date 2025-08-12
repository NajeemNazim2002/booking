<?php
class Auth {
    public static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function preventCache() {
        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
        header("Pragma: no-cache"); // HTTP 1.0
        header("Expires: 0"); // Proxies
    }

    public static function requireRole($role) {
        self::startSession();
        self::preventCache();

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
            // Redirect to login page in root directory
            header("Location: ../login.php");
            exit();
        }
    }
}
?>

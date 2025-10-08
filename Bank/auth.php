<?php
session_start();

function ensureLoggedIn(): void {
    if (!isset($_SESSION['username'])) {
        header('Location: index.html?msg=login-required#login');
        exit;
    }
}
?>



<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function verificarSesion(): void
{
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: login.php');
        exit;
    }
}

function usuarioAutenticado(): bool
{
    return isset($_SESSION['usuario_id']);
}
?>

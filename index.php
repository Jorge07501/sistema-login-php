<?php
require_once 'includes/auth.php';
if (usuarioAutenticado()) {
    header('Location: perfil.php');
} else {
    header('Location: login.php');
}
exit;
?>

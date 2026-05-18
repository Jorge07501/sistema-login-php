<?php
require_once 'config/conexion.php';
require_once 'includes/auth.php';
verificarSesion();

$mensaje = '';
$tipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actual = $_POST['actual'] ?? '';
    $nueva = $_POST['nueva'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    if ($actual === '' || $nueva === '' || $confirmar === '') {
        $mensaje = 'Todos los campos son obligatorios.';
        $tipo = 'error';
    } elseif ($nueva !== $confirmar) {
        $mensaje = 'La nueva contraseña y la confirmación no coinciden.';
        $tipo = 'error';
    } elseif (strlen($nueva) < 6) {
        $mensaje = 'La nueva contraseña debe tener al menos 6 caracteres.';
        $tipo = 'error';
    } else {
        $stmt = $pdo->prepare('SELECT password FROM usuarios WHERE id = ?');
        $stmt->execute([$_SESSION['usuario_id']]);
        $usuario = $stmt->fetch();

        if (!$usuario || !password_verify($actual, $usuario['password'])) {
            $mensaje = 'La contraseña actual es incorrecta.';
            $tipo = 'error';
        } else {
            $nuevoHash = password_hash($nueva, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE usuarios SET password = ? WHERE id = ?');
            $stmt->execute([$nuevoHash, $_SESSION['usuario_id']]);
            $mensaje = 'Contraseña actualizada correctamente.';
            $tipo = 'success';
        }
    }
}
?>
<?php include 'includes/header.php'; ?>
<h1>Cambiar contraseña</h1>
<div class="menu">
    <a class="btn btn-secondary" href="perfil.php">Volver al perfil</a>
    <a class="btn btn-danger" href="logout.php">Cerrar sesión</a>
</div>

<?php if ($mensaje): ?><div class="alert <?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

<form method="POST" action="">
    <label>Contraseña actual</label>
    <input type="password" name="actual" required>

    <label>Nueva contraseña</label>
    <input type="password" name="nueva" required>

    <label>Confirmar nueva contraseña</label>
    <input type="password" name="confirmar" required>

    <button type="submit">Cambiar contraseña</button>
</form>
<?php include 'includes/footer.php'; ?>

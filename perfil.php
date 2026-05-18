<?php
require_once 'config/conexion.php';
require_once 'includes/auth.php';
verificarSesion();

$mensaje = '';
$tipo = '';

$stmt = $pdo->prepare('SELECT id, cedula, nombre, correo, fecha_registro FROM usuarios WHERE id = ?');
$stmt->execute([$_SESSION['usuario_id']]);
$usuario = $stmt->fetch();

if (!$usuario) {
    session_destroy();
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');

    if ($nombre === '' || $correo === '') {
        $mensaje = 'Nombre y correo son obligatorios.';
        $tipo = 'error';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'El correo no tiene un formato válido.';
        $tipo = 'error';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE correo = ? AND id != ?');
        $stmt->execute([$correo, $_SESSION['usuario_id']]);

        if ($stmt->fetch()) {
            $mensaje = 'El correo ya está registrado por otro usuario.';
            $tipo = 'error';
        } else {
            $stmt = $pdo->prepare('UPDATE usuarios SET nombre = ?, correo = ? WHERE id = ?');
            $stmt->execute([$nombre, $correo, $_SESSION['usuario_id']]);
            $_SESSION['usuario_nombre'] = $nombre;
            $mensaje = 'Perfil actualizado correctamente.';
            $tipo = 'success';

            $stmt = $pdo->prepare('SELECT id, cedula, nombre, correo, fecha_registro FROM usuarios WHERE id = ?');
            $stmt->execute([$_SESSION['usuario_id']]);
            $usuario = $stmt->fetch();
        }
    }
}
?>
<?php include 'includes/header.php'; ?>
<h1>Zona privada de perfil</h1>
<div class="menu">
    <a class="btn btn-secondary" href="cambiar_password.php">Cambiar contraseña</a>
    <a class="btn btn-danger" href="logout.php">Cerrar sesión</a>
</div>

<?php if ($mensaje): ?><div class="alert <?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

<p><strong>Cédula:</strong> <?= htmlspecialchars($usuario['cedula']) ?></p>
<p><strong>Fecha de registro:</strong> <?= htmlspecialchars($usuario['fecha_registro']) ?></p>

<h2>Actualizar datos</h2>
<form method="POST" action="">
    <label>Nombre</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

    <label>Correo</label>
    <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>

    <button type="submit">Actualizar perfil</button>
</form>
<?php include 'includes/footer.php'; ?>

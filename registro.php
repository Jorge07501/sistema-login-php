<?php
require_once 'config/conexion.php';
require_once 'includes/auth.php';

$mensaje = '';
$tipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = trim($_POST['cedula'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    if ($cedula === '' || $nombre === '' || $correo === '' || $password === '' || $confirmar === '') {
        $mensaje = 'Todos los campos son obligatorios.';
        $tipo = 'error';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'El correo no tiene un formato válido.';
        $tipo = 'error';
    } elseif ($password !== $confirmar) {
        $mensaje = 'Las contraseñas no coinciden.';
        $tipo = 'error';
    } elseif (strlen($password) < 6) {
        $mensaje = 'La contraseña debe tener al menos 6 caracteres.';
        $tipo = 'error';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE correo = ? OR cedula = ?');
        $stmt->execute([$correo, $cedula]);

        if ($stmt->fetch()) {
            $mensaje = 'El correo o la cédula ya se encuentran registrados.';
            $tipo = 'error';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO usuarios (cedula, nombre, correo, password) VALUES (?, ?, ?, ?)');
            $stmt->execute([$cedula, $nombre, $correo, $hash]);
            $mensaje = 'Usuario registrado correctamente. Ya puede iniciar sesión.';
            $tipo = 'success';
        }
    }
}
?>
<?php include 'includes/header.php'; ?>
<h1>Registro de usuario</h1>
<?php if ($mensaje): ?><div class="alert <?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>
<form method="POST" action="">
    <label>Cédula</label>
    <input type="text" name="cedula" required>

    <label>Nombre</label>
    <input type="text" name="nombre" required>

    <label>Correo</label>
    <input type="email" name="correo" required>

    <label>Contraseña</label>
    <input type="password" name="password" required>

    <label>Confirmar contraseña</label>
    <input type="password" name="confirmar" required>

    <button type="submit">Registrarse</button>
</form>
<p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
<?php include 'includes/footer.php'; ?>

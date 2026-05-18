<?php
require_once 'config/conexion.php';
require_once 'includes/auth.php';

if (usuarioAutenticado()) {
    header('Location: perfil.php');
    exit;
}

$mensaje = '';
$tipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($correo === '' || $password === '') {
        $mensaje = 'Ingrese correo y contraseña.';
        $tipo = 'error';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'Correo inválido.';
        $tipo = 'error';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE correo = ?');
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            header('Location: perfil.php');
            exit;
        } else {
            $mensaje = 'Credenciales incorrectas.';
            $tipo = 'error';
        }
    }
}
?>
<?php include 'includes/header.php'; ?>
<h1>Login</h1>
<?php if ($mensaje): ?><div class="alert <?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>
<form method="POST" action="">
    <label>Correo</label>
    <input type="email" name="correo" required>

    <label>Contraseña</label>
    <input type="password" name="password" required>

    <button type="submit">Ingresar</button>
</form>
<p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
<?php include 'includes/footer.php'; ?>

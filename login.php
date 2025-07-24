<?php
session_start();
include 'conexion.php';

// Variable para mensaje de error
$error = "";

// Si envía formulario:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    // Consulta a la BD
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$usuario' AND contraseña = '$contraseña'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Si existe, guardar sesión y redirigir
        $_SESSION['usuario'] = $usuario;
        header("Location: index.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Centro de Salud</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #e0f7fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .login-card {
            background: white;
            border-radius: 10px;
            padding: 40px 30px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 26px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }
        h2 {
            font-size: 20px;
            text-align: center;
            margin-bottom: 30px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h1>Centro de Salud</h1>
    <h2>Iniciar Sesión</h2>

    <?php if ($error != ""): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" name="usuario" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="contraseña" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
    </form>
</div>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

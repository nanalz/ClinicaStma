<?php
include 'conexion.php';

$mensaje = "";
$exito = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $ci = trim($_POST['ci']);
    $celular = trim($_POST['celular']);
    $direccion = trim($_POST['direccion']);
    $fechanac = trim($_POST['fechanac']);

    $errores = [];

    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/", $nombres)) {
        $errores[] = "Nombres inválidos. Solo letras y espacios.";
    }
    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/", $apellidos)) {
        $errores[] = "Apellidos inválidos. Solo letras y espacios.";
    }
    if (!preg_match("/^[0-9]+$/", $ci)) {
        $errores[] = "Número de cédula inválido. Solo números, sin puntos.";
    }
    if (!preg_match("/^[0-9]{10,12}$/", $celular)) {
        $errores[] = "Número de celular inválido. Ejemplo válido: 09xx-xxx-xxx (sin guiones, solo números).";
    }
    if (!preg_match("/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s]+$/", $direccion)) {
        $errores[] = "Dirección inválida. Solo letras, números y espacios.";
    }
    if (empty($fechanac) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $fechanac)) {
        $errores[] = "Fecha de nacimiento inválida. Use el formato correcto.";
    } elseif (strtotime($fechanac) > time()) {
        $errores[] = "La fecha de nacimiento no puede ser futura.";
    }

    if (empty($errores)) {
        $sql = "INSERT INTO pacientes (nombres, apellidos, ci, fechanac, celular, direccion)
                VALUES ('$nombres', '$apellidos', '$ci', '$fechanac', '$celular', '$direccion')";

        if ($conn->query($sql) === TRUE) {
            $exito = true;
        } else {
            $mensaje = "Error: " . $conn->error;
        }
    } else {
        $mensaje = implode('<br>', $errores);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Paciente | CDS Hohenau</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #cce5ff;
        }
        .card {
            max-width: 600px;
            margin: 60px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="card">
    <h2 class="mb-4 text-center">Registrar Nuevo Paciente</h2>

    <?php if ($mensaje != ""): ?>
        <div class="alert alert-danger"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Nombres:</label>
            <input type="text" name="nombres" class="form-control" required
                   pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+"
                   title="Solo letras y espacios, se permiten acentos.">
        </div>

        <div class="mb-3">
            <label class="form-label">Apellidos:</label>
            <input type="text" name="apellidos" class="form-control" required
                   pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+"
                   title="Solo letras y espacios, se permiten acentos.">
        </div>

        <div class="mb-3">
            <label class="form-label">Número de Cédula:</label>
            <input type="text" name="ci" class="form-control" required
                   pattern="[0-9]+"
                   title="Ingrese solo números, sin puntos.">
        </div>

        <div class="mb-3">
            <label class="form-label">Celular:</label>
            <input type="text" name="celular" class="form-control" required
                   pattern="[0-9]{10,12}"
                   placeholder="Ej: 09xx-xxx-xxx (sin guiones)"
                   title="Ingrese solo números. Ejemplo: 0975123456">
        </div>

        <div class="mb-3">
            <label class="form-label">Dirección:</label>
            <input type="text" name="direccion" class="form-control" required
                   pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s]+"
                   title="Solo letras, números y espacios. Se permiten acentos.">
        </div>

        <div class="mb-4">
            <label class="form-label">Fecha de Nacimiento:</label>
            <input type="date" name="fechanac" class="form-control" required>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Registrar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php if ($exito): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Paciente registrado',
    text: 'El paciente fue registrado correctamente',
    confirmButtonText: 'Aceptar'
}).then(() => {
    window.location.href = 'index.php';
});
</script>
<?php endif; ?>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>

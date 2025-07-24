<?php
include 'conexion.php';

session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$mensaje = "";
$exito = false;

// Insertar consulta
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_paciente = $_POST['id_paciente'];
    $motivo = trim($_POST['motivo']);
    $profesional = trim($_POST['profesional']);
    $tipo = $_POST['tipo'];

    if (empty($id_paciente) || empty($motivo) || empty($profesional) || empty($tipo)) {
        $mensaje = "Todos los campos son obligatorios.";
    } else {
        $sql = "INSERT INTO consultas (id_paciente, motivo_consulta, profesional_referido, tipo_consulta, fecha_consulta)
                VALUES ('$id_paciente', '$motivo', '$profesional', '$tipo', NOW())";

        if ($conn->query($sql) === TRUE) {
            $exito = true;
        } else {
            $mensaje = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Consulta | CDS Hohenau</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #cce5ff;
        }
        .card {
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
        }
        .suggestions {
            border: 1px solid #ced4da;
            border-top: none;
            max-height: 200px;
            overflow-y: auto;
            position: absolute;
            z-index: 1000;
            background: white;
            width: 100%;
        }
        .suggestion-item {
            padding: 8px;
            cursor: pointer;
        }
        .suggestion-item:hover {
            background: #e9ecef;
        }
    </style>
</head>
<body>

<div class="card">
    <h2 class="mb-4 text-center">Registrar Nueva Consulta</h2>

    <?php if ($mensaje != ""): ?>
        <div class="alert alert-danger"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <!-- Autocomplete -->
        <div class="mb-3 position-relative">
            <label class="form-label">Buscar Paciente:</label>
            <input type="text" id="busquedaPaciente" class="form-control" placeholder="Empieza a escribir nombre o apellido...">
            <div id="suggestions" class="suggestions"></div>
            <input type="hidden" name="id_paciente" id="id_paciente">
        </div>

        <div id="datosPaciente" style="display:none;">
            <hr>
            <div class="mb-2">
                <label class="form-label">Nombre:</label>
                <input type="text" id="nombrePaciente" class="form-control" readonly>
            </div>
            <div class="mb-2">
                <label class="form-label">Apellido:</label>
                <input type="text" id="apellidoPaciente" class="form-control" readonly>
            </div>
            <div class="mb-2">
                <label class="form-label">Cédula:</label>
                <input type="text" id="ciPaciente" class="form-control" readonly>
            </div>
            <div class="mb-2">
                <label class="form-label">Fecha de Nacimiento:</label>
                <input type="text" id="fechanacPaciente" class="form-control" readonly>
            </div>
            <div class="mb-2">
                <label class="form-label">Celular:</label>
                <input type="text" id="celularPaciente" class="form-control" readonly>
            </div>
            <div class="mb-2">
                <label class="form-label">Dirección:</label>
                <input type="text" id="direccionPaciente" class="form-control" readonly>
            </div>
            <hr>
        </div>

        <div class="mb-3">
            <label class="form-label">Motivo de la Consulta:</label>
            <textarea name="motivo" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Profesional Referido:</label>
            <input type="text" name="profesional" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo de Consulta:</label>
            <select name="tipo" class="form-select" required>
                <option value="">Seleccione tipo</option>
                <option value="Ambulatorio">Ambulatorio</option>
                <option value="Urgencias">Urgencias</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1">Registrar Consulta</button>
            <a href="index.php" class="btn btn-secondary flex-grow-1">Cancelar</a>
        </div>
    </form>
</div>

<script>
const busqueda = document.getElementById('busquedaPaciente');
const suggestions = document.getElementById('suggestions');
const datosPaciente = document.getElementById('datosPaciente');

busqueda.addEventListener('input', () => {
    const term = busqueda.value;
    if (term.length < 2) {
        suggestions.innerHTML = '';
        return;
    }

    fetch('buscar_paciente.php?term=' + encodeURIComponent(term))
    .then(res => res.json())
    .then(data => {
        suggestions.innerHTML = '';
        data.forEach(p => {
            const div = document.createElement('div');
            div.classList.add('suggestion-item');
            div.textContent = p.nombres + ' ' + p.apellidos + ' (' + p.ci + ')';
            div.addEventListener('click', () => {
                document.getElementById('id_paciente').value = p.id_paciente;
                document.getElementById('nombrePaciente').value = p.nombres;
                document.getElementById('apellidoPaciente').value = p.apellidos;
                document.getElementById('ciPaciente').value = p.ci;
                document.getElementById('fechanacPaciente').value = p.fechanac;
                document.getElementById('celularPaciente').value = p.celular;
                document.getElementById('direccionPaciente').value = p.direccion;

                busqueda.value = p.nombres + ' ' + p.apellidos;
                suggestions.innerHTML = '';
                datosPaciente.style.display = 'block';
            });
            suggestions.appendChild(div);
        });
    });
});
</script>

<?php if ($exito): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Consulta registrada',
    text: 'La consulta fue guardada correctamente',
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

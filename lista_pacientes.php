<?php
include 'conexion.php';

session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['eliminar'])) {
    $id_eliminar = intval($_GET['eliminar']);
    $sql_delete = "DELETE FROM pacientes WHERE id_paciente = $id_eliminar";
    if ($conn->query($sql_delete) === TRUE) {
        $mensaje = "Paciente eliminado correctamente.";
    } else {
        $mensaje = "Error al eliminar paciente: " . $conn->error;
    }
}

$sql = "SELECT id_paciente, nombres, apellidos, ci, fechanac, celular, direccion FROM pacientes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pacientes | CDS Hohenau</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Centro de Salud</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
      aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Consultas</a></li>
        <li class="nav-item"><a class="nav-link" href="registro.php">Registrar Paciente</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar Sesión</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">

    <h2 class="mb-4">Lista de Pacientes</h2>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <?php if ($result && $result->num_rows > 0): ?>
    <div class="table-responsive">
      <table id="tablaPacientes" class="table table-bordered table-hover align-middle">
        <thead class="table-primary">
          <tr>
            <th>CI</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Fecha Nac.</th>
            <th>Celular</th>
            <th>Dirección</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['ci']); ?></td>
            <td><?php echo htmlspecialchars($row['nombres']); ?></td>
            <td><?php echo htmlspecialchars($row['apellidos']); ?></td>
            <td><?php echo htmlspecialchars($row['fechanac']); ?></td>
            <td><?php echo htmlspecialchars($row['celular']); ?></td>
            <td><?php echo htmlspecialchars($row['direccion']); ?></td>
            <td>
              <a href="editar_paciente.php?id=<?php echo $row['id_paciente']; ?>" class="btn btn-sm btn-warning">Editar</a>
              <button class="btn btn-sm btn-danger btn-eliminar" data-id="<?php echo $row['id_paciente']; ?>">Eliminar</button>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
      <p>No hay pacientes registrados.</p>
    <?php endif; ?>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery para DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

<script>
// Inicializar DataTables para tabla con orden y búsqueda
$(document).ready(function() {
    $('#tablaPacientes').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json"
        },
        "order": [[0, "asc"]] // ordenar por CI ascendente por defecto
    });
});

// Confirmación eliminar con SweetAlert2
document.querySelectorAll('.btn-eliminar').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción eliminará al paciente definitivamente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'lista_pacientes.php?eliminar=' + id;
            }
        });
    });
});
</script>

</body>
</html>

<?php
$conn->close();
?>

<?php
include 'conexion.php';

session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit();
}

// Variables de filtros
$fecha = isset($_GET['fecha']) ? trim($_GET['fecha']) : '';
$nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
$profesional = isset($_GET['profesional']) ? trim($_GET['profesional']) : '';
$ci = isset($_GET['ci']) ? trim($_GET['ci']) : '';

// Consulta base con JOIN
$sql = "SELECT c.id_consulta, p.nombres, p.apellidos, p.ci, 
               c.motivo_consulta, c.profesional_referido, 
               c.tipo_consulta, c.fecha_consulta
        FROM consultas c
        INNER JOIN pacientes p ON c.id_paciente = p.id_paciente
        WHERE 1=1";

// Aplicar filtros dinámicamente
if ($fecha != '') {
  $sql .= " AND DATE(c.fecha_consulta) = '$fecha'";
}
if ($nombre != '') {
  $sql .= " AND (p.nombres LIKE '%$nombre%' OR p.apellidos LIKE '%$nombre%')";
}
if ($profesional != '') {
  $sql .= " AND c.profesional_referido LIKE '%$profesional%'";
}
if ($ci != '') {
  $sql .= " AND p.ci LIKE '%$ci%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Consultas | CDS Hohenau</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- DataTables CSS -->
  <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
</head>

<body>

  <!-- Navbar -->
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
          <li class="nav-item">
            <a class="nav-link" href="registro_consulta.php">Nueva Consulta</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="registro.php">Nuevo Paciente</a>
          </li>
           <li class="nav-item">
            <a class="nav-link" href="lista_pacientes.php">Pacientes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Cerrar Sesión</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <h2 class="mb-4">Listado de Consultas</h2>

    <!-- Tabla -->
    <div class="table-responsive">
      <table id="tablaConsultas" class="table table-bordered table-hover">
        <thead class="table-primary">
          <tr>
            <th>CI</th>
            <th>Paciente</th>
            <th>Motivo</th>
            <th>Profesional Referido</th>
            <th>Tipo de Consulta</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row["ci"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["nombres"] . " " . $row["apellidos"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["motivo_consulta"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["profesional_referido"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["tipo_consulta"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["fecha_consulta"]) . "</td>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='6'>No hay consultas registradas o no se encontraron resultados.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Bootstrap Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#tablaConsultas').DataTable({
        language: {
          url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json'
        },
        order: [[0, 'asc']] // Ordena por CI ascendente
      });
    });
  </script>

</body>

</html>

<?php $conn->close(); ?>

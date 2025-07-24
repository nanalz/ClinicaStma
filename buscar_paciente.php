<?php
include 'conexion.php';

$term = trim($_GET['term']);

$sql = "SELECT id_paciente, nombres, apellidos, ci, fechanac, celular, direccion 
        FROM pacientes 
        WHERE nombres LIKE '%$term%' OR apellidos LIKE '%$term%'
        LIMIT 10";

$result = $conn->query($sql);

$pacientes = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pacientes[] = $row;
    }
}

echo json_encode($pacientes);

$conn->close();
?>

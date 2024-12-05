<?php
require_once 'reserva_controlador.php';
require_once 'conexion.php';
require_once 'reserva.php';

// Conexión a la base de datos
$conexion = new Conexion();
$reservaModel = new Reserva($conexion);
$controlador = new ReservaControlador($conexion);

// Verificar que se reciba un ID de reserva para archivar antes de mostrar el listado
if (isset($_GET['id_reserva'])) {
    $id_reserva = $_GET['id_reserva'];
    // Llamar al método del controlador para archivar la reserva
    $controlador->archivarReserva($id_reserva);
    
    // Después de archivar, redirigir a la página de reservas
    header("Location: vista_reserva.php");
    exit();
}

// Obtener los parámetros de filtro
$estado = isset($_GET['estado']) ? $_GET['estado'] : 'todas';
$fechaInicio = isset($_GET['fechaInicio']) && $_GET['fechaInicio'] !== '' ? $_GET['fechaInicio'] : null;
$fechaFin = isset($_GET['fechaFin']) && $_GET['fechaFin'] !== '' ? $_GET['fechaFin'] : null;

// Obtener reservas históricas con los filtros
$reservasHistoricas = $reservaModel->obtenerReservasHistoricas($estado, $fechaInicio, $fechaFin);

// Mostrar las reservas
foreach ($reservasHistoricas as $reserva) {
    echo "<div class='reserva-item'>";
    echo "<p>Cliente: " . htmlspecialchars($reserva['nombre'] . " " . $reserva['apellido']) . "</p>";
    echo "<p>Teléfono: " . htmlspecialchars($reserva['telefono']) . "</p>";
    echo "<p>Fecha: " . date("d/m/y", strtotime($reserva['fecha'])) . "</p>";
    echo "<p>Motivo: " . ucfirst(htmlspecialchars($reserva['motivo'])) . "</p>";
    echo "<p>Cantidad de Personas: " . htmlspecialchars($reserva['cantidad_personas']) . "</p>";
    echo "<p>Hora: " . date("H:i", strtotime($reserva['hora'])) . "</p>";
    echo "<p>Estado: " . ucfirst(htmlspecialchars($reserva['estado'])) . "</p>";
    echo "</div>";
}

?>

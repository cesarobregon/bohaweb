<? 
require_once 'reserva_controlador.php';  // Asegúrate de tener el path correcto
// Crear conexión a la base de datos
$conexion = new Conexion();  // Asegúrate de que la clase 'Conexion' esté bien implementada

// Crear una instancia del controlador, pasando la conexión
$reservaControlador = new ReservaControlador($conexion);

// Obtener reservas pendientes usando el controlador
$reservasPendientes = $reservaControlador->obtenerReservasPendientes();

// Enviar el resultado como JSON
echo json_encode($reservasPendientes);
?>
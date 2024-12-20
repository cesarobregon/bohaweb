<?php
// Incluir los archivos de conexión y modelos
require_once 'Conexion.php'; // Archivo de conexión a la base de datos
require_once 'reserva.php';  // Archivo del modelo Reserva
require_once 'cliente.php';  // Archivo del modelo Cliente

// Crear una instancia de la conexión
$conexion = new Conexion(); 

// Instanciar los modelos con la conexión
$reservaModel = new Reserva($conexion);

try {
    // Conexión a la base de datos
    require_once 'Conexion.php';
    $db = new Conexion();

    // Verificar si se ha recibido el ID de la reserva
    if (isset($_GET['id_reserva'])) {
        $id_reserva = $_GET['id_reserva'];

        // Actualizar el estado de la reserva a archivada
        $sql = "UPDATE reservas SET archivada = 1 WHERE id_reserva = :id_reserva";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_reserva', $id_reserva);
        $stmt->execute();

        // Redirigir de vuelta a la página principal después de archivar
        header("Location: index.php");
        exit(); // Asegúrate de llamar a exit() después de la redirección para evitar que se ejecute más código
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

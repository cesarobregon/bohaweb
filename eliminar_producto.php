<?php
// Incluir el archivo de conexión
require_once 'conexion.php';

// Paso 1: Establecer la conexión usando la clase 'Conexion'
$conexion = new Conexion();

// Verificar si se ha recibido el ID del producto
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Paso 2: Consulta para eliminar el producto por su ID usando la conexión PDO
    $sql = "DELETE FROM productos WHERE id_producto = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(1, $id, PDO::PARAM_INT);  // Usar bindParam con PDO
    
    if ($stmt->execute()) {
        // Redirigir de vuelta a la página de administración con mensaje de éxito
        header("Location: administrarMenu.php?mensaje=Producto eliminado con éxito");
        exit; // Asegúrate de salir después de redirigir
    } else {
        // Redirigir con mensaje de error
        header("Location: administrarMenu.php?mensaje=Error al eliminar el producto");
        exit; // Asegúrate de salir después de redirigir
    }

    $stmt->closeCursor(); // Cerramos el statement
}

// Paso 3: Cerrar la conexión
$conexion = null;
?>

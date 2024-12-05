<?php
// Paso 1: Incluir el archivo de conexión
require_once 'Conexion.php';

try {
    // Paso 2: Crear una instancia de la conexión
    $pdo = new Conexion();

    // Paso 3: Obtener los datos del formulario
    $uuid_pedido = $_POST['uuid_pedido'];
    $estado = $_POST['estado'];

    // Paso 4: Preparar la consulta para actualizar el estado
    $sql = "UPDATE pedidos SET estado = :estado WHERE uuid_pedido = :uuid_pedido";
    $stmt = $pdo->prepare($sql);

    // Paso 5: Ejecutar la consulta con los valores proporcionados
    if ($stmt->execute([':estado' => $estado, ':uuid_pedido' => $uuid_pedido])) {
        echo "El estado del pedido ha sido actualizado correctamente.";
    } else {
        echo "Error al actualizar el estado.";
    }

    // Cerrar la conexión (se hará automáticamente al final del script)
    $stmt = null;
    $pdo = null;

    // Redireccionar a la página anterior (opcional)
    header("Location: pedidos.php");
    exit();

} catch (PDOException $e) {
    // Manejar cualquier error de conexión o ejecución
    echo "Error: " . $e->getMessage();
}
?>

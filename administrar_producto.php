<?php
// Incluir el archivo de conexión
require_once 'conexion.php';

// Crear una instancia de la conexión
$conexion = new Conexion();

// Obtener todas las categorías para el menú desplegable
$sqlCategorias = "SELECT id_categoria, nombre FROM categorias";
$stmtCategorias = $conexion->prepare($sqlCategorias);
$stmtCategorias->execute();
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

// Obtener los datos del producto si se pasa un ID
$producto = null;
if (isset($_GET['id'])) {
    $idProducto = $_GET['id'];

    // Obtener los datos del producto, incluyendo la categoría (usamos JOIN con la tabla categorías)
    $sqlProducto = "
        SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.id_categoria, p.disponibilidad, c.nombre AS categoria_nombre
        FROM productos p
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
        WHERE p.id_producto = :id
    ";
    $stmtProducto = $conexion->prepare($sqlProducto);
    $stmtProducto->bindParam(':id', $idProducto);
    $stmtProducto->execute();
    $producto = $stmtProducto->fetch(PDO::FETCH_ASSOC);
}


try {
    // Crear una instancia de la conexión
    $conexion = new Conexion();

    // Verificar si se ha enviado el formulario de actualización
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $categoria = $_POST['categoria'];
        $disponibilidad = $_POST['disponibilidad'];

        // Actualizar los datos del producto en la base de datos
        $sql = "UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, 
                id_categoria = :categoria, disponibilidad = :disponibilidad WHERE id_producto = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':disponibilidad', $disponibilidad);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Redirigir de nuevo a la página principal
        header('Location: administrarMenu.php');
        exit();
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

?>

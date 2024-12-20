<?php
// Incluir la conexión PDO
require_once 'conexion.php';

try {
    // Crear una instancia de la clase Conexion
    $conexion = new Conexion();

    // Obtener los datos del formulario
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $categoria = $_POST["categoria"];

    // Verificar si se subió un archivo
    if ($_FILES["archivo"]) {
        $nombre_base = basename($_FILES["archivo"]["name"]);
        $nombre_final = date("m-d-y")."-".date("H-i-s")."-".$nombre_base;
        $ruta = "Images/".$nombre_final;

        // Subir el archivo
        if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $ruta)) {
            // Preparar la consulta SQL con marcadores de parámetros
            $insertarSQL = "INSERT INTO productos (nombre, descripcion, foto, precio, id_categoria) 
                            VALUES (:nombre, :descripcion, :ruta, :precio, :categoria)";
            $stmt = $conexion->prepare($insertarSQL);

            // Asignar los valores a los parámetros
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':ruta', $ruta);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':categoria', $categoria);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                $mensaje = "Se guardó con éxito";
                $redireccion = "administrarMenu.php";
            } else {
                $mensaje = "Error al guardar el producto.";
                $redireccion = null;
            }
        } else {
            $mensaje = "Error al subir archivo.";
            $redireccion = null;
        }
    } else {
        $mensaje = "No se subió ningún archivo.";
        $redireccion = null;
    }

} catch (PDOException $e) {
    $mensaje = "Error: " . $e->getMessage();
    $redireccion = null;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado de la Operación</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <!-- Modal -->
    <div class="modal fade" id="resultadoModal" tabindex="-1" aria-labelledby="resultadoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultadoModalLabel">Resultado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo $mensaje; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="cerrarModal()">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mostrar el modal al cargar la página
        $(document).ready(function() {
            $('#resultadoModal').modal('show');
        });

        // Función para cerrar el modal y redirigir si es necesario
        function cerrarModal() {
            $('#resultadoModal').modal('hide');
            <?php if ($redireccion): ?>
                window.location.href = "<?php echo $redireccion; ?>";
            <?php endif; ?>
        }
    </script>
</body>
</html>

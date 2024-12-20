<?php
//  conexión
require_once 'Conexion.php';

try {
    // Crear una nueva instancia de la clase Conexion
    $db = new Conexion();
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

// Obtener los datos del formulario
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$tipo_informe = $_POST['tipo_informe'];

// Inicializar variables para etiquetas y datos
$labels = [];
$data = [];

try {
    if ($tipo_informe == "platos_vendidos") {
        // Consulta para obtener los tres platos más vendidos y la suma de sus cantidades
        $sql = "SELECT pr.id_producto, pr.nombre AS nombre_producto, SUM(d.cantidad) AS total_ventas 
                FROM pedidos p
                JOIN detalle d ON p.uuid_pedido = d.id_detalle
                JOIN productos pr ON d.id_producto = pr.id_producto
                WHERE CONCAT(p.fecha, ' ', p.hora) BETWEEN :fecha_inicio AND :fecha_fin
                GROUP BY pr.id_producto, pr.nombre
                ORDER BY total_ventas DESC 
                LIMIT 5";

        $stmt = $db->prepare($sql);
        $stmt->execute([':fecha_inicio' => $fecha_inicio, ':fecha_fin' => $fecha_fin]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $labels[] = $row['nombre_producto'] . " (ID: " . $row['id_producto'] . ")";
            $data[] = (int)$row['total_ventas'];
        }
    } elseif ($tipo_informe == "clientes_frecuentes") {
        // Consulta para obtener los clientes más frecuentes
        $sql = "SELECT c.id_cliente AS id_cliente, c.nombre AS nombre_cliente, c.apellido AS apellido_cliente, COUNT(p.uuid_pedido) AS total_pedidos 
                FROM pedidos p
                JOIN clientes c ON p.id_cliente = c.id_cliente
                WHERE CONCAT(p.fecha, ' ', p.hora) BETWEEN :fecha_inicio AND :fecha_fin
                GROUP BY c.id_cliente, c.nombre, c.apellido
                ORDER BY total_pedidos DESC
                LIMIT 3";

        $stmt = $db->prepare($sql);
        $stmt->execute([':fecha_inicio' => $fecha_inicio, ':fecha_fin' => $fecha_fin]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $labels[] = $row['nombre_cliente'] . " " . $row['apellido_cliente'];
            $data[] = (int)$row['total_pedidos'];
        }
    } elseif ($tipo_informe == "metodo_de_pago") {
        // Consulta para obtener los métodos de pago más utilizados y la suma de sus montos
        $sql = "SELECT mp.nombre_metodo AS metodo_pago, SUM(p.monto) AS total_monto
        FROM pagos p
        JOIN metodos_pagos mp ON p.id_metodo = mp.id_metodo  -- Relación entre las tablas usando id_metodo
        WHERE p.fecha_pago BETWEEN :fecha_inicio AND :fecha_fin
        GROUP BY mp.id_metodo  -- Agrupamos por el id_metodo en la tabla metodos_pagos
        ORDER BY total_monto DESC
        LIMIT 4";

        $stmt = $db->prepare($sql);
        $stmt->execute([':fecha_inicio' => $fecha_inicio, ':fecha_fin' => $fecha_fin]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $labels[] = $row['metodo_pago'];
            $data[] = (float)$row['total_monto'];
        }
    }

    // Devolver los datos en formato JSON
    echo json_encode(['labels' => $labels, 'data' => $data]);

} catch (PDOException $e) {
    // Aquí capturamos el error y lo devolvemos en JSON
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
?>

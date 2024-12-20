<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();

include('Conexion.php');
$pdo = new Conexion();

$fechaInicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fechaFin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;
$estadoFilter = isset($_GET['estado_filter']) ? $_GET['estado_filter'] : null;

    // SQL base
        $sql = "
        SELECT 
        p.uuid_pedido, 
        CONCAT(c.nombre, ' ', c.apellido) AS nombre_completo, 
        GROUP_CONCAT(CONCAT(pr.nombre, ' (Cantidad: ', d.cantidad, ')') SEPARATOR '<br>') AS detalles, 
        p.tipo_entrega,
        c.direccion,
        c.telefono,
        p.estado, 
        p.fecha,
        p.hora,
        p.modificado,
        (CASE 
            WHEN p.tipo_entrega = 'Consumir en el Local' THEN 'Prioridad'
            ELSE 'Normal'
        END) AS prioridad,
        pa.estado AS estado_pago,
        m.nombre_metodo,
        pa.monto
    FROM pedidos p
    JOIN clientes c ON p.id_cliente = c.id_cliente
    JOIN detalle d ON p.uuid_pedido = d.uuid_pedido
    JOIN productos pr ON d.id_producto = pr.id_producto
    JOIN pagos pa ON p.uuid_pedido COLLATE utf8mb4_spanish_ci = pa.uuid_pedido COLLATE utf8mb4_spanish_ci
    JOIN metodos_pagos m ON pa.id_metodo = m.id_metodo
    ";

$conditions = [];
if ($fechaInicio && $fechaFin) {
    $conditions[] = "p.fecha BETWEEN :fechaInicio AND :fechaFin";
}
if ($estadoFilter && $estadoFilter !== 'TODOS') {
    $conditions[] = "p.estado = :estadoFilter";
}
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= "
    GROUP BY p.uuid_pedido
    ORDER BY 
        CASE WHEN p.tipo_entrega = 'Consumir en el Local' THEN 0 ELSE 1 END,
        CASE WHEN p.estado = 'PENDIENTE' THEN 0 ELSE 1 END,
        p.hora DESC
";

$stmt = $pdo->prepare($sql);

if ($fechaInicio && $fechaFin) {
    $stmt->bindParam(':fechaInicio', $fechaInicio);
    $stmt->bindParam(':fechaFin', $fechaFin);
}
if ($estadoFilter && $estadoFilter !== 'TODOS') {
    $stmt->bindParam(':estadoFilter', $estadoFilter);
}

// Ejecución y verificación
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($results)) {
    die('No hay datos disponibles para los filtros seleccionados.');
}

// Crear PDF
require_once('vendor/tecnickcom/tcpdf/tcpdf.php');

$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Boha Restaurante');
$pdf->SetTitle('Lista de Pedidos');
$pdf->SetHeaderData('', 0, 'Lista de Pedidos', 'Generado por Boha Restaurante');
$pdf->SetMargins(15, 27, 15);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->SetFont('helvetica', '', 12);
$pdf->AddPage();

// Encabezado
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Restaurante Boha', 0, 1, 'C');
$pdf->Cell(0, 10, 'Lista de Pedidos', 0, 1, 'C');

// Tabla
$pdf->SetFont('helvetica', '', 12);
$html = '<table border="1" cellpadding="5">
            <tr>
                <th>ID Pedido</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Prioridad</th>
                <th>Detalle</th>
                <th>Tipo de Entrega</th>
            </tr>';

foreach ($results as $row) {
    $html .= '<tr>
                <td>' . substr($row['uuid_pedido'], 0, 3) . '</td>
                <td>' . $row['nombre_completo'] . '</td>
                <td>' . date("d/m/Y", strtotime($row['fecha'])) . '</td>
                <td>' . $row['monto'] . '</td>
                <td>' . $row['prioridad'] . '</td>
                <td>' . $row['detalles'] . '</td>
                <td>' . $row['tipo_entrega'] . '</td>
              </tr>';
}
$html .= '</table>';

// Escribir HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Salida
ob_end_clean();
$pdf->Output('pedidos.pdf', 'D');
exit;
?>

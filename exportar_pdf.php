<?php
// Incluir la librería TCPDF
require_once('vendor/tecnickcom/tcpdf/tcpdf_autoconfig.php');  // Asegúrate de tener TCPDF instalado en tu servidor
require_once('vendor/tecnickcom/tcpdf/tcpdf.php');
// Crear un nuevo objeto TCPDF
$pdf = new TCPDF();

// Establecer información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Boha Restuarante');
$pdf->SetHeaderData('', 0, 'Lista de Pedidos', 'Generado por Boha Restaurante');
$pdf->SetMargins(15, 27, 15);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->SetTitle('Lista de Pedidos');
$pdf->SetSubject('Pedidos');


// Agregar una página
$pdf->AddPage();

// Establecer un título para la lista
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Restuarane Boha', 0, 1, 'C');
$pdf->Cell(0, 10, 'Lista de Pedidos', 0, 1, 'C');


// Establecer la fuente para el contenido
$pdf->SetFont('helvetica', '', 12);

// Aquí iría la consulta para obtener los pedidos de la base de datos
// Asegúrate de que tu conexión y consulta están correctamente configuradas
// Ejemplo con PDO:
// Conectar a la base de datos
include('conexion.php');  // Asegúrate de incluir tu archivo de conexión
$pdo = new Conexion(); 

// Obtener valores de filtro de fecha
$fechaInicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fechaFin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

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

    // Verificar si se aplican filtros por rango de fechas
    $conditions = [];
    if ($fechaInicio && $fechaFin) {
    $conditions[] = "p.fecha BETWEEN :fechaInicio AND :fechaFin";
    } else {
    // Si no hay rango de fechas, mostrar solo los pedidos del día actual
    $conditions[] = "p.fecha = CURDATE()";
    }

    // Añadir condiciones al SQL
    if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    // Agrupar por UUID de pedido para consolidar los detalles
    $sql .= "
    GROUP BY p.uuid_pedido
    ORDER BY prioridad DESC, p.uuid_pedido DESC";

    // Paso 3: Preparar y ejecutar la consulta
    $stmt = $pdo->prepare($sql);

    // Enlazar parámetros de fecha si están definidos
    if ($fechaInicio && $fechaFin) {
    $stmt->bindParam(':fechaInicio', $fechaInicio);
    $stmt->bindParam(':fechaFin', $fechaFin);
    }

    // Ejecutar la consulta
    $stmt->execute();

// Crear el contenido HTML con los resultados de la consulta
$html = '<table border="2" cellpadding="5">
            <tr>
                <th width="10%">ID Pedido</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Prioridad</th>
                <th>Detalle</th>
                <th>Tipo de Entrega</th>
            </tr>';

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $html .= '<tr>
                <td>'.substr($row['uuid_pedido'], 0, 3). '</td>
                <td>' . $row['nombre_completo'] . '</td>
                <td>' . date("d/m/Y", strtotime($row['fecha'])) . '</td>
                <td>' . $row['monto'] . '</td>
                <td>' . $row['prioridad'] . '</td>
                <td>'.  $row['detalles'].'</td>"
                <td style="white-space: nowrap;">' . $row['tipo_entrega'].'</td>
              </tr>';
}

$html .= '</table>';

// Escribir el contenido HTML
$pdf->writeHTML($html, true, false, true, false, '');

// Cerrar y generar el PDF
$pdf->Output('pedidos.pdf', 'D');  // 'D' indica que el archivo se descargará automáticamente
exit;
?>

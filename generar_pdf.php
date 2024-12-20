<?php
require_once('vendor/tecnickcom/tcpdf/tcpdf_autoconfig.php');
require_once('vendor/tecnickcom/tcpdf/tcpdf.php');
require_once('vendor/autoload.php');

//Para generar el grafico en el pdf de los informes 
//Se debe activar estoe xtension=gd en el hosting o local(xamp) 

// Paso para activar 
// Paso 1: cmd del S.O cololcar:-> php --ini

//windows
//Paso 2: en windows: Aprecera informacion esta informacion es importante Loaded Configuration File:    C:\xampp\php\php.ini
//Paso 3: Introducir esto C:\xampp\php\php.ini en la barra de direcciones en cualquier carpeta
//paso 4: Se abrira automaticamente un editor de texto y se debe buscar-> ;extension=gd 
//se le debe sacar el ;(Eliminar) y guardar el archivo...

//linux
//Paso 2: en windows: Aprecera informacion esta informacion-> Configuration File (php.ini) Path: /etc/php/8.1/cli
//Paso 3: Dirijirse a la ruta que se obtuvo anterirmente para editar el archivo.
//paso 4: Se abrira automaticamente un editor de texto y se debe buscar-> ;extension=gd 
//se le debe sacar el ;(Eliminar) y guardar el archivo...

// Deshabilitar la visualización de errores
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

// Obtener los datos enviados desde el cliente
$input = file_get_contents('php://input');
$data = json_decode($input, true);


// Verificar si los datos son válidos
if (isset($data['fecha_inicio']) && isset($data['fecha_fin']) && isset($data['tipo_informe'])) {
    // Obtener los datos del informe
    $fecha_inicio = $data['fecha_inicio'];
    $fecha_fin = $data['fecha_fin'];
    $tipo_informe = $data['tipo_informe'];

    // Conexión a la base de datos
    require_once 'Conexion.php';
    try {
        $db = new Conexion();
    } catch (PDOException $e) {
        die("Error en la conexión: " . $e->getMessage());
    }

    // Inicializar variables
    $labels = [];
    $data = [];

    // Consultas según el tipo de informe
    try {
        if ($tipo_informe == "platos_vendidos") {
            $encabezado_categoria = 'Productos';
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
                $labels[] = $row['nombre_producto'];
                $data[] = (int)$row['total_ventas'];
            }
        } elseif ($tipo_informe == "clientes_frecuentes") {
            $encabezado_categoria = 'Clientes';
            $sql = "SELECT c.id_cliente AS id_cliente, c.nombre AS nombre_cliente, c.apellido AS apellido_cliente, COUNT(p.uuid_pedido) AS total_pedidos 
                    FROM pedidos p
                    JOIN clientes c ON p.id_cliente = c.id_cliente
                    WHERE CONCAT(p.fecha, ' ', p.hora) BETWEEN :fecha_inicio AND :fecha_fin
                    GROUP BY c.id_cliente, c.nombre, c.apellido
                    ORDER BY total_pedidos DESC LIMIT 3";
            $stmt = $db->prepare($sql);
            $stmt->execute([':fecha_inicio' => $fecha_inicio, ':fecha_fin' => $fecha_fin]);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $labels[] = $row['nombre_cliente'] . " " . $row['apellido_cliente'];
                $data[] = (int)$row['total_pedidos'];
            }
        } elseif ($tipo_informe == "metodo_de_pago") {
            $encabezado_categoria = 'Método de Pagos';
            $sql = "SELECT mp.nombre_metodo AS metodo_pago, SUM(p.monto) AS total_monto
                    FROM pagos p
                    JOIN metodos_pagos mp ON p.id_metodo = mp.id_metodo
                    WHERE p.fecha_pago BETWEEN :fecha_inicio AND :fecha_fin
                    GROUP BY mp.id_metodo
                    ORDER BY total_monto DESC LIMIT 4";
            $stmt = $db->prepare($sql);
            $stmt->execute([':fecha_inicio' => $fecha_inicio, ':fecha_fin' => $fecha_fin]);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $labels[] = $row['metodo_pago'];
                $data[] = (float)$row['total_monto'];
            }
        }

        // Validar que existan datos antes de continuar
        if (empty($data)) {
            die('Error: No hay datos para generar el informe.');
        }

        // Calcular el valor máximo para la escala del gráfico
        $maxValue = max($data);

        // Configuración de la imagen
        $imageWidth = max(700, ($barWidth + $barSpacing + 10) * count($data) + $padding * 2); // Ancho dinámico
        $imageHeight = 500;
        $barWidth = 50;
        $barSpacing = 20;
        $padding = 100;

        // Crear imagen
        $image = imagecreatetruecolor($imageWidth, $imageHeight);

        // Colores
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $blue = imagecolorallocate($image, 50, 50, 200);
        $gray = imagecolorallocate($image, 200, 200, 200);

        // Fondo blanco
        imagefilledrectangle($image, 0, 0, $imageWidth, $imageHeight, $white);

        // Dibujar líneas horizontales de la cuadrícula
        $numLines = 5;
        $fontPath = 'C:/xampp/htdocs/BOHAFINAL/Fonts/arial.ttf';
        for ($i = 0; $i <= $numLines; $i++) {
            $y = $imageHeight - $padding - ($i * ($imageHeight - 2 * $padding) / $numLines);
            imageline($image, $padding, $y, $imageWidth - $padding, $y, $gray);
            $value = $i * $maxValue / $numLines;
            imagettftext($image, 10, 0, $padding - 70, $y + 5, $black, $fontPath, number_format($value, 0, ',', '.'));
        }

        // Dibujar el eje X y el eje Y
        imageline($image, $padding , $padding, $padding, $imageHeight - $padding, $black);
        imageline($image, $padding, $imageHeight - $padding, $imageWidth - $padding, $imageHeight - $padding, $black);

        // Dibujar las barras
        $barX = $padding + $barSpacing;
        foreach ($data as $index => $value) {
            $barHeight = ($value / $maxValue) * ($imageHeight - 2 * $padding);
            imagefilledrectangle(
                $image,
                $barX,
                $imageHeight - $padding - $barHeight,
                $barX + $barWidth,
                $imageHeight - $padding,
                $blue
            );

            // Etiquetas ajustadas debajo de cada barra
            $wrappedLabel = wordwrap($labels[$index], 10, "\n", true);
            $labelY = $imageHeight - $padding + 20;
            foreach (explode("\n", $wrappedLabel) as $line) {
                imagettftext($image, $fontSize = count($data) > 15 ? 8 : 11, 0, $barX, $labelY, $black, $fontPath, $line);
                $labelY += 12;
            }

            // Valores encima de cada barra
            $valueX = $barX + ($barWidth / 2) - 10;
            imagettftext($image, $fontSize = count($data) > 15 ? 8 : 11, 0, $valueX, $imageHeight - $padding - $barHeight - 10, $black, $fontPath, number_format($value, 0, ',', '.'));

            $barX += $barWidth + $barSpacing + 15;
        }

        // Guardar la imagen en un archivo temporal
        $chartPath = 'temp_chart.png';
        imagepng($image, $chartPath);
        imagedestroy($image);

        // Crear el PDF
        $pdf = new TCPDF();
        $pdf->SetCreator('Boha Restaurante');
        $pdf->SetAuthor('Boha Restaurante');
        $pdf->SetTitle('Informe de Ventas');
        $pdf->SetHeaderData('', 0, 'Informe de Ventas', 'Generado por Boha Restaurante');
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetAutoPageBreak(TRUE, 25);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->AddPage();

        // Título
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Informe de Ventas', 0, 1, 'C');
        $pdf->Ln(10);

        // Fechas
        $fecha_inicio = DateTime::createFromFormat('Y-m-d\TH:i', $fecha_inicio)->format('d/m/Y');
        $fecha_fin = DateTime::createFromFormat('Y-m-d\TH:i', $fecha_fin)->format('d/m/Y');
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, "Desde $fecha_inicio hasta $fecha_fin", 0, 1, 'C');
        $pdf->Ln(10);

        // Tabla de datos
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(90, 10,$encabezado_categoria, 1);
        $pdf->Cell(90, 10, 'Total', 1);
        $pdf->Ln();
        $pdf->SetFont('helvetica', '', 12);
        for ($i = 0; $i < count($labels); $i++) {
            $pdf->Cell(90, 10, $labels[$i], 1);
            $pdf->Cell(90, 10, number_format($data[$i], 0, ',', '.'), 1);
            $pdf->Ln();
        }

        // Incluir la imagen del gráfico en el PDF
        $pdf->Ln(10);
        $pdf->Image($chartPath, 20, $pdf->GetY(), 170, 100, 'PNG');

        // Salvar el archivo PDF
        ob_clean();
        $pdf->Output('Informes.pdf', 'D');

        // Eliminar el archivo temporal del gráfico
        unlink($chartPath);

        // envia un msj de exitos al front
        exit(json_encode(['success' => true]));
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Parámetros inválidos']);
}
?>



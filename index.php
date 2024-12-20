<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Incluir el archivo donde se maneja la conexión y el modelo de reservas
require_once 'Conexion.php';
require_once 'reserva.php';
require_once 'cliente.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boha Restaurante</title>
    <!-- Script para actualizar la página cada 15 segundos -->
    <link rel="icon" type="image/png" href="Images/logog.jpg" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reserva_index.css">
    <style>
        /* Estilo para la lista de pedidos pequeña */
        .tabla-pedidos-pequena {
            font-size: 0.8em;
            border-collapse: collapse;
            width: 100%;
        }

        .tabla-pedidos-pequena th, .tabla-pedidos-pequena td {
            border: 1px solid #ac920452;
            padding: 8px;
        }

        .tabla-pedidos-pequena th {
            padding-top: 15px;
            padding-bottom: 15px;
            text-align: left;
            background-color: #d3c60bf6;
            color: black;
        }
        .prioridad {
            color: yellow;
        }

        .normal {
            color: white;
        }
        @media screen and (max-width: 768px) {
            .tabla-pedidos-pequena {
            font-size: 0.7em;
            }
        }   
    </style>
</head>
<body>
    <header id="banner-container">
        <img src="Images/bannner.png" alt="Banner de Boha Restaurante" id="banner">
        <h1>Administración de Restaurante</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="pedidos.php">Ver Pedidos</a></li>
                <li><a href="vista_reserva.php">Ver Reservas</a></li>
                <li><a href="administrarMenu.php">Administrar Menú</a></li>
                <li><a href="informes.html">Generar Informes</a></li>
            </ul>
        </nav>
    </header>

    <div class="contenedor">
         <!-- Sección para la lista de reservas en formato de lista -->
        <div class="lista-reservas">
            <h2>Reservas Recientes</h2>
            <?php
            try {
                // Conexión a la base de datos usando la clase Conexion
                $db = new Conexion();

                // Obtener datos de reservas
                $fechaActual = date('Y-m-d');
                $sql = "SELECT r.id_reserva, r.fecha, r.hora, r.motivo, r.cantidad_personas, r.estado, 
                            c.nombre, c.apellido, c.telefono
                        FROM reservas r
                        JOIN clientes c ON r.id_cliente = c.id_cliente
                        WHERE r.fecha >= :fechaActual AND r.archivada = 0
                        ORDER BY r.fecha DESC"; // Cambia GROUP BY a ORDER BY
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':fechaActual', $fechaActual);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    // Mostrar cada reserva como un bloque de información en formato lista
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='reserva-item'>
                                <a href='reserva_index.php?id_reserva={$row['id_reserva']}' class='archivar-btn'>
                                    <img src='Images/archivar.ico' alt='Archivar' class='icono-archivar'>
                                </a>
                                <p><strong>Cliente:</strong> {$row['nombre']} {$row['apellido']}</p>
                                <p><strong>Teléfono:</strong> {$row['telefono']}</p>
                                <p><strong>Fecha:</strong> " . date("d/m/y", strtotime($row['fecha'])) . "</p>
                                <p><strong>Hora:</strong> " . date("H:i", strtotime($row['hora'])) . "</p>
                                <p><strong>Motivo:</strong> {$row['motivo']}</p>
                                <p><strong>Cantidad de Personas:</strong> {$row['cantidad_personas']}</p>
                                <p><strong>Estado:</strong> {$row['estado']}</p>
                                </div>";
                    }
                } else {
                    echo "<p>No hay reservas disponibles.</p>";
                }
            } catch (PDOException $e) {
                echo "<p>Error al cargar las reservas: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>
        <script>
            function ocultarReserva(icon) {
                const reservaItem = icon.parentElement; // Obtiene el elemento contenedor de la reserva
                reservaItem.style.display = 'none'; // Oculta la reserva
            }

        
        </script>
        

</script>

        <!-- Sección para la lista de pedidos -->
<div class="lista-pedidos">
    <h2>Pedidos Recientes</h2>
    <table class="tabla-pedidos-pequena">
        <thead>
            <tr>
                <th>Prioridad</th>
                <th>ID del Pedido</th>
                <th>Cliente</th>
                <th>Tipo de Entrega</th>
                <th>Domicilio</th>
                <th>Telefono</th>
                <th>Detalle</th>
                <th>Estado</th>
                <th>Fecha y Hora</th> 
                <th>Pago</th> <!-- Solo una columna para el monto y el método de pago -->
            </tr>
        </thead>
        <tbody>
        <?php
// Incluye la clase de conexión
require_once 'Conexion.php';

try {
    // Paso 1: Conexión a la base de datos usando la clase Conexion
    $db = new Conexion();

    // SQL base para la consulta de los pedidos
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

    // Condiciones para filtrar los pedidos
    $conditions = [];
    
    // Filtro para los pedidos del día actual
    $conditions[] = "p.fecha = CURDATE()";

    // Excluir los pedidos que tienen estado 'ENTREGADO'
    $conditions[] = "p.estado != 'ENTREGADO'";

    // Añadir condiciones al SQL si existen
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    // Ordenar por prioridad (Consumir en el Local primero), luego por estado y por hora
    $sql .= "
    GROUP BY p.uuid_pedido
    ORDER BY 
        CASE 
            WHEN p.tipo_entrega = 'Consumir en el Local' THEN 0
            ELSE 1
        END, 
        CASE 
            WHEN p.estado = 'PENDIENTE' THEN 0
            ELSE 1
        END,
        p.hora DESC, 
        p.uuid_pedido DESC
    ";

    // Preparar y ejecutar la consulta
    $stmt = $db->prepare($sql);

    $stmt->execute();

    // Renderizar los resultados en HTML
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Determinar color de fondo según el campo 'modificado'
            $fondo = ($row['modificado'] > 0) ? 'orange' : 'transparent';

            // Inicia la fila con el color de fondo definido
            echo "<tr style='background-color: {$fondo};'>";

            // Determinar color de texto según la prioridad
            $color = ($row['prioridad'] === 'Prioridad') ? 'yellow' : 'white';
            echo "<td style='color: {$color};'>{$row['prioridad']}</td>";

            // columna del id del pedido, nombre y apellido del cliente y direccion del cliente
            echo "<td>" . substr($row['uuid_pedido'], 0, 3) . "</td>";
            echo "<td>" . htmlspecialchars($row['nombre_completo']) . "</td>";
            echo "<td>{$row['tipo_entrega']}</td>";
            echo "<td>" . htmlspecialchars($row['direccion']) . "</td>";

            // Campo de teléfono con enlace a WhatsApp
            $telefono = htmlspecialchars($row['telefono']);
            echo "<td>";
            echo "{$telefono} <a href='https://wa.me/{$telefono}' target='_blank'><strong><br>Contactar</strong></a>";
            echo "</td>";

            // Producto y cantidad
            echo "<td>{$row['detalles']}</td>";

            // Mostrar el estado con color de fondo según el valor del estado
            $estado = $row['estado'];
            switch ($estado) {
                case 'PENDIENTE':
                    $colorEstado = '#FFFFFF';  // Blanco
                    break;
                case 'EN PROCESO':
                    $colorEstado = '#6495ED'; // Azul
                    break;
                case 'LISTO':
                    $colorEstado = 'blue';    // Azul oscuro
                    break;
                case 'EN CAMINO':
                    $colorEstado = '#008080'; // Verde oscuro
                    break;
                case 'ENTREGADO':
                    $colorEstado = '#FFFF00'; // Amarillo
                    break;
                case 'CANCELADO':
                    $colorEstado = '#FF0000';
                    break;
                default:
                    $colorEstado = '#CCCCCC'; // Gris predeterminado
                    break;
            }

            // Solo mostrar el estado, no se puede seleccionar
            echo "<td style='background-color: {$colorEstado}; color: black; font-weight: bold;'>";
            echo htmlspecialchars($row['estado']); // Muestra el estado como texto
            echo "</td>";

            // Fecha y hora formateadas
            $fecha_formateada = date("d/m/Y", strtotime($row['fecha']));
            $hora_formateada = date("H:i", strtotime($row['hora']));
            echo "<td>{$fecha_formateada} {$hora_formateada}</td>";

            // Monto y método de pago
            $monto_formateado = number_format($row['monto'], 2, ',', '.');
            $colorPago = (strcasecmp($row['estado_pago'], 'Pendiente') === 0) ? 'lightcoral' : 'lightgreen';

            echo "<td style='background-color: {$colorPago}; color: black; font-weight: bold;'>";
            echo "<div>$ {$monto_formateado}</div>";
            echo "<div>" . htmlspecialchars($row['nombre_metodo']) . "</div>";
            echo "</td>";

            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No se encontraron pedidos pendientes de entrega para el día de hoy.</td></tr>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

        </tbody>
    </table>
</div>

        <script>
            setTimeout(function(){
                window.location.reload(1);
            }, 15000); // 15000 ms = 15 segundos
        </script>
</body>
</html>

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Pedidos</title>
    <link rel="icon" type="image/png" href="Images/logog.jpg" />
    <link rel="stylesheet" href="css/style.css">
    <script>
        // Variable para controlar si el modal está abierto
        let modalAbierto = false;
        let autoReloadTimeout;

        // Función para manejar la actualización automática
        function manejarActualizacionAutomatica() {
            if (!modalAbierto) { // Solo actualizar si el modal está cerrado
                window.location.reload();
            }
            // Reprogramar la actualización automática
            autoReloadTimeout = setTimeout(manejarActualizacionAutomatica, 15000); // 15000 ms = 15 segundos
        }

        function actualizarEstado(form) {
            form.submit(); // Envía el formulario automáticamente cuando se selecciona un nuevo estado
        }
    </script>
    <style>
        /* Estilos del modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    align-items: center;
    justify-content: center;
}
.modal-content {
    background-color: rgba(50, 50, 50, 0.7); /* Gris oscuro con 80% de opacidad */
    color: white; /* Texto blanco para contrastar con el fondo oscuro */
    padding: 20px;
    border-radius: 8px;
    width: 300px;
    text-align: center;
}
.close {
    color: #aaa;
    float: right;
    font-size: 24px;
    cursor: pointer;
}
.modal input[type="date"] {
    width: 80%;
    padding: 5px;
    margin: 10px 0;
}
.modal label {
    color: white; /* Cambia el color a negro para mejor visibilidad */
    font-weight: bold; /* Añade negrita a las etiquetas */
}
#exportPdf-button {
    padding: 8px 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 4px;
}
#exportPdf-button:hover {
    background-color: #45a049;
}
#filter-button {
    padding: 8px 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 4px;
}
#filter-button:hover {
    background-color: #45a049;
}
.reset-button {
    margin-top: 10px; /* Espacio superior para separar del botón aplicar */
    padding: 8px 16px;
    background-color: #f44336; /* Rojo para el botón de quitar filtros */
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 4px;
}
.reset-button:hover {
    background-color: #d32f2f; /* Hover más oscuro */
}
#pedidos {
    margin: 20px 0; /* Añade un margen vertical para separar del resto del contenido */
}


#estado_filter {
    background-color: rgba(255, 255, 0, 0.6); /* Amarillo medio, con transparencia */
    border: 1px solid #ccc; /* Borde gris claro */
    border-radius: 12px; /* Bordes redondeados */
    padding: 8px 16px; /* Espaciado interno */
    font-size: 14px; /* Tamaño de fuente */
    color: #333; /* Color de texto */
    cursor: pointer; /* Cambia el cursor al pasar por encima */
    transition: background-color 0.3s ease; /* Transición suave para el fondo */
}

#estado_filter:hover {
    background-color: rgba(255, 255, 0, 0.8); /* Cambia a un amarillo más fuerte al pasar el ratón */
}

#estado_filter:focus {
    outline: none; /* Elimina el borde de enfoque predeterminado */
    background-color: rgba(255, 255, 0, 1); /* Amarillo sin transparencia cuando está enfocado */
}



    </style>
    <script>
        // Script para abrir y cerrar el modal
        function openModal() {
            document.getElementById("dateModal").style.display = "flex";
            modalAbierto = true; // Cambiar estado a abierto
            clearTimeout(autoReloadTimeout); // Detener el timeout de recarga automática
        }

        function closeModal() {
            document.getElementById("dateModal").style.display = "none";
            modalAbierto = false; // Cambiar estado a cerrado
            manejarActualizacionAutomatica(); // Reanudar la actualización automática
        }

        function submitFilter() {
            document.getElementById("filterForm").submit();
        }

        function resetFilters() {
            window.location.href = "pedidos.php"; // Redirigir a la página sin filtros
        }
    </script>
</head>

<body>
    <header id="banner-container">
        <img src="Images/bannner.png" alt="Banner de Boha Restaurante" id="banner">
        <h1>PEDIDOS</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="pedidos.php">Ver Pedidos</a></li>
                <li><a href="vista_reserva.php">Ver Reservas</a></li>
                <li><a href="administrarMenu.php">Administrar productos</a></li>
                <li><a href="informes.html">Generar Informes</a></li>
            </ul>
        </nav>
    </header>
    <div class="contenedor">
    <section id="pedidos">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin: 0;">Lista de pedidos</h2>

            <div class="button-group">
                <!-- Botón Exportar PDF -->
                <button id="exportPdf-button">
                    Exportar a PDF
                    <i class="fas fa-file-pdf"></i> <!-- Ícono a la derecha -->
                </button>
                <!-- Botón para abrir el modal de filtro alineado a la derecha -->
                <button id="filter-button" onclick="openModal()">Filtrar por Fecha</button>
            </div>
        </div>


            <!-- Modal de selección de fechas -->
            <div id="dateModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <form id="filterForm" method="GET" action="pedidos.php">
                        <label for="fecha_inicio">Fecha de Inicio:</label> <!-- Etiqueta para Fecha de Inicio -->
                        <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : ''; ?>">
                        
                        <label for="fecha_fin">Fecha de Fin:</label> <!-- Etiqueta para Fecha de Fin -->
                        <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : ''; ?>">
                        
                        <button type="button" onclick="submitFilter()">Aplicar Filtros</button>
                        <button type="button" class="reset-button" onclick="resetFilters()">Quitar Filtros</button> <!-- Botón para quitar filtros -->
                    </form>
                </div>
            </div>


<script>
function aplicarFiltro() {
    const select = document.getElementById('estado_filter');
    const form = document.getElementById('filtroForm');
    form.submit(); // Esto envía automáticamente el formulario con la opción seleccionada
}
</script>


        <table>
            <thead>
                <tr>
                    <th>Prioridad</th>
                    <th>ID del Pedido</th>
                    <th>Cliente</th>
                    <th>Tipo de Entrega</th>
                    <th>Domicilio</th>
                    <th>Telefono</th>
                    <th>Detalle</th>
                    <!-- Filtro de Estado dentro de la tabla -->
            <th colspan="" style="text-align: right;">
            <form method="GET" action="pedidos.php" style="margin-bottom: 20px;" id="filtroForm">
    <label for="estado_filter"></label>
    <select name="estado_filter" id="estado_filter" onchange="aplicarFiltro()">
        <option value="TODOS" <?php echo (!isset($_GET['estado_filter']) || $_GET['estado_filter'] == 'TODOS') ? 'selected' : ''; ?>>Estados</option>
        <option value="CANCELADO" <?php echo (isset($_GET['estado_filter']) && $_GET['estado_filter'] == 'CANCELADO') ? 'selected' : ''; ?>>Cancelado</option>
        <option value="PENDIENTE" <?php echo (isset($_GET['estado_filter']) && $_GET['estado_filter'] == 'PENDIENTE') ? 'selected' : ''; ?>>Pendiente</option>
        <option value="EN PROCESO" <?php echo (isset($_GET['estado_filter']) && $_GET['estado_filter'] == 'EN PROCESO') ? 'selected' : ''; ?>>En Proceso</option>
        <option value="LISTO" <?php echo (isset($_GET['estado_filter']) && $_GET['estado_filter'] == 'LISTO') ? 'selected' : ''; ?>>Listo</option>
        <option value="EN CAMINO" <?php echo (isset($_GET['estado_filter']) && $_GET['estado_filter'] == 'EN CAMINO') ? 'selected' : ''; ?>>En Camino</option>
        <option value="ENTREGADO" <?php echo (isset($_GET['estado_filter']) && $_GET['estado_filter'] == 'ENTREGADO') ? 'selected' : ''; ?>>Entregado</option>
    </select>
</form>
            </th>
                    <th>Fecha y Hora</th> 
                    <th>Pago</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Incluye la clase de conexión
            require_once 'Conexion.php';

            try {
                // Paso 1: Conexión a la base de datos usando la clase Conexion
                $db = new Conexion();

                // Obtener valores de filtro de fecha y estado
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

                // Construcción dinámica de condiciones
                $conditions = [];
                if ($fechaInicio && $fechaFin) {
                    $conditions[] = "p.fecha BETWEEN :fechaInicio AND :fechaFin";
                } elseif (!$fechaInicio && !$fechaFin) {
                    // Si no hay rango de fechas, mostrar solo los pedidos del día actual
                    $conditions[] = "p.fecha = CURDATE()";
                }

                if ($estadoFilter && $estadoFilter !== 'TODOS') {
                    $conditions[] = "p.estado = :estadoFilter";
                }

// Excluir los pedidos con estado 'ENTREGADO' por defecto
if (!$estadoFilter || $estadoFilter !== 'ENTREGADO') {
    $conditions[] = "p.estado != 'ENTREGADO'";
}

                // Añadir condiciones al SQL si existen
                if (!empty($conditions)) {
                    $sql .= " WHERE " . implode(" AND ", $conditions);
                }

                $sql .= "
                GROUP BY p.uuid_pedido, p.hora -- Agregamos p.hora al GROUP BY
ORDER BY 
    -- Prioridad: 'Consumir en el Local' primero
    CASE 
        WHEN p.tipo_entrega = 'Consumir en el Local' THEN 0
        ELSE 1
    END, 
    -- Estado: 'PENDIENTE' primero, luego los otros
    CASE 
        WHEN p.estado = 'PENDIENTE' THEN 0
        ELSE 1
    END,
    -- Para mover los pedidos 'CANCELADO' al final de la lista
    CASE 
        WHEN p.estado = 'CANCELADO' THEN 1
        ELSE 0
    END,
    -- Ordenar por hora en orden descendente
    p.hora DESC
                ";

                // Preparar y ejecutar la consulta
                $stmt = $db->prepare($sql);

                // Enlazar parámetros de fecha si están definidos
                if ($fechaInicio && $fechaFin) {
                    $stmt->bindParam(':fechaInicio', $fechaInicio);
                    $stmt->bindParam(':fechaFin', $fechaFin);
                }

                // Enlazar el filtro de estado si está definido
                if ($estadoFilter && $estadoFilter !== 'TODOS') {
                    $stmt->bindParam(':estadoFilter', $estadoFilter);
                }

                $stmt->execute();

                // Renderizar resultados en HTML
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if ($row['estado'] === 'CANCELADO') {
                            $fondo = 'red';
                        } else {
                            $fondo = 'transparent'; 
                        if ($row['modificado'] > 0 && $row['estado'] !== 'CANCELADO') {
                            $fondo = 'orange';}
                        }


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
                        $estado = $row['estado'];
switch ($estado) {
    case 'PENDIENTE':
        $colorEstado = '#FFFFFF';
        break;
    case 'EN PROCESO':
        $colorEstado = '#6495ED';
        break;
    case 'LISTO':
        $colorEstado = 'blue';
        break;
    case 'EN CAMINO':
        $colorEstado = '#008080';
        break;
    case 'ENTREGADO':
        $colorEstado = '#FFFF00';
        break;
    case 'CANCELADO':
        $colorEstado = '#FF0000';
        break;
    default:
        $colorEstado = '#CCCCCC'; // Valor predeterminado
        break;
}


                        

                        // Selector para actualizar el estado
                        echo "<td style='background-color: {$colorEstado}; color: black; font-weight: bold;'>";
                        echo "<form action='actualizar_estado.php' method='post' style='margin: 0;'>";
                        echo "<input type='hidden' name='uuid_pedido' value='" . htmlspecialchars($row['uuid_pedido']) . "'>";
                        echo "<select name='estado' onchange='actualizarEstado(this.form)' style='width: 100%;'>";
                        $estados = ['PENDIENTE', 'EN PROCESO', 'LISTO', 'EN CAMINO', 'ENTREGADO','CANCELADO'];
                        foreach ($estados as $estadoOption) {
                            $selected = ($estadoOption === $row['estado']) ? 'selected' : '';
                            echo "<option value='{$estadoOption}' {$selected}>{$estadoOption}</option>";
                        }
                        echo "</select>";
                        echo "</form>";
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
                    echo "<tr><td colspan='10'>No se encontraron pedidos.</td></tr>";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>

                </tbody>
            </table>
        </section>
    </div>
    <script>
        document.getElementById("exportPdf-button").addEventListener("click", function() {
        // Enviar una solicitud AJAX al servidor para generar el PDF
        window.location.href = "exportar_pdf.php";  // El archivo PHP que generará el PDF
        });
    </script>
    <script>
        setInterval(function() {
            location.reload(); // Recarga la página
        },60000); // 60000 milisegundos = 1 minuto
    </script>
</body>
</html>



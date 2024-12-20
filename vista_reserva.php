<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir el archivo donde se maneja la conexión y el modelo de reservas
require_once 'Conexion.php';
require_once 'reserva.php';
require_once 'reserva_controlador.php';

// Crear instancia del controlador y obtener reservas para el calendario
$reservaControlador = new ReservaControlador(new Conexion());
$reservasPorFecha = $reservaControlador->obtenerReservasParaCalendario();

// Crear una instancia de la clase de conexión y reservas
$conexion = new Conexion();
$reservaModel = new Reserva($conexion);

// Obtener la fecha actual
$fechaActual = date('Y-m-d');

// Obtener todas las reservas cuya fecha sea igual o posterior a la actual
$reservas = $reservaModel->obtenerReservasPosteriores($fechaActual);


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Reservas</title>
    <link rel="icon" type="image/png" href="Images/logog.jpg" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/calendario.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js'></script>
    
</head>


<body>

<header id="banner-container">
    <img src="Images/bannner.png" alt="Banner de Boha Restaurante" id="banner">
    <h1>RESERVAS</h1>
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
    <!-- Lista de Reservas -->
    <div class="lista-reservas">
        <h2>Reservas</h2>
        <button class="btn" onclick="abrirModal()">Agregar Reserva</button>
        <?php if (!empty($reservas) ): ?>
            <?php foreach ($reservas as $reserva): ?>
                <div class="reserva-item">
                    <div>
                    <a href="reservas_historicas.php?id_reserva=<?php echo htmlspecialchars($reserva['id_reserva']); ?>" class="archivar-btn">
                            <img src='Images/archivar.ico' alt='Archivar' class='icono-archivar'>
                        </a>
                        <p>Cliente: <?php echo $reserva['nombre'] . ' ' . $reserva['apellido']; ?></p>
                        <p>Teléfono: <?php echo $reserva['telefono']; ?></p>
                        <p>Fecha: <?php echo date("d/m/y", strtotime($reserva['fecha'])); ?></p>
                        <p>Motivo: <?php echo ucfirst($reserva['motivo']); ?></p>
                        <p>Cantidad de Personas: <?php echo $reserva['cantidad_personas']; ?></p>
                        <p>Hora: <?php echo date("H:i", strtotime($reserva['hora'])); ?></p>
                        <p>Estado: <?php echo ucfirst($reserva['estado']); ?></p>
                    </div>
                    <div class="botones">
                        <button class="confirmar" onclick="abrirModalConfirmacion(<?php echo $reserva['id_reserva']; ?>, 'confirmar')">Confirmar</button>
                        <button class="cancelar" onclick="abrirModalConfirmacion(<?php echo $reserva['id_reserva']; ?>, 'cancelar')">Cancelar</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay reservas disponibles.</p>
        <?php endif; ?>
        <button class="btn" onclick="abrirModalHistorico()">Ver Reservas Históricas</button>
    </div>

    <!-- Calendario de reservas -->
    <div id="calendar"></div>
</div>

<!-- Modal para agregar reservas -->
<div id="modalReserva" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal()">&times;</span>
        <form id="formAgregarReserva" method="POST" action="reserva_controlador.php">
            <h2 style="color: #d4af37;">Agregar Reserva</h2>
            <label for="nombre" class="label-estilo">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="input-ancho" required>

            <label for="apellido" class="label-estilo">Apellido:</label>
            <input type="text" id="apellido" name="apellido" class="input-ancho" required>

            <label for="email" class="label-estilo">Correo electrónico:</label>
            <input type="email" id="email" name="email" class="input-ancho" required>

            <label for="telefono" class="label-estilo">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" class="input-ancho">

            <label for="fecha" class="label-estilo">Fecha:</label>
            <input type="date" id="fecha" name="fecha" class="input-ancho" required>

            <label for="hora" class="label-estilo">Hora:</label>
            <input type="time" id="hora" name="hora" required>

            <label for="motivo" class="label-estilo">Motivo:</label>
            <input type="text" id="motivo" name="motivo" class="input-ancho" required>

            <label for="cantidad_personas" class="label-estilo">Cantidad de personas:</label>
            <input type="number" id="cantidad_personas" name="cantidad_personas" class="input-ancho" required>

            <button type="submit">Agregar Reserva</button>
        </form>
    </div>
</div>

<!-- Modal de confirmación de acciones -->
<div id="modalConfirmacion" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModalConfirmacion()">&times;</span>
        <h2 class="modalh2-estilo">Confirmar acción</h2>
        <p class="label-estilo" id="mensajeConfirmacion"></p>
        <button id="btnConfirmarAccion" class="confirmar" type="button">Confirmar</button>
        <button class="cancelar" onclick="cerrarModalConfirmacion()">Cancelar</button>
    </div>
</div>

<div id="modalHistorico" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModalHistorico()">&times;</span>
        <h2>Reservas Históricas</h2>
        <!-- Contenedor para los filtros en vertical -->
        <div class="filtros-container">
            <!-- Contenedor para mostrar mensajes de error -->
            <div id="mensajeError" class="mensaje-error" style="display: none;"></div>
            <!-- Filtro de Estado -->
            <div class="filtro">
                <label for="filtroEstado">Filtrar por estado:</label>
                <select id="filtroEstado">
                    <option value="todas">Todas</option>
                    <option value="confirmado">Confirmado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>

            <!-- Filtro de Rango de Fechas -->
            <div class="filtro">
                <label for="fechaInicio">Fecha Inicio:</label>
                <input type="date" id="fechaInicio">
            </div>

            <div class="filtro">
                <label for="fechaFin">Fecha Fin:</label>
                <input type="date" id="fechaFin">
            </div>

            <!-- Botón para aplicar filtros -->
            <div class="filtro">
                <button onclick="filtrarReservasHistoricas()" class="btn-filtrar">Aplicar Filtros</button>
            </div>
        </div>

        <div id="listaHistorico">
            <!-- Las reservas históricas se cargarán aquí -->
        </div>
    </div>
</div>

<script>
    let reservaIdGlobal = null;
    let accionGlobal = null;

    // Abrir el modal de confirmación
    function abrirModalConfirmacion(id_reserva, accion) {
        reservaIdGlobal = id_reserva;
        accionGlobal = accion;
        const mensaje = `¿Estás seguro de que quieres ${accion === 'confirmar' ? 'confirmar' : 'cancelar'} esta reserva?`;
        document.getElementById('mensajeConfirmacion').innerText = mensaje;
        document.getElementById('modalConfirmacion').style.display = 'block';
    }

    // Cerrar el modal de confirmación
    function cerrarModalConfirmacion() {
        document.getElementById('modalConfirmacion').style.display = 'none';
    }

    // Función para confirmar la acción en el modal
    document.getElementById('btnConfirmarAccion').addEventListener('click', function() {
        event.preventDefault();
        gestionarReserva(reservaIdGlobal, accionGlobal);
        cerrarModalConfirmacion();
    });

    // Función para abrir el modal de agregar reserva
    function abrirModal() {
        document.getElementById('modalReserva').style.display = 'block';
    }

    // Función para cerrar el modal de agregar reserva
    function cerrarModal() {
        document.getElementById('modalReserva').style.display = 'none';
    }

    // Función para gestionar reservas (confirmar o cancelar)
    function gestionarReserva(id_reserva, accion) {
            // Crear un formulario dinámico para enviar los datos al backend
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'reserva_controlador.php';

            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'id_reserva';
            inputId.value = id_reserva;

            const inputAccion = document.createElement('input');
            inputAccion.type = 'hidden';
            inputAccion.name = 'accion';
            inputAccion.value = accion;

            form.appendChild(inputId);
            form.appendChild(inputAccion);
            document.body.appendChild(form);

            // Enviar el formulario
            form.submit();
    }
    // Cerrar el modal si el usuario hace clic fuera del contenido del modal
    window.onclick = function(event) {
    let modals = [document.getElementById('modalReserva'), document.getElementById('modalHistorico'), document.getElementById('modalReservasFecha')];
    modals.forEach(modal => {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
    }
    function abrirModalHistorico() {
        cargarReservasHistoricas();
        document.getElementById('modalHistorico').style.display = 'block';
    }

    function cerrarModalHistorico() {
        document.getElementById('modalHistorico').style.display = 'none';
    }

    function cargarReservasHistoricas() {
        fetch('reservas_historicas.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('listaHistorico').innerHTML = data;
            })
            .catch(error => console.error('Error al cargar reservas históricas:', error));
    }

    function filtrarReservasHistoricas() {
        const estado = document.getElementById('filtroEstado').value;
        const fechaInicio = document.getElementById("fechaInicio").value;
        const fechaFin = document.getElementById("fechaFin").value;
        const mensajeError = document.getElementById("mensajeError");

        // Validación de fechas
        if (fechaInicio && fechaFin && new Date(fechaInicio) > new Date(fechaFin)) {
            mensajeError.innerText = "La fecha de inicio debe ser anterior o igual a la fecha de fin.";
            mensajeError.style.display = "block";
            return;
        } else {
            mensajeError.style.display = "none"; // Ocultar mensaje si no hay error
        }

        // Construcción de la URL con parámetros
        const url = `reservas_historicas.php?estado=${estado}&fechaInicio=${fechaInicio}&fechaFin=${fechaFin}`;
        
        fetch(url)
            .then(response => response.text())
            .then(data => {
                document.getElementById('listaHistorico').innerHTML = data;
            })
            .catch(error => console.error('Error al filtrar reservas históricas:', error));
    }
    
</script>

<!-- Modal para detalles de reservas en una fecha -->
<div id="modalReservasFecha" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModalReservasFecha()">&times;</span>
        <h2>Reservas para la fecha seleccionada</h2>
        <div id="detalleReservasFecha">
            <!-- Los detalles de las reservas se cargarán aquí dinámicamente -->
        </div>
    </div>
</div>

<script>
    var reservasPorFecha = <?php echo json_encode($reservasPorFecha); ?>;
    
    // Inicializar FullCalendar
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            buttonText: {
                today: 'Mes Actual' // Cambia el texto de "today" a "Hoy"
            },
            contentHeight: 'auto',
            fixedWeekCount: false,
            events: Object.keys(reservasPorFecha).map(function(fecha) {
                // Lógica para determinar el color
                var color = 'gold'; // Valor predeterminado para "pendientes" o "cancelados"
                
                // Comprobar si todas las reservas de ese día están confirmadas
                var todasConfirmadas = reservasPorFecha[fecha].every(function(reserva) {
                    return reserva.estado === 'confirmado'; // Aquí 'confirmado' es el estado que defines
                });
                
                if (todasConfirmadas) {
                    color = 'green'; // Si todas las reservas son confirmadas, usa verde
                } else {
                    // Si alguna reserva está pendiente o cancelada, usa amarillo
                    reservasPorFecha[fecha].forEach(function(reserva) {
                        if (reserva.estado === 'cancelado') {
                            color = 'red'; // Si hay alguna cancelada, usa rojo
                        } else if (reserva.estado === 'pendiente') {
                            color = 'yellow'; // Si hay alguna pendiente, usa amarillo
                        }
                    });
                }

                return {
                    title: reservasPorFecha[fecha].length + ' reservas', // El número de reservas
                    start: fecha,
                    color: color, // Asignar el color calculado
                    textColor: 'Black' // Cambia el color del texto para que sea visible sobre el fondo
                };
            }),
            eventRender: function(info) {
                // Aquí puedes aplicar estilos adicionales a los eventos
                var eventEl = info.el; // El evento en el DOM
                eventEl.style.display = 'flex';
                eventEl.style.justifyContent = 'center'; // Centra el contenido horizontalmente
                eventEl.style.alignItems = 'center'; // Centra el contenido verticalmente
                eventEl.style.backgroundColor = info.event.backgroundColor; // Fondo del evento
                eventEl.style.borderRadius = '5px'; // Bordes redondeados
                eventEl.style.height = '100%'; // Asegura que el evento ocupe toda la celda
            },
            dateClick: function(info) {
                mostrarReservasParaFecha(info.dateStr);
            },
            selectable: true // Asegura que todas las fechas sean seleccionables
        });

        calendar.render();
    });




    // Función para mostrar reservas en el modal 'modalReservasFecha'
    function mostrarReservasParaFecha(fecha) {
        const modalReservasFecha = document.getElementById('modalReservasFecha');
        const detalleReservasFecha = document.getElementById('detalleReservasFecha');
        modalReservasFecha.style.display = 'block';
        
        // Limpiamos el contenido anterior
        detalleReservasFecha.innerHTML = '';
    // Convertir la fecha a formato 'DD/MM/YYYY'
    function formatearFecha(fecha) {
        let [año, mes, día] = fecha.split('-');
        return `${día}/${mes}/${año}`;
    }
    //Fucion de formato
    let fechaformato =formatearFecha(fecha);


        if (reservasPorFecha[fecha] && reservasPorFecha[fecha].length > 0) {
            detalleReservasFecha.innerHTML = `<h3>Reservas para ${fechaformato}</h3>`;
            reservasPorFecha[fecha].forEach(function(reserva) {
                detalleReservasFecha.innerHTML += `
                    <div class="reserva-item">
                        <p><strong>Cliente:</strong> ${reserva.nombre} ${reserva.apellido}</p>
                        <p><strong>Teléfono:</strong> ${reserva.telefono}</p>
                        <p><strong>Hora:</strong> ${reserva.hora}</p>
                        <p><strong>Motivo:</strong> ${reserva.motivo}</p>
                        <p><strong>Cantidad de Personas:</strong> ${reserva.cantidad_personas}</p>
                        <p><strong>Estado:</strong> ${reserva.estado}</p>
                    </div>
                `;
            });
        } else {
            detalleReservasFecha.innerHTML = `<h3>No hay reservas para la fecha seleccionada (${fechaformato})</h3>`;
        }
    }

    // Función para cerrar el modal
    function cerrarModalReservasFecha() {
        const modalReservasFecha = document.getElementById('modalReservasFecha');
        modalReservasFecha.style.display = 'none';
    }
</script>
</body>
</html>

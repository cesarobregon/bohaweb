<?php
require_once 'Conexion.php';
require_once 'reserva.php';
require_once 'cliente.php'; // Modelo del cliente, asegúrate de tener este archivo
require_once 'Correo.php';  // Incluir la clase para enviar correos

class ReservaControlador {

    private $conexion;
    private $correo;
    private $modeloReserva;

    public function __construct($conexion) {
        $this->conexion = $conexion;
        $this->correo = new Correo();  // Instanciamos la clase Correo
        $this->modeloReserva = new Reserva($conexion);
    }
        
    // Método para obtener reservas pendientes
    public function obtenerReservasPendientes() {
        $db = new Conexion();
        $query = "SELECT * FROM reservas WHERE estado = 'pendiente'";
        $result = $db->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para archivar una reserva
    public function archivarReserva($id_reserva) {
        try {
            $sql = "UPDATE reservas SET archivada = 1 WHERE id_reserva = :id_reserva";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Método para confirmar una reserva
    public function confirmarReserva($id_reserva) {
        $reservaModel = new Reserva($this->conexion);
        $clienteModel = new Cliente($this->conexion);

        $reserva = $reservaModel->obtenerReservaPorId($id_reserva);
        $cliente = $clienteModel->obtenerClientePorId($reserva['id_cliente']);

        $reservaModel->actualizarEstadoReserva($id_reserva, 'confirmada');

        if ($this->correo->enviarCorreoConfirmacion($cliente, $reserva)) {
            echo 'Correo de confirmación enviado correctamente.';
        } else {
            echo 'Error al enviar el correo de confirmación.';
        }
    }

    // Método para cancelar una reserva
    public function cancelarReserva($id_reserva) {
        $reservaModel = new Reserva($this->conexion);
        $clienteModel = new Cliente($this->conexion);

        $reserva = $reservaModel->obtenerReservaPorId($id_reserva);
        $cliente = $clienteModel->obtenerClientePorId($reserva['id_cliente']);

        $reservaModel->actualizarEstadoReserva($id_reserva, 'cancelada');

        if ($this->correo->enviarCorreoCancelacion($cliente, $reserva)) {
            echo 'Correo de cancelación enviado correctamente.';
        } else {
            echo 'Error al enviar el correo de cancelación.';
        }
    }

    // Método para obtener todas las reservas organizadas por fecha para el calendario
    public function obtenerReservasParaCalendario() {
        $reservas = [];
        $sql = "SELECT fecha, nombre, apellido, telefono, hora, motivo, cantidad_personas, estado 
                FROM reservas INNER JOIN clientes ON reservas.id_cliente = clientes.id_cliente";
        $result = $this->conexion->query($sql);

        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $fecha = $row['fecha'];
                if (!isset($reservas[$fecha])) {
                    $reservas[$fecha] = [];
                }
                $reservas[$fecha][] = $row;
            }
        }

        return $reservas;
    }

    // Método para agregar una nueva reserva
    public function agregarReserva($nombre, $apellido, $email, $cantidad_personas, $fecha, $hora, $motivo) {
        try {
            $clienteModel = new Cliente($this->conexion);
    
            // Agregar o reutilizar cliente
            $cliente = $clienteModel->agregarCliente($nombre, $apellido, $email, '', '');
    
            if (!$cliente || !isset($cliente['id_cliente'])) {
                throw new Exception("Error al obtener el cliente después de crearlo.");
            }
    
            // Agregar la reserva asociada al cliente
            $reservaModel = new Reserva($this->conexion);
            $reservaCreada = $reservaModel->agregarReserva($cliente['id_cliente'], $cantidad_personas, $fecha, $hora, $motivo);
    
            if (!$reservaCreada) {
                throw new Exception("Error al agregar la reserva.");
            }
    
            header('Location: vista_reserva.php');
            exit;
    
        } catch (Exception $e) {
            // Manejo de errores
            error_log($e->getMessage());
            echo "Ocurrió un error: " . $e->getMessage();
        }
    }
    
}

// Lógica principal para manejar las solicitudes POST
$conexion = new Conexion();
$reservaControlador = new ReservaControlador($conexion);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_reserva = $_POST['id_reserva'];
    $accion = $_POST['accion'];

    if ($accion == 'confirmar') {
        $reservaControlador->confirmarReserva($id_reserva); // Confirmar reserva y enviar correo
    } elseif ($accion == 'cancelar') {
        $reservaControlador->cancelarReserva($id_reserva); // Cancelar reserva y enviar correo
    }

    // Redirigir después de procesar la acción
    header("Location: vista_reserva.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    
    $clienteModel = new Cliente($conexion);
    $reservaModel = new Reserva($conexion);
    $correoModel = new Correo();

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $cantidad_personas = $_POST['cantidad_personas'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $motivo = $_POST['motivo'] ?? 'Reserva';

    // Verificar si el cliente ya existe
    if ($clienteModel->existeClientePorEmail($email)) {
        $cliente = $clienteModel->obtenerClientePorEmail($email);
    } else {
        // Generar una contraseña aleatoria
        $cliente = $clienteModel->agregarCliente($nombre, $apellido, $email, '', '');  // Puedes manejar teléfono/dirección si es necesario
        
        // Enviar correo de bienvenida con la información de acceso
        $correoModel->enviarCorreodeBienvienida($cliente, [
            'fecha' => $fecha,
            'hora' => $hora,
            'motivo' => $motivo,
            'cantidad_personas' => $cantidad_personas
        ]);
    }

    // Agregar la reserva con el ID del cliente
    $reservaModel->agregarReserva($cliente['id_cliente'], $cantidad_personas, $fecha, $hora, $motivo);

    // Redirigir de vuelta a la página de reservas
    header('Location: vista_reserva.php');
    exit;
}

?>


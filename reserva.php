<?php
// Modelo de Reservas
class Reserva {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
    
    // Método para agregar una nueva reserva
    public function agregarReserva($id_cliente, $cantidad_personas, $fecha, $hora, $motivo) { 
        if (empty($id_cliente)) {
            throw new Exception("Error: El ID del cliente no puede estar vacío.");
        }
    
        try {
            $query = "INSERT INTO reservas (id_cliente, cantidad_personas, fecha, hora, motivo, estado, archivada) 
                      VALUES (:id_cliente, :cantidad_personas, :fecha, :hora, :motivo, 'pendiente', '0')";
            $stmt = $this->conexion->prepare($query);
            $stmt->bindParam(':id_cliente', $id_cliente);
            $stmt->bindParam(':cantidad_personas', $cantidad_personas);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':hora', $hora);
            $stmt->bindParam(':motivo', $motivo);
    
            return $stmt->execute(); // Devuelve true si se ejecutó correctamente
        } catch (PDOException $e) {
            error_log("Error al agregar la reserva: " . $e->getMessage());
            return false; // Devuelve false si hubo un error
        }
    }


    public function obtenerReservasPosteriores($fechaActual) {
        $query = "
            SELECT 
                r.id_reserva, r.fecha, r.motivo, r.cantidad_personas, r.hora, r.estado, 
                c.nombre, c.apellido, c.telefono
            FROM 
                reservas r
            JOIN 
                clientes c 
            ON 
                r.id_cliente = c.id_cliente
            WHERE 
                r.fecha >= :fechaActual AND r.archivada = 0
            GROUP BY
                r.id_reserva DESC";

        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(':fechaActual', $fechaActual);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener todas las reservas
    public function obtenerReservas() {
        $query = $this->conexion->prepare("SELECT * FROM reservas");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    // Obtener reserva por ID
    public function obtenerReservaPorId($id_reserva) {
        $query = $this->conexion->prepare("SELECT * FROM reservas WHERE id_reserva = ?");
        $query->execute([$id_reserva]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    

    // Actualizar estado de la reserva
    public function actualizarEstadoReserva($id_reserva, $estado) {
        $query = $this->conexion->prepare("UPDATE reservas SET estado = ? WHERE id_reserva = ?");
        $query->execute([$estado, $id_reserva]);
    }
    public function obtenerReservasHistoricas($estado = 'todas', $fechaInicio = null, $fechaFin = null) {
        $query = "SELECT reservas.*, clientes.nombre, clientes.apellido, clientes.telefono 
                  FROM reservas
                  JOIN clientes ON reservas.id_cliente = clientes.id_cliente
                  WHERE 1=1"; // Iniciar con una condición siempre verdadera para añadir filtros
    
        // Filtros condicionales
        if ($estado !== 'todas') {
            $query .= " AND reservas.estado = :estado";
        }
        if ($fechaInicio && $fechaFin) {
            $query .= " AND reservas.fecha BETWEEN :fechaInicio AND :fechaFin";
        }
        
        $stmt = $this->conexion->prepare($query);
    
        // Parámetros de filtro
        if ($estado !== 'todas') {
            $stmt->bindParam(':estado', $estado);
        }
        if ($fechaInicio && $fechaFin) {
            $stmt->bindParam(':fechaInicio', $fechaInicio);
            $stmt->bindParam(':fechaFin', $fechaFin);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // En el archivo
    public function archivarReserva($id_reserva) {
        try {
            $query = "UPDATE reservas SET archivada = 1 WHERE id_reserva = :id_reserva";
            $stmt = $this->conexion->prepare($query);
            $stmt->bindParam(':id_reserva', $id_reserva);
            return $stmt->execute(); // Devuelve true si se ejecutó correctamente
        } catch (PDOException $e) {
            error_log("Error al archivar la reserva: " . $e->getMessage());
            return false;
        }
}
}



?>

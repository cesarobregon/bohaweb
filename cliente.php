<?php

class Cliente {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Obtener todos los clientes
    public function obtenerTodosLosClientes() {
        $query = $this->conexion->prepare("SELECT * FROM clientes");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener cliente por ID
    public function obtenerClientePorId($id_cliente) {
        $query = $this->conexion->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
        $query->execute([$id_cliente]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener cliente por email
    public function obtenerClientePorEmail($email) {
        $query = $this->conexion->prepare("SELECT * FROM clientes WHERE email = ?");
        $query->execute([$email]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Verificar si el cliente existe por email
    public function existeClientePorEmail($email) {
        $query = $this->conexion->prepare("SELECT COUNT(*) FROM clientes WHERE email = ?");
        $query->execute([$email]);
        return $query->fetchColumn() > 0;
    }

    public function agregarCliente($nombre, $apellido, $email, $telefono, $direccion) {
        if ($this->existeClientePorEmail($email)) {
            // Si el cliente ya existe, devolverlo
            return $this->obtenerClientePorEmail($email);
        }
    
        // Generar una contraseña aleatoria para el cliente
        $clave = $this->generarClaveAleatoria();
    
        try {
            // Insertar un nuevo cliente en la base de datos
            $query = $this->conexion->prepare("
                INSERT INTO clientes (nombre, apellido, email, clave, telefono, direccion) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $query->execute([$nombre, $apellido, $email, $clave, $telefono, $direccion]);
    
            // Obtener el cliente recién creado (incluyendo el id_cliente)
            return $this->obtenerClientePorEmail($email);
        } catch (PDOException $e) {
            error_log("Error al agregar cliente: " . $e->getMessage());
            return false; // Retornar false si ocurre un error
        }
    }
    

    // Actualizar los datos de un cliente
    public function actualizarCliente($id_cliente, $nombre, $apellido, $email, $telefono, $direccion) {
        $query = $this->conexion->prepare("
            UPDATE clientes 
            SET nombre = ?, apellido = ?, email = ?, telefono = ?, direccion = ? 
            WHERE id_clientes = ?
        ");
        $query->execute([$nombre, $apellido, $email, $telefono, $direccion, $id_cliente]);
    }

    // Eliminar un cliente por ID
    public function eliminarCliente($id_cliente) {
        $query = $this->conexion->prepare("DELETE FROM clientes WHERE id_clientes = ?");
        $query->execute([$id_cliente]);
    }

    // Función para generar una contraseña aleatoria
    private function generarClaveAleatoria($longitud = 6) {
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $clave = '';
        for ($i = 0; $i < $longitud; $i++) {
            $clave .= $caracteres[random_int(0, strlen($caracteres) - 1)];
        }
        return $clave;
    }
}

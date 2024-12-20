<?php
session_start();
require_once 'conexion.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica si los campos email y password están definidos
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];  // Contraseña en texto plano

        try {
            //instancia de la clase Conexion
            $conexion = new Conexion();

            // consulta SQL
            $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Comprobar si se encontró un usuario
            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verifica la contraseña en texto plano
                if ($password === $usuario['contrasena']) {
                    // Contraseña correcta, redirigir a index.php
                    header("Location: index.php");
                    exit();
                } else {
                    // mensaje de error en la sesión
                    $_SESSION['error'] = "Contraseña incorrecta, intente nuevamente. Recuerde utilizar la contraseña especificadas en el manual de usuario";
                    header("Location: login.php"); // Redirige a login.php con el error
                    exit();
                }
            } else {
                $_SESSION['error'] = "Correo electronico incorrecto. Recuerde utilizar el correo especificado en el manual de usuario";
                header("Location: login.php"); // Redirige a login.php con el error
                exit();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Por favor, complete todos los campos.";
    }
} else {
    echo "Método de solicitud no válido.";
}

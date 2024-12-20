<?php
require 'vendor/autoload.php';  // Asegúrate de que esta ruta sea correcta
require_once 'cliente.php';
require_once 'reserva.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Correo {

    public function enviarCorreodeBienvienida($cliente, $reserva) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'restaurantboha@gmail.com';
            $mail->Password = 'igwr kosu rywe gmon';  // Asegúrate de usar contraseñas seguras o aplicaciones específicas
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
    
            $mail->setFrom('restaurantboha@gmail.com', 'Boha Restaurante');
            $mail->addAddress($cliente['email'], $cliente['nombre'] . ' ' . $cliente['apellido']);
    
            $mail->isHTML(true);
            $mail->Subject = 'Bienvenido a Boha Restaurante';
            $mail->Body = "
                <h1>¡Bienvenido/a {$cliente['nombre']}!</h1>
                <p>Nos complace darte la bienvenida a Boha Restaurante. Aquí están tus credenciales:</p>
                <p><strong>Email:</strong> {$cliente['email']}</p>
                <p><strong>Contraseña:</strong> {$cliente['clave']}</p>
                <p><strong>Información de tu reserva:</strong></p>
                <ul>
                    <li><strong>Fecha:</strong> {$reserva['fecha']}</li>
                    <li><strong>Hora:</strong> {$reserva['hora']}</li>
                    <li><strong>Motivo:</strong> {$reserva['motivo']}</li>
                    <li><strong>Cantidad de personas:</strong> {$reserva['cantidad_personas']}</li>
                </ul>
                <p>Gracias por elegirnos. ¡Te esperamos!</p>
            ";
    
            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar el correo: {$mail->ErrorInfo}");
        }
    }
    public function enviarCorreoConfirmacion($cliente, $reserva) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'restaurantboha@gmail.com';
            $mail->Password = 'igwr kosu rywe gmon';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Cambiar si es necesario
            $mail->Port = 587;

            // Opcional para depuración
            $mail->SMTPDebug = 2;
            $mail->Debugoutput = 'html';

            $mail->setFrom('restaurantboha@gmail.com', 'Boha Restaurante');
            $mail->addAddress($cliente['email'], $cliente['nombre'] . ' ' . $cliente['apellido']);
            $mail->Subject = 'Confirmacion de Reserva';
            $mail->Body = "Estimado/a {$cliente['nombre']},\n\nTu reserva para el {$reserva['fecha']} ha sido confirmada.\n\nGracias por elegirnos!!";

            $mail->send();
            echo 'Correo de confirmación enviado con éxito.';
        } catch (Exception $e) {
            error_log("Error al enviar correo: {$mail->ErrorInfo}. Exception: " . $e->getMessage());
            echo "El correo no pudo ser enviado. Error: {$mail->ErrorInfo}";
        }
    }

    public function enviarCorreoCancelacion($cliente, $reserva) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'restaurantboha@gmail.com';
            $mail->Password = 'igwr kosu rywe gmon';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('restaurantboha@gmail.com', 'Boha Restaurante');
            $mail->addAddress($cliente['email'], $cliente['nombre'] . ' ' . $cliente['apellido']);
            $mail->Subject = 'Cancelacion de Reserva';
            $mail->Body = "Estimado/a {$cliente['nombre']},\n\nLamentamos informarte que tu reserva para el {$reserva['fecha']} ha sido cancelada.\n\nDisculpe la invonveniencia.";

            $mail->send();
            echo 'Correo de cancelación enviado con éxito.';
        } catch (Exception $e) {
            error_log("Error al enviar correo: {$mail->ErrorInfo}. Exception: " . $e->getMessage());
            echo "El correo no pudo ser enviado. Error: {$mail->ErrorInfo}";
        }
    }
}
?>

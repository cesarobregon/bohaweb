<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/login.css">
    
    <style>
        /* Estilos para el modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7); /* Gris oscuro semitransparente */
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #333; /* Gris oscuro */
            color: #fff; /* Texto blanco */
            padding: 20px;
            border-radius: 10px;
            width: 300px; /* Tamaño pequeño */
            text-align: center;
        }

        .modal-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #FFD700; /* Amarillo */
            color: #333; /* Texto oscuro */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .modal-button:hover {
            background-color: #FFC107; /* Amarillo más oscuro al pasar el ratón */
        }

        /* Estilo para el enlace "Olvidé la contraseña" */
        .forgot-password {
            color: #FFD700;
            text-decoration: none;
            font-size: 14px;
            margin-top: 10px;
            display: block;
            text-align: center;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">

        <img src="Images/logog (2).jpg" alt="Logo">

        
        <h2>Iniciar Sesión</h2>

        <!-- Formulario de login -->
        <form action="procesar_login.php" method="post">
            <label for="email" style="color: #fff;">Correo Electrónico</label>
            <input type="email" id="email" name="email" placeholder="Correo Electrónico" required>

            <label for="password" style="color: #fff;">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Contraseña" required>

            <input type="submit" value="Iniciar Sesión">
        </form>

        <!-- enlace para olvide la contra -->
        <a href="#" class="forgot-password" onclick="openForgotPasswordModal()">Olvidé la contraseña</a>

        <!-- Modal para mostrar el error de inicio secionkje -->
        <div id="errorModal" class="modal">
            <div class="modal-content">
                <p id="errorMessage"></p>
                <button class="modal-button" onclick="closeModal()">Ok</button>
            </div>
        </div>

        <!-- Modal para "Olvidé la contraseña" -->
        <div id="forgotPasswordModal" class="modal">
            <div class="modal-content">
                <p>Recuerde utilizar el correo y contraseña especificados en el manual de usuario.</p>
                <p>Si el problema persiste, contacte a servicio técnico.</p>
                <button class="modal-button" onclick="closeForgotPasswordModal()">Ok</button>
            </div>
        </div>
    </div>

    <script>
        // Muestra el modal si hay error en i.s
        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo 'document.getElementById("errorMessage").innerText = "' . $_SESSION['error'] . '";';
            echo 'document.getElementById("errorModal").style.display = "flex";';
            unset($_SESSION['error']); // Limpiar el mensaje de error después de mostrarlo
        }
        ?>

        //F cerrar el modal de error
        function closeModal() {
            document.getElementById("errorModal").style.display = "none";
        }

        // F    abrir el modal de "Olvidé la contraseña"
        function openForgotPasswordModal() {
            document.getElementById("forgotPasswordModal").style.display = "flex";
        }

        // F cerrar el modal de "Olvidé la contraseña"
        function closeForgotPasswordModal() {
            document.getElementById("forgotPasswordModal").style.display = "none";
        }
    </script>
</body>
</html>

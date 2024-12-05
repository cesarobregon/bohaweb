<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=nombre_de_la_base", "usuario", "contraseña");
    echo "Conexión exitosa!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

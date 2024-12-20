<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir el archivo de conexión
require_once 'Conexion.php';
// Obtener el valor de la categoría seleccionada si está presente
$categoriaSeleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : null;
try {
    // Crear una instancia de la conexión
    $conexion = new Conexion();
    // Consulta SQL para obtener las categorías para el menú desplegable
    $categoriaSql = "SELECT id_categoria, nombre FROM categorias";
    $stmtCategorias = $conexion->prepare($categoriaSql);
    $stmtCategorias->execute();
    $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
    // Consulta SQL para obtener los productos con o sin filtro de categoría
    $sql = "
        SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.id_categoria, p.disponibilidad, p.foto, c.nombre AS categoria_nombre
        FROM productos p
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
    ";
    if ($categoriaSeleccionada) {
        $sql .= " WHERE p.id_categoria = :categoriaSeleccionada";
    }
    $sql .= " ORDER BY p.id_producto DESC";
    $stmt = $conexion->prepare($sql);
    if ($categoriaSeleccionada) {
        $stmt->bindParam(':categoriaSeleccionada', $categoriaSeleccionada, PDO::PARAM_INT);
    }
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Menú</title>
    <link rel="icon" type="image/png" href="Images/logog.jpg" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/modal.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    

    <style>
body {
    font-family: Arial, Helvetica, sans-serif;
    color: #423d02;
    margin: 0;
    padding: 0;
}
.contenedor {
    max-width: 1300px;
    margin: 20px auto;
    padding: 20px;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
}
input[type="submit"], button {
    background-color: #d4af37;
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    border-radius: 3px;
    font-size: 16px;
}
input[type="submit"]:hover, button:hover {
    background-color: #b38e30;
}

/* Estilos para el modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 140%;
    height: 120%;
    background-color: rgba(0, 0, 0, 0.8); /* Fondo oscuro y semi-transparente */
}
.modal-content {
    background-color: rgba(50, 50, 50, 0.9); /* Fondo gris oscuro con algo de transparencia */
    margin: auto;
    padding: 30px;
    border: 1px solid #888;
    width: 100%;
    max-width: 500px;
    border-radius: 5px;
}
.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
}
.close:hover {
    color: #b38e30;
}
/* Estilo para el contenedor detrás de la tabla */
.contenedor {
    background-color: black; /* Fondo negro/gris oscuro para el contenedor */
    padding: 20px;
}
/* Estilos para la tabla */
.tabla-inicio {
    width: 100%;
    border-collapse: collapse;
    background-color: #e0e0e0; /* Fondo gris intermedio para la tabla */
    color: black; /* Texto negro para una mejor legibilidad */
}
/* Estilos para las celdas de la tabla */
.tabla-inicio th, .tabla-inicio td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}
/* Fondo gris oscuro para el encabezado de la tabla */
.tabla-inicio th {
    background-color: #2c2c2c; /* Gris oscuro para el encabezado */
    color: white; /* Texto blanco en el encabezado */
}
/* Fondo gris más oscuro para las filas pares */
.tabla-inicio tr:nth-child(even) {
    background-color: #b0b0b0; /* Gris más oscuro para las filas pares */
}
/* Fondo gris medio al pasar el mouse sobre las filas */
.tabla-inicio tr:hover {
    background-color: #999999; /* Gris más oscuro para el hover */
}
 /* Estilo del modal */
.modal {
        display: none; /* Ocultar el modal por defecto */
        position: fixed; /* Fijar la posición del modal */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7); /* Fondo oscuro y transparente */
        z-index: 1; /* Asegurarse de que el modal esté por encima de otros elementos */
        justify-content: center;
        align-items: center;
    }
    /* Estilo del contenido del modal */
    .modal-content-eliminar {
        background-color: #333; /* Fondo gris oscuro para el contenido del modal */
        color: white; /* Color del texto blanco */
        margin: 25% auto; /* Centrado en el medio de la pantalla */
        padding: 20px;
        border-radius: 8px;
        width: 40;/* Ancho del modal, puedes ajustarlo según prefieras */
        text-align: center; /* Centrar el contenido */
    }
    /* Estilo del botón de cerrar */
    .close {
        color: #aaa; /* Color gris para el botón de cerrar */
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close:hover,
    .close:focus {
        color: black; /* Cambia el color del botón de cerrar al pasar el mouse */
        text-decoration: none;
        cursor: pointer;
    }
/* Estilo para el contenedor del botón (centrado en la parte superior de la tabla) */
.boton-container {
    display: flex;
    justify-content: center; /* Centra los botones horizontalmente */
    gap: 20px; /* Espacio entre los botones */
    margin-bottom: 20px; /* Espacio entre los botones y la tabla */
}
/* Estilo para los botones */
.boton-container .btnn {
    width: 150px;
    height: 40px;
    background-color: green; /* Verde más oscuro */
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
    border-radius: 5px;
    font-weight: bold; /* Pone la letra en negrita */
    display: flex; /* Activamos Flexbox */
    justify-content: center; /* Centra horizontalmente */
    align-items: center; /* Centra verticalmente */
    text-align: center; /* Asegura que el texto esté centrado */
}

/* Estilo para los botones */
.boton-container .btn {
    width: 150px;
    height: 40px;
    background-color:  #d69e2e; /* Amarillo más oscuro */
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
    border-radius: 5px;
    font-weight: bold; /* Pone la letra en negrita */
}

/* Estilos para el modal */
#modal-imagen {
    position: fixed;
    top: 50px; /* Mueve el modal hacia abajo */
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    display: flex;
    justify-content: center;  /* Centrado horizontal */
    align-items: center;  /* Centrado vertical */
    z-index: 1000;            /* Asegura que el modal esté sobre otros elementos */
}

/* Estilo de la imagen dentro del modal */
#img-modal {
    max-width: 80%;  /* Limita el ancho máximo de la imagen al 80% del ancho del modal */
    max-height: 80%; /* Limita la altura máxima de la imagen al 80% del alto del modal */
    object-fit: contain; /* Mantiene la relación de aspecto de la imagen */
    margin-top: 20px; /* Desplaza la imagen hacia abajo si lo deseas */
}

/* Estilo del botón de cerrar */
.close {
    position: absolute;
    top: 10px;
    right: 10px;
    color: white;
    font-size: 30px;
    font-weight: bold;
    cursor: pointer;
    z-index: 1001; /* Asegura que el botón de cerrar esté sobre la imagen */
}

</style>

<script>
        // Función para abrir el modal del filtro 
        function abrirModalFiltro() {
            document.getElementById("modalFiltro").style.display = "flex";
        }
        // Función para cerrar el modal je
        function cerrarModalFiltro() {
            document.getElementById("modalFiltro").style.display = "none";
        }
    </script> 

</head>
<body>
    <header id="banner-container">
        <img src="Images/bannner.png" alt="Banner de Boha Restaurante" id="banner">
        <h1>PRODUCTOS   
        </h1>
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

    <div class="boton-container">
    <button style="margin-right: 10px; margin-left: 10px; width: 150px; height: 47px;  color: white;" class="btnn" onclick="abrirModalFiltro()">Filtrar por Categoría</button>
    <button style="margin-right: 10px; width: 150px; height: 47px; color: white;" class="btn" onclick="abrirModalProducto()">Nuevo Producto</button>
</div>
<!-- Modal de Filtrado -->
<div id="modalFiltro" class="modal-filtro">
    <div class="modal-filtro-content">
        <h3>Seleccione la categoría por la que desea filtrar</h3>
        <form method="GET" action="">
            <select name="categoria" style="width: 100%; padding: 10px; margin-top: 10px; margin-bottom: 20px;">
                <option value="">Todas las Categorías</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id_categoria'] ?>" <?= ($categoriaSeleccionada == $categoria['id_categoria']) ? 'selected' : '' ?>>
                        <?= $categoria['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="apply-btn">Aplicar Filtro</button>
            <button type="button" class="close-btn" onclick="cerrarModalFiltro()">Cerrar</button>
        </form>
    </div>
</div>
<style>
/* Estilos del Modal de Filtrado */
.modal-filtro {
display: none; /* Oculto por defecto */
position: fixed;
z-index: 1000;
left: 50%;
top: 50%;
transform: translate(-50%, -50%);
}
.modal-filtro-content {
width: 300px; /* Más angosto */
padding: 20px;
background-color: rgba(50, 50, 50, 0.9); /* Gris oscuro con 80% de opacidad */
color: white; /* Letras en blanco */
border-radius: 10px;
text-align: center;
}
.apply-btn, .close-btn {
background-color: green;
color: white;
border: none;
padding: 10px 20px;
margin: 10px 5px;
cursor: pointer;
border-radius: 5px;
}
.apply-btn:hover, .close-btn:hover {
background-color: darkgreen;
}
</style>

<div class="contenedor">
    <table class="tabla-inicio">
        <thead>
            <tr>
                <th>Acción</th>
                <th>Id</th>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Categoria</th>
                <th>Disponibilidad</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($productos) > 0) {
                foreach ($productos as $fila) {
                    echo "<tr>";
                    echo "<td>";
                    echo '<button style="margin-right: 10px; background-color: green; color: white; width: 100px; height: 40px;" onclick="abrirModalEditarProducto('
                        . $fila["id_producto"] . ', '
                        . "'" . $fila["nombre"] . "', "
                        . "'" . $fila["descripcion"] . "', "
                        . $fila["precio"] . ', '
                        . $fila["id_categoria"] . ', '
                        . $fila["disponibilidad"] 
                        . ')">Editar</button>';
                    echo '<button style="margin-right: 10px; background-color: red; color: white; width: 100px; height: 40px;" onclick="abrirModalEliminarProducto(' . $fila["id_producto"] . ')">Eliminar</button>';
                    echo "</td>";
                    echo "<td>" . $fila["id_producto"] . "</td>";
                    echo "<td>" . $fila["nombre"] . "</td>";
                    echo "<td>" . $fila["descripcion"] . "</td>";
                    echo "<td>" . $fila["precio"] . "</td>";
                    echo "<td>" . $fila["categoria_nombre"] . "</td>";
                    echo "<td>" . ($fila["disponibilidad"] ? "Disponible" : "No Disponible") . "</td>";
                    echo '<td><img src="' . $fila["foto"] . '" alt="Imagen de ' . $fila["nombre"] . '" style="width:100px; height:auto; cursor:pointer;" class="clickable-img"></td>';

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No hay productos disponibles</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
    <!-- Modal para agregar un nuevo producto -->
<div id="modalProducto" class="modal">
    <div class="modal-content" style="color: white;" >
        <span class="close" onclick="cerrarModalProducto()">&times;</span>
        <h2 style="color: #d4af37;">Agregar Producto</h2>
        <form action="insertar.php" method="post" enctype="multipart/form-data">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required maxlength="50" style="width: 70%;">
            
            <label for="descripcion">Descripción:</label>
            <input type="text" id="descripcion" name="descripcion" required maxlength="200"style="width: 70%;">
            
            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" required min="0" max="9999999999" style="width: 70%;">
            
            <label for="categoria">Categoría:</label>
            <select id="categoria" name="categoria" required style="width: 70%; height: 35px; margin-top: 0px;">
            <option value="">Todas las Categorías</option>
            <?php
            // Incluir la conexión a la base de datos
            require_once 'conexion.php';
            try {
            // Crear una instancia de la clase Conexion
                $conexion = new Conexion();
                // Consulta SQL para obtener las categorías
                $sql = "SELECT id_categoria, nombre FROM categorias"; // Asegúrate de que la tabla y el campo de la categoría sean correctos
                $stmt = $conexion->prepare($sql);
                $stmt->execute();
                // Iterar sobre los resultados y agregar opciones al menú desplegable
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    
                    echo "<option value='{$row['id_categoria']}'>{$row['nombre']}</option>";
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </select>
            <label for="foto">Imagen de comida:</label>
            <input type="file" id="foto" name="archivo" accept="image/*" required style="width: 70%; height: 35px; margin-top: 1px;">
            <!-- Botón de agregar comida -->
            <div style="margin-top: 15px;"> <!-- Añadido margen para espaciar -->
                <input type="submit" value="Agregar producto" class="boton-agregar" style="background-color: #d4af37; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px; font-size: 16px;">
            </div>
        </form>
    </div>
</div>

<!-- Modal para editar producto existente -->
<div id="modalEditarProducto" class="modal">
    <div class="modal-content" style="color: white;">
        <span class="close" onclick="cerrarModalEditarProducto()">&times;</span>
        
        <h2 style="color: #d4af37;">Editar Producto</h2>
        
        <form id="formEditarProducto" action="administrar_producto.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="productoId" name="id" value="<?php echo isset($producto) ? $producto['id_producto'] : ''; ?>">

            <label for="productoNombre">Nombre:</label>
            <input type="text" id="productoNombre" name="nombre" value="<?php echo isset($producto) ? $producto['nombre'] : ''; ?>" required style="width: 70%;">
            
            <label for="productoDescripcion">Descripción:</label>
            <input type="text" id="productoDescripcion" name="descripcion" value="<?php echo isset($producto) ? $producto['descripcion'] : ''; ?>" required style="width: 70%;">
            
            <label for="productoPrecio">Precio:</label>
            <input type="number" id="productoPrecio" name="precio" value="<?php echo isset($producto) ? $producto['precio'] : ''; ?>" required style="width: 70%;">
            
            <label for="productoCategoria" style="font-size: 16px; margin-bottom: 8px;">Categoría:</label>
            <select id="productoCategoria" name="categoria" required style="height: 40px; width: 70%; padding: 10px; margin-bottom: 10px; font-size: 13px;">
                <?php
                // Mostrar las categorías en el menú desplegable
                include 'administrar_producto.php';
                foreach ($categorias as $categoria) {
                    // Si la categoría del producto es la misma que la del loop, marcarla como seleccionada
                    $selected = (isset($producto) && $producto['id_categoria'] == $categoria['id_categoria']) ? 'selected' : '';
                    echo "<option value='" . $categoria['id_categoria'] . "' $selected>" . $categoria['nombre'] . "</option>";
                }
                ?>
            </select>
            
            <label for="productoDisponibilidad">Disponibilidad:</label>
            <select required style="height: 40px; width: 70%;" id="productoDisponibilidad" name="disponibilidad" required style="width: 70%;">
                <option value="1" <?php echo (isset($producto) && $producto['disponibilidad'] == 1) ? 'selected' : ''; ?>>Disponible</option>
                <option value="0" <?php echo (isset($producto) && $producto['disponibilidad'] == 0) ? 'selected' : ''; ?>>No Disponible</option>
            </select>
            
            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <input type="submit" value="Guardar Cambios" style="background-color: #d4af37; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px; font-size: 16px; width: 190px;">
                <button type="button" onclick="cerrarModalEditarProducto()" style="background-color: #d4af37; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px; font-size: 16px; width: 190px;">Cancelar</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal de Confirmación de Eliminación -->
<div id="modalEliminar" class="modal">
<div class="modal-content">

    <span class="close" onclick="cerrarModalEliminar()">&times;</span>
    <h2 >¿Estás seguro que deseas eliminar este producto?</h2>
    <input type="hidden" id="idProductoEliminar">
    <button onclick="confirmarEliminar()">Eliminar</button>
    <button onclick="cerrarModalEliminar()">Cancelar</button>
</div>
    </div>

    <style>
/* Estilos del modal de confirmación de eliminación */
#modalEliminar {
    display: none; /* Inicialmente oculto */
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7); /* Fondo oscuro con 70% de opacidad */
    align-items: center;
    justify-content: center;
}

/* Estilos del contenido del modal de eliminación */
#modalEliminar .modal-content {
    background-color: rgba(50, 50, 50, 0.7); /* Gris oscuro con 70% de opacidad */
    color: white; /* Texto blanco para buen contraste */
    padding: 20px; /* Espaciado interno */
    border-radius: 8px; /* Bordes redondeados */
    width: 270px; /* Ancho más pequeño */
    max-width: 90%; /* Limitar al 90% del ancho de la pantalla en dispositivos pequeños */
    text-align: center;
    font-size: 16px; /* Tamaño de fuente para mejor visibilidad */
    box-sizing: border-box; /* Incluye el padding dentro del tamaño total */
    height: auto; /* Deja que la altura se ajuste al contenido */
    min-height: 150px; /* Altura mínima para que no quede demasiado comprimido */
}

/* Estilo de la "X" para cerrar el modal */
#modalEliminar .close {
    color: #aaa;
    float: right;
    font-size: 24px;
    cursor: pointer;
}

/* Botón de eliminar */
#modalEliminar .modal-content button:nth-child(1) {
    padding: 8px 16px;
    background-color: #D32F2F; /* Rojo oscuro */
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 25px; /* Separación mayor entre el texto y el botón */
    width: 100%; /* Que ocupe todo el ancho disponible */
}

#modalEliminar .modal-content button:nth-child(1):hover {
    background-color: #C62828; /* Rojo más oscuro al pasar el mouse */
}

/* Botón de cancelar */
#modalEliminar .modal-content button:nth-child(2) {
    padding: 8px 16px;
    background-color: #9E9E9E; /* Gris para el botón de cancelar */
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 25px; /* Mayor separación entre los botones */
    width: 100%; /* Que ocupe todo el ancho disponible */
}

#modalEliminar .modal-content button:nth-child(2):hover {
    background-color: #757575; /* Gris más oscuro al pasar el mouse */
}

    </style>
<!-- Modal de imagen ampliada -->
<div id="modal-imagen" style="display: none;">
    <span class="close" onclick="document.getElementById('modal-imagen').style.display='none'">&times;</span>
    <img id="img-modal" src="" alt="Imagen ampliada">
</div>




<script>
    document.querySelectorAll(".clickable-img").forEach(function(img) {
    img.addEventListener("click", function() {
        var imgSrc = this.src;  // Obtiene la ruta de la imagen
        var modal = document.getElementById("modal-imagen");
        var modalImg = document.getElementById("img-modal");

        modal.style.display = "block";
        modalImg.src = imgSrc;  // Establece la imagen dentro del modal
    });
});

// Cerrar modal cuando se haga clic en el modal
document.getElementById("modal-imagen").addEventListener("click", function() {
    this.style.display = "none";
});


function agrandarImagen(src) {
    var modalImagen = document.getElementById("modalImagen");
    var imagenAmpliada = document.getElementById("imagenAmpliada");
    modalImagen.style.display = "block";
    imagenAmpliada.src = src;
}
function cerrarModalImagen() {
    var modalImagen = document.getElementById("modalImagen");
    modalImagen.style.display = "none";
}
// Cerrar el modal si el usuario hace clic fuera de la imagen
window.onclick = function(event) {
    var modalImagen = document.getElementById("modalImagen");
    if (event.target == modalImagen) {
        modalImagen.style.display = "none";
    }
};
        // Función para abrir el modal de nuevo producto
        function abrirModalProducto() {
            document.getElementById("modalProducto").style.display = "block";
        }

        // Función para cerrar el modal de nuevo producto
        function cerrarModalProducto() {
            document.getElementById("modalProducto").style.display = "none";
        }

        // Función para abrir el modal de editar producto con datos precargados
        function abrirModalEditarProducto(id, nombre, descripcion, precio, categoria, disponibilidad) {
            document.getElementById('productoId').value = id;
            document.getElementById('productoNombre').value = nombre;
            document.getElementById('productoDescripcion').value = descripcion;
            document.getElementById('productoPrecio').value = precio;
            document.getElementById('productoCategoria').value = categoria;
            document.getElementById('productoDisponibilidad').value = disponibilidad;
            document.getElementById("modalEditarProducto").style.display = "block";
        }

        // Función para cerrar el modal de editar producto
        function cerrarModalEditarProducto() {
            document.getElementById("modalEditarProducto").style.display = "none";
        }
        
    // Función para cerrar el modal de editar producto
    function cerrarModalEditar() {
        document.getElementById('modalEditar').style.display = "none";
    }

        // Función para abrir el modal de la imagen ampliada
        function agrandarImagen(src) {
            var modalImagen = document.getElementById("modalImagen");
            var imagenAmpliada = document.getElementById("imagenAmpliada");
            modalImagen.style.display = "block";
            imagenAmpliada.src = src;
        }

        // Función para cerrar el modal de la imagen ampliada
        function cerrarModalImagen() {
            document.getElementById("modalImagen").style.display = "none";
        }

        // Cerrar el modal si el usuario hace clic fuera del contenido del modal
        window.onclick = function(event) {
            
            var modalProducto = document.getElementById("modalProducto");
            var modalEditarProducto = document.getElementById("modalEditarProducto");
            var modalImagen = document.getElementById("modalImagen");
            
            if (event.target == modalProducto) {
                modalProducto.style.display = "none";
            } else if (event.target == modalEditarProducto) {
                modalEditarProducto.style.display = "none";
            } else if (event.target == modalImagen) {
                modalImagen.style.display = "none";
            } 
        }
    </script>
<script>
        // Función para abrir el modal de eliminar producto
        function abrirModalEliminarProducto(id) {

            document.getElementById('modalEditarProducto').style.display = "none"; // Cerrar el de Editar
            document.getElementById('idProductoEliminar').value = id;
            document.getElementById('modalEliminar').style.display = "block"; // Mostrar el de Eliminar
}

        // Función para cerrar el modal de eliminar producto
        function cerrarModalEliminar() {
            document.getElementById('modalEliminar').style.display = "none";
        }
        // Función para confirmar la eliminación
        function confirmarEliminar() {
            var id = document.getElementById('idProductoEliminar').value;
            // Redirigir a la página de eliminación con la ID del producto
            window.location.href = 'eliminar_producto.php?id=' + id;
        }

        // Cerrar el modal si el usuario hace clic fuera del contenido del modal
        window.onclick = function(event) {
            var modalEliminar = document.getElementById('modalEliminar');
            if (event.target == modalEliminar) {
                modalEliminar.style.display = "none";
            }
        }
</script>
</body>
</html>

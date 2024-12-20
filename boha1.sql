-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-11-2024 a las 07:15:10
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `boha1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(20) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `tipo` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `tipo`) VALUES
(1, 'Pizzas', 'comida'),
(2, 'Hamburgesas', 'comida'),
(3, 'Milanesas', 'comida'),
(4, 'Sandwiches', 'comida'),
(5, 'Empanadas', 'comida'),
(6, 'Picadas', 'comida'),
(7, 'Bebidas sin alcohol', 'bebida'),
(8, 'Bebidas con alcohol', 'bebida'),
(9, 'Postres', 'comida'),
(10, 'Ofertas especiales', 'comida');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `clave` varchar(30) NOT NULL,
  `direccion` varchar(30) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `apellido`, `email`, `clave`, `direccion`, `telefono`) VALUES
(1, 'Ailin', 'Monzon', 'ailiinnicole99@gmail.com', '41945505', 'mz 47 pc 19 B° fonavi', '34265346'),
(2, 'Orlando ', 'Lopez', 'orlandiño@gmail.com', 'orli1234', 'mi casa', '546546'),
(3, 'Lolo', 'lopez', 'lololopez@gmail.com', 'mEcCsBI1', '', '0'),
(4, 'cesar', 'obregon', 'cesarobregon66@gmail.com', '7zsby8sK', '', '0'),
(5, 'jaquelin', 'Dimatos', 'delijaque@gmail.com', 'wexRjyUv', '', '0'),
(6, 'Ronaldiño', 'perez', 'rojaslucas17@gmail.com', 'y3zV7fNG', '', '0'),
(7, 'Raul ', 'Hernandez', 'raulhernandez@gmail.com', 'Mh8ZQgqT', '', '0'),
(8, 'César', 'Obregón ', 'cesarobregon@gmail.com', 'Cesarob10', 'Sáenz Peña ', '3644524941'),
(9, 'Axel Exequiel', 'Alfonso Barrios', 'axelexequiel17@gmail.com', '171709', 'Miguiel de Garate 1361', '34265346');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle`
--

CREATE TABLE `detalle` (
  `id_detalle` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` int(11) NOT NULL,
  `uuid_pedido` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `detalle`
--

INSERT INTO `detalle` (`id_detalle`, `id_producto`, `cantidad`, `precio`, `uuid_pedido`) VALUES
(4, 20, 1, 21434, '3d96bfea-ea99-4246-9ae2-e4b92b990c44'),
(5, 21, 1, 2500, '3d96bfea-ea99-4246-9ae2-e4b92b990c44'),
(6, 18, 1, 4500, 'd739a7a0-d39d-437e-be9b-fbc77c9ed263'),
(7, 27, 1, 6000, 'd739a7a0-d39d-437e-be9b-fbc77c9ed263'),
(8, 21, 1, 2500, 'd739a7a0-d39d-437e-be9b-fbc77c9ed263'),
(9, 30, 1, 353253, '2a9681e5-5f90-4830-ae71-70ad5cf346b7'),
(10, 27, 1, 6000, '2a9681e5-5f90-4830-ae71-70ad5cf346b7'),
(11, 30, 1, 353253, '930b5f8e-0389-403f-b83e-bd064b522feb'),
(12, 27, 1, 6000, '930b5f8e-0389-403f-b83e-bd064b522feb'),
(13, 20, 1, 21434, '930b5f8e-0389-403f-b83e-bd064b522feb'),
(14, 21, 1, 2500, '930b5f8e-0389-403f-b83e-bd064b522feb'),
(15, 22, 1, 6500, '930b5f8e-0389-403f-b83e-bd064b522feb'),
(16, 30, 1, 353253, '0313fb6b-0d0a-4d74-90f7-ab6562ed0fc4'),
(17, 27, 1, 6000, '0313fb6b-0d0a-4d74-90f7-ab6562ed0fc4'),
(18, 20, 1, 21434, '0313fb6b-0d0a-4d74-90f7-ab6562ed0fc4'),
(19, 21, 1, 2500, '0313fb6b-0d0a-4d74-90f7-ab6562ed0fc4'),
(20, 22, 1, 6500, '0313fb6b-0d0a-4d74-90f7-ab6562ed0fc4'),
(21, 30, 1, 353253, '0313fb6b-0d0a-4d74-90f7-ab6562ed0fc4'),
(22, 20, 1, 21434, '0313fb6b-0d0a-4d74-90f7-ab6562ed0fc4'),
(23, 21, 1, 2500, '0313fb6b-0d0a-4d74-90f7-ab6562ed0fc4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pagos`
--

CREATE TABLE `metodos_pagos` (
  `id_metodo` int(20) NOT NULL,
  `nombre_metodo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `metodos_pagos`
--

INSERT INTO `metodos_pagos` (`id_metodo`, `nombre_metodo`) VALUES
(1, 'Mercado pago'),
(2, 'Trajeta'),
(3, 'Transferencia'),
(4, 'Efectivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(11) NOT NULL,
  `uuid_pedido` char(36) NOT NULL,
  `id_metodo` int(11) NOT NULL,
  `monto` int(20) NOT NULL,
  `fecha_pago` varchar(30) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id_pago`, `uuid_pedido`, `id_metodo`, `monto`, `fecha_pago`, `estado`) VALUES
(61, '3d96bfea-ea99-4246-9ae2-e4b92b990c44', 4, 23934, '', 'pendiente'),
(62, 'd739a7a0-d39d-437e-be9b-fbc77c9ed263', 3, 13000, '2024-11-24', 'Pagado'),
(63, '2a9681e5-5f90-4830-ae71-70ad5cf346b7', 4, 359253, '', 'Pendiente'),
(64, '930b5f8e-0389-403f-b83e-bd064b522feb', 3, 389687, '2024-11-24', 'Pagado'),
(65, '0313fb6b-0d0a-4d74-90f7-ab6562ed0fc4', 4, 766874, '', 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `uuid_pedido` char(36) NOT NULL,
  `id_cliente` int(20) NOT NULL,
  `fecha` date NOT NULL,
  `estado` varchar(20) NOT NULL,
  `hora` time NOT NULL,
  `tipo_entrega` varchar(20) NOT NULL,
  `modificado` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`uuid_pedido`, `id_cliente`, `fecha`, `estado`, `hora`, `tipo_entrega`, `modificado`) VALUES
('0313fb6b-0d0a-4d74-90f7-ab6562ed0fc4', 3, '2024-11-25', 'ENTREGADO', '17:26:49', '', 1),
('2a9681e5-5f90-4830-ae71-70ad5cf346b7', 2, '2024-11-25', 'EN CAMINO', '17:23:26', 'Delivery', 0),
('3d96bfea-ea99-4246-9ae2-e4b92b990c44', 8, '2024-11-25', 'LISTO', '05:12:02', 'Retirar en Local', 0),
('930b5f8e-0389-403f-b83e-bd064b522feb', 1, '2024-11-25', 'EN PROCESO', '17:24:57', 'Consumir en el Local', 0),
('d739a7a0-d39d-437e-be9b-fbc77c9ed263', 8, '2024-11-25', 'EN PROCESO', '16:26:32', 'Consumir en el Local', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  `foto` varchar(60) NOT NULL,
  `precio` int(10) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `disponibilidad` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `foto`, `precio`, `id_categoria`, `disponibilidad`) VALUES
(1, 'Pizza Fugazza', 'Masa de pizza, cebolla caramelizada, aceite de oliva, orégano.', 'Images/11-15-24-10-25-49-Fugazza.jfif', 12000, 1, 0),
(2, 'Pizza Muzzarella', 'Salsa de tomate, mozzarella, aceite de oliva, orégano.', 'Images/11-18-24-10-37-43-3pVElVPsf_720x0__1.webp', 13000, 1, 0),
(3, 'Pizza Napolitana', ' Salsa de tomate, mozzarella, tomates frescos, ajo, albahaca fresca, aceite de oliva.', 'Images/11-18-24-10-46-22-DSC_1249-6.jpg', 15000, 1, 0),
(4, 'Pizza Jamón y Morrones', 'Salsa de tomate, mozzarella, jamón cocido, pimientos rojos asados, orégano.', 'Images/11-18-24-10-48-38-Receta-recetas-locos-x-la-parrilla-', 15000, 1, 0),
(5, 'Hamburguesa Clásica', 'Pan de hamburguesa, medallón de carne vacuna, lechuga, tomate, cebolla, queso cheddar, mayonesa.', 'Images/11-18-24-11-38-36-BestBurgersSF_GottsRoadside_impossi', 6000, 2, 0),
(6, 'Hamburguesa Criolla', 'Pan de hamburguesa, medallón de carne vacuna, huevo frito, queso, cebolla caramelizada, morrón asado, salsa criolla.', 'Images/11-18-24-11-41-03-category6.jpg', 7500, 2, 0),
(7, 'Hamburguesa Bacon y Cheddar', 'Pan de hamburguesa, medallón de carne vacuna, queso cheddar, panceta crocante, cebolla, salsa barbacoa.', 'Images/11-18-24-11-43-07-hamburguesa-DOBLE-CHEDDAR-BACON.png', 9000, 2, 0),
(8, 'Hamburguesa Napolitana', 'Pan de hamburguesa, medallón de carne vacuna, salsa de tomate, mozzarella, jamón cocido, orégano.', 'Images/11-18-24-11-46-00-Napo.png', 9000, 2, 0),
(9, 'Hamburguesa de Campo', 'Pan de hamburguesa, medallón de carne vacuna, rúcula, queso brie, cebolla morada, mayonesa al ajo.', 'Images/11-18-24-11-48-03-hamburguesas-caseras-receta.jpg', 9000, 2, 0),
(10, 'Sándwich de Milanesa', 'Pan francés, milanesa de carne o pollo, lechuga, tomate, mayonesa, y mostaza.(PAPAS Opcional)', 'Images/11-18-24-11-52-44-sandwich de mila conpapas.webp', 7000, 4, 0),
(11, 'Lomito Completo', 'Pan de lomito, lomo de carne vacuna a la plancha, jamón, queso, lechuga, tomate, huevo frito, mayonesa.', 'Images/11-18-24-11-54-13-lomito con papas.avif', 9000, 4, 0),
(12, 'Sándwich de Bondiola', 'Pan ciabatta, bondiola de cerdo asada, chimichurri, lechuga, tomate, cebolla caramelizada.', 'Images/11-18-24-11-56-06-f848x477-1261677_1319480_5050.webp', 9000, 4, 0),
(13, ' Sándwich de Jamón y Queso Tostado', 'Pan de molde, jamón cocido, queso mozzarella o queso fresco, manteca..', 'Images/11-18-24-11-58-32-210587.webp', 4500, 4, 0),
(14, 'Coca Cola', '500ml', 'Images/11-18-24-12-06-21-cocoa ml.jpeg', 1200, 7, 0),
(15, 'Coca-Cola', '1.5 litros', 'Images/11-18-24-12-12-10-unnamed.jpg', 2700, 7, 0),
(16, 'Fanta', '500 ml', 'Images/11-18-24-12-14-23-WL48L6QY6tMew43wE-800-x.webp', 1200, 7, 0),
(17, 'Fanta', '2 litros', 'Images/11-18-24-12-19-39-D_NQ_NP_706401-MLU74245659577_01202', 3100, 7, 0),
(18, 'Sprite', '1.5 litros', 'Images/11-18-24-12-22-19-D_NQ_NP_2X_619878-MLA54055740815_02', 2700, 7, 0),
(19, 'Sprite', '500ml', 'Images/11-18-24-12-23-41-D_NQ_NP_736878-MLU75124220173_03202', 1200, 7, 0),
(20, 'Cerveza Patagonia', '1litro', 'Images/11-18-24-12-28-58-D_817039-MLA50464478921_062022-X.we', 3200, 8, 0),
(21, 'Cerveza Corona', '710 ml', 'Images/11-18-24-12-29-50-D_986365-MLU78110012679_082024-X.we', 3100, 8, 0),
(22, 'Quilmes', '1 litro', 'Images/11-18-24-12-33-07-588362-500-auto.webp', 3200, 8, 0),
(23, 'Flan Casero con Dulce de Leche', 'Leche, azúcar, huevos, esencia de vainilla, dulce de leche.', 'Images/11-18-24-12-36-07-como-hacer-flan-de-huevo-casero.web', 4500, 9, 0),
(24, 'Vigilante (Queso y Dulce)', 'Queso fresco, dulce de membrillo o batata.', 'Images/11-18-24-12-39-04-quesoydulce02-re.webp', 3500, 9, 0),
(25, 'Helado Artesanal', 'Leche, crema, azúcar, sabores clásicos como dulce de leche, chocolate o frutilla.', 'Images/11-18-24-12-40-16-helado-artesanal.webp', 4500, 9, 0),
(26, 'Chocotorta', 'Galletitas de chocolate, queso crema, dulce de leche, cacao en polvo (para decorar).', 'Images/11-18-24-12-43-40-Receta-Chocotorta.webp', 4500, 9, 0),
(27, 'Flan con Dulce de Leche y Crema', 'Leche, azúcar, huevos, esencia de vainilla, dulce de leche, crema batida.', 'Images/11-18-24-12-46-43-md.webp', 4500, 9, 0),
(28, 'Lemon pie', 'Porcion', 'Images/11-08-24-18-10-49-lemon pie.jpg', 6000, 9, 0),
(29, 'Pizza Rúcula y Jamón Crudo', 'Mozzarella, rúcula fresca, jamón crudo, lascas de queso parmesano, aceite de oliva.', 'Images/11-18-24-10-54-36-pizza-de-rucula.webp', 15000, 1, 0),
(31, 'Cerveza Corona', 'ml', 'Images/11-07-24-05-11-59-corona.jpg', 6500, 8, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `cantidad_personas` varchar(11) NOT NULL,
  `fecha` date NOT NULL,
  `motivo` text NOT NULL,
  `estado` varchar(50) NOT NULL,
  `hora` time NOT NULL,
  `mesa_asignada` int(11) NOT NULL,
  `archivada` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_cliente`, `cantidad_personas`, `fecha`, `motivo`, `estado`, `hora`, `mesa_asignada`, `archivada`) VALUES
(1, 2, '5', '2024-11-01', 'Cumpleaños de mi hijo ', '2024-10-22', '00:00:00', 0, 0),
(2, 2, '4', '2024-11-01', 'cumpleaños', '', '22:00:00', 0, 0),
(3, 3, '4', '2024-11-01', 'rgfdg', 'pendiente', '21:40:00', 0, 0),
(4, 4, '15', '2024-11-11', 'Cumple Feliz', 'confirmada', '20:22:00', 0, 1),
(5, 5, '3', '2024-11-11', 'Alog de la vida', 'pendiente', '22:22:00', 0, 1),
(6, 5, '4', '2024-11-11', 'Algo Emotivo', 'pendiente', '22:22:00', 0, 1),
(7, 1, '4', '2024-11-10', 'Festejo de la  terminacion del proyecto \"Boha \".', 'confirmada', '22:15:00', 0, 1),
(8, 1, '4', '2024-11-01', 'cumpleaños', 'confirmado', '18:30:00', 1, 0),
(9, 2, '2', '2024-11-02', 'reunión', 'cancelado', '13:00:00', 2, 0),
(10, 3, '5', '2024-11-03', 'familia', 'confirmado', '20:00:00', 3, 0),
(11, 4, '3', '2024-11-04', 'negocios', 'confirmado', '19:30:00', 4, 0),
(12, 5, '6', '2024-11-05', 'aniversario', 'pendiente', '17:00:00', 5, 0),
(13, 1, '2', '2024-11-06', 'cena', 'confirmado', '18:00:00', 1, 0),
(14, 2, '8', '2024-11-07', 'celebración', 'cancelado', '14:30:00', 2, 0),
(15, 3, '4', '2024-11-08', 'reencuentro', 'confirmado', '20:15:00', 3, 0),
(16, 4, '3', '2024-11-09', 'negocios', 'confirmado', '12:00:00', 4, 0),
(17, 5, '1', '2024-11-10', 'solo', 'confirmado', '19:45:00', 5, 0),
(18, 6, '78', '2024-11-10', 'CUMPLE FELIZ', 'cancelada', '22:00:00', 0, 0),
(19, 6, '78', '2024-11-10', 'CUMPLE FELIZ', 'pendiente', '22:00:00', 0, 1),
(20, 7, '15', '2024-11-29', 'cumpleaños', 'pendiente', '19:20:00', 0, 0),
(21, 1, '4', '2024-09-01', 'Cumpleaños', 'pendiente', '12:00:00', 1, 0),
(22, 2, '2', '2024-09-02', 'Reunión de Amigos', 'confirmado', '14:00:00', 2, 1),
(23, 3, '5', '2024-09-03', 'Cena Familiar', 'cancelado', '19:00:00', 3, 0),
(24, 4, '3', '2024-09-04', 'Aniversario', 'pendiente', '20:00:00', 4, 1),
(25, 5, '6', '2024-09-05', 'Cumpleaños', 'confirmado', '13:00:00', 5, 0),
(26, 6, '4', '2024-09-06', 'Reunión de Amigos', 'cancelado', '15:00:00', 6, 1),
(27, 7, '2', '2024-09-07', 'Cena de Negocios', 'pendiente', '18:00:00', 7, 0),
(28, 1, '8', '2024-09-08', 'Cumpleaños', 'confirmado', '12:30:00', 8, 1),
(29, 2, '3', '2024-09-09', 'Cena Familiar', 'pendiente', '19:30:00', 9, 0),
(30, 3, '7', '2024-09-10', 'Aniversario', 'cancelado', '21:00:00', 10, 1),
(31, 4, '4', '2024-09-11', 'Cumpleaños', 'confirmado', '11:00:00', 11, 0),
(32, 5, '5', '2024-09-12', 'Cena de Negocios', 'pendiente', '13:30:00', 12, 1),
(33, 6, '6', '2024-09-13', 'Reunión de Amigos', 'cancelado', '20:30:00', 13, 0),
(34, 7, '3', '2024-09-14', 'Cumpleaños', 'confirmado', '19:15:00', 14, 1),
(35, 1, '5', '2024-09-15', 'Cena Familiar', 'pendiente', '17:00:00', 15, 0),
(36, 2, '4', '2024-09-16', 'Reunión de Amigos', 'cancelado', '16:00:00', 16, 1),
(37, 3, '2', '2024-09-17', 'Cumpleaños', 'confirmado', '20:45:00', 17, 0),
(38, 4, '6', '2024-09-18', 'Cena de Negocios', 'pendiente', '15:15:00', 18, 1),
(39, 5, '8', '2024-09-19', 'Cumpleaños', 'cancelado', '14:30:00', 19, 0),
(40, 6, '3', '2024-09-20', 'Cena Familiar', 'confirmado', '13:45:00', 20, 1),
(41, 7, '7', '2024-09-21', 'Cumpleaños', 'pendiente', '12:45:00', 1, 0),
(42, 1, '4', '2024-09-22', 'Cena de Negocios', 'cancelado', '16:30:00', 2, 1),
(43, 2, '2', '2024-09-23', 'Reunión de Amigos', 'confirmado', '18:30:00', 3, 0),
(44, 3, '5', '2024-09-24', 'Cumpleaños', 'pendiente', '20:30:00', 4, 1),
(45, 4, '6', '2024-09-25', 'Cena Familiar', 'cancelado', '19:15:00', 5, 0),
(46, 5, '3', '2024-09-26', 'Reunión de Amigos', 'confirmado', '14:15:00', 6, 1),
(47, 6, '4', '2024-09-27', 'Cumpleaños', 'pendiente', '17:45:00', 7, 0),
(48, 7, '2', '2024-09-28', 'Cena Familiar', 'cancelado', '13:30:00', 8, 1),
(49, 1, '8', '2024-09-29', 'Cena de Negocios', 'confirmado', '18:30:00', 9, 0),
(50, 2, '4', '2024-09-30', 'Reunión de Amigos', 'pendiente', '12:15:00', 10, 1),
(51, 1, '4', '2024-09-01', 'Cumpleaños', 'pendiente', '12:00:00', 1, 0),
(52, 2, '2', '2024-09-02', 'Reunión de Amigos', 'confirmado', '14:00:00', 2, 1),
(53, 3, '5', '2024-09-03', 'Cena Familiar', 'cancelado', '19:00:00', 3, 0),
(54, 4, '3', '2024-09-04', 'Aniversario', 'pendiente', '20:00:00', 4, 1),
(55, 5, '6', '2024-09-05', 'Cumpleaños', 'confirmado', '13:00:00', 5, 0),
(56, 6, '4', '2024-09-06', 'Reunión de Amigos', 'cancelado', '15:00:00', 6, 1),
(57, 7, '2', '2024-09-07', 'Cena de Negocios', 'pendiente', '18:00:00', 7, 0),
(58, 1, '8', '2024-09-08', 'Cumpleaños', 'confirmado', '12:30:00', 8, 1),
(59, 2, '3', '2024-09-09', 'Cena Familiar', 'pendiente', '19:30:00', 9, 0),
(60, 3, '7', '2024-09-10', 'Aniversario', 'cancelado', '21:00:00', 10, 1),
(61, 4, '4', '2024-09-11', 'Cumpleaños', 'confirmado', '11:00:00', 11, 0),
(62, 5, '5', '2024-09-12', 'Cena de Negocios', 'pendiente', '13:30:00', 12, 1),
(63, 6, '6', '2024-09-13', 'Reunión de Amigos', 'cancelado', '20:30:00', 13, 0),
(64, 7, '3', '2024-09-14', 'Cumpleaños', 'confirmado', '19:15:00', 14, 1),
(65, 1, '5', '2024-09-15', 'Cena Familiar', 'pendiente', '17:00:00', 15, 0),
(66, 2, '4', '2024-09-16', 'Reunión de Amigos', 'cancelado', '16:00:00', 16, 1),
(67, 3, '2', '2024-09-17', 'Cumpleaños', 'confirmado', '20:45:00', 17, 0),
(68, 4, '6', '2024-09-18', 'Cena de Negocios', 'pendiente', '15:15:00', 18, 1),
(69, 5, '8', '2024-09-19', 'Cumpleaños', 'cancelado', '14:30:00', 19, 0),
(70, 6, '3', '2024-09-20', 'Cena Familiar', 'confirmado', '13:45:00', 20, 1),
(71, 7, '7', '2024-09-21', 'Cumpleaños', 'pendiente', '12:45:00', 1, 0),
(72, 1, '4', '2024-09-22', 'Cena de Negocios', 'cancelado', '16:30:00', 2, 1),
(73, 2, '2', '2024-09-23', 'Reunión de Amigos', 'confirmado', '18:30:00', 3, 0),
(74, 3, '5', '2024-09-24', 'Cumpleaños', 'pendiente', '20:30:00', 4, 1),
(75, 4, '6', '2024-09-25', 'Cena Familiar', 'cancelado', '19:15:00', 5, 0),
(76, 5, '3', '2024-09-26', 'Reunión de Amigos', 'confirmado', '14:15:00', 6, 1),
(77, 6, '4', '2024-09-27', 'Cumpleaños', 'pendiente', '17:45:00', 7, 0),
(78, 7, '2', '2024-09-28', 'Cena Familiar', 'cancelado', '13:30:00', 8, 1),
(79, 1, '8', '2024-09-29', 'Cena de Negocios', 'confirmado', '18:30:00', 9, 0),
(80, 2, '4', '2024-09-30', 'Reunión de Amigos', 'pendiente', '12:15:00', 10, 1),
(81, 1, '6', '2024-11-01', 'Cumpleaños', 'pendiente', '12:00:00', 1, 1),
(82, 2, '8', '2024-11-02', 'Reunión de Amigos', 'confirmado', '14:00:00', 2, 0),
(83, 3, '3', '2024-11-03', 'Cena Familiar', 'cancelado', '19:00:00', 3, 1),
(84, 4, '5', '2024-11-04', 'Aniversario', 'pendiente', '20:00:00', 4, 0),
(85, 5, '7', '2024-11-05', 'Cumpleaños', 'confirmado', '13:00:00', 5, 1),
(86, 6, '4', '2024-11-06', 'Reunión de Amigos', 'cancelado', '15:00:00', 6, 0),
(87, 7, '3', '2024-11-07', 'Cena de Negocios', 'pendiente', '18:00:00', 7, 1),
(88, 1, '2', '2024-11-08', 'Cena Familiar', 'confirmado', '12:30:00', 8, 0),
(89, 2, '4', '2024-11-09', 'Aniversario', 'cancelado', '19:30:00', 9, 1),
(90, 3, '5', '2024-11-10', 'Cumpleaños', 'pendiente', '20:00:00', 10, 0),
(91, 4, '6', '2024-11-11', 'Reunión de Amigos', 'confirmado', '11:00:00', 11, 1),
(92, 5, '7', '2024-11-12', 'Cena de Negocios', 'cancelado', '13:30:00', 12, 0),
(93, 6, '4', '2024-11-13', 'Cumpleaños', 'pendiente', '14:00:00', 13, 1),
(94, 7, '3', '2024-11-14', 'Cena Familiar', 'confirmado', '19:15:00', 14, 0),
(95, 1, '8', '2024-11-15', 'Aniversario', 'cancelado', '20:30:00', 15, 1),
(96, 2, '6', '2024-11-16', 'Cumpleaños', 'pendiente', '17:45:00', 16, 0),
(97, 3, '5', '2024-11-17', 'Reunión de Amigos', 'confirmado', '16:00:00', 17, 1),
(98, 4, '3', '2024-11-18', 'Cena de Negocios', 'cancelado', '18:30:00', 18, 0),
(99, 5, '2', '2024-11-19', 'Cumpleaños', 'pendiente', '12:00:00', 19, 1),
(100, 6, '6', '2024-11-20', 'Cena Familiar', 'confirmado', '14:15:00', 20, 0),
(101, 7, '4', '2024-11-21', 'Aniversario', 'cancelado', '13:45:00', 1, 1),
(102, 1, '3', '2024-11-22', 'Reunión de Amigos', 'pendiente', '17:30:00', 2, 0),
(103, 2, '7', '2024-11-23', 'Cena de Negocios', 'confirmado', '19:00:00', 3, 1),
(104, 3, '6', '2024-11-24', 'Cumpleaños', 'cancelado', '20:15:00', 4, 0),
(105, 4, '4', '2024-11-25', 'Cena Familiar', 'pendiente', '16:30:00', 5, 1),
(106, 5, '5', '2024-11-26', 'Reunión de Amigos', 'confirmado', '13:00:00', 6, 0),
(107, 6, '8', '2024-11-27', 'Aniversario', 'cancelado', '14:30:00', 7, 1),
(108, 7, '3', '2024-11-28', 'Cumpleaños', 'pendiente', '17:00:00', 8, 0),
(109, 1, '2', '2024-11-29', 'Cena Familiar', 'confirmado', '20:00:00', 9, 1),
(110, 2, '4', '2024-11-30', 'Reunión de Amigos', 'cancelado', '18:45:00', 10, 0),
(111, 1, '4', '2024-10-01', 'Cumpleaños', 'pendiente', '12:00:00', 1, 0),
(112, 2, '2', '2024-10-02', 'Reunión de Amigos', 'confirmado', '14:00:00', 2, 1),
(113, 3, '5', '2024-10-03', 'Cena Familiar', 'cancelado', '19:00:00', 3, 0),
(114, 4, '3', '2024-10-04', 'Aniversario', 'pendiente', '20:00:00', 4, 1),
(115, 5, '6', '2024-10-05', 'Cumpleaños', 'confirmado', '13:00:00', 5, 0),
(116, 6, '4', '2024-10-06', 'Reunión de Amigos', 'cancelado', '15:00:00', 6, 1),
(117, 7, '3', '2024-10-07', 'Cena de Negocios', 'pendiente', '18:00:00', 7, 0),
(118, 1, '2', '2024-10-08', 'Cena Familiar', 'confirmado', '12:30:00', 8, 1),
(119, 2, '4', '2024-10-09', 'Aniversario', 'cancelado', '19:30:00', 9, 0),
(120, 3, '5', '2024-10-10', 'Cumpleaños', 'pendiente', '20:00:00', 10, 1),
(121, 4, '6', '2024-10-11', 'Reunión de Amigos', 'confirmado', '11:00:00', 11, 0),
(122, 5, '7', '2024-10-12', 'Cena de Negocios', 'cancelado', '13:30:00', 12, 1),
(123, 6, '4', '2024-10-13', 'Cumpleaños', 'pendiente', '14:00:00', 13, 0),
(124, 7, '3', '2024-10-14', 'Cena Familiar', 'confirmado', '19:15:00', 14, 1),
(125, 1, '8', '2024-10-15', 'Aniversario', 'cancelado', '20:30:00', 15, 0),
(126, 2, '6', '2024-10-16', 'Cumpleaños', 'pendiente', '17:45:00', 16, 1),
(127, 3, '5', '2024-10-17', 'Reunión de Amigos', 'confirmado', '16:00:00', 17, 0),
(128, 4, '3', '2024-10-18', 'Cena de Negocios', 'cancelado', '18:30:00', 18, 1),
(129, 5, '2', '2024-10-19', 'Cumpleaños', 'pendiente', '12:00:00', 19, 0),
(130, 6, '6', '2024-10-20', 'Cena Familiar', 'confirmado', '14:15:00', 20, 1),
(131, 7, '4', '2024-10-21', 'Aniversario', 'cancelado', '13:45:00', 1, 0),
(132, 1, '3', '2024-10-22', 'Reunión de Amigos', 'pendiente', '17:30:00', 2, 1),
(133, 2, '7', '2024-10-23', 'Cena de Negocios', 'confirmado', '19:00:00', 3, 0),
(134, 3, '6', '2024-10-24', 'Cumpleaños', 'cancelado', '20:15:00', 4, 1),
(135, 4, '4', '2024-10-25', 'Cena Familiar', 'pendiente', '16:30:00', 5, 0),
(136, 5, '5', '2024-10-26', 'Reunión de Amigos', 'confirmado', '13:00:00', 6, 1),
(137, 6, '8', '2024-10-27', 'Aniversario', 'cancelado', '14:30:00', 7, 0),
(138, 7, '3', '2024-10-28', 'Cumpleaños', 'pendiente', '17:00:00', 8, 1),
(139, 1, '2', '2024-10-29', 'Cena Familiar', 'confirmado', '20:00:00', 9, 0),
(140, 2, '4', '2024-10-30', 'Reunión de Amigos', 'cancelado', '18:45:00', 10, 1),
(141, 1, '4', '2024-12-31', 'Fiesta de Fin de Año', 'confirmado', '20:00:00', 1, 0),
(142, 2, '5', '2024-12-31', 'Reunión Familiar', 'pendiente', '21:00:00', 2, 1),
(143, 3, '6', '2024-12-31', 'Celebración de Año Nuevo', 'cancelado', '22:00:00', 3, 0),
(144, 4, '3', '2024-12-31', 'Cena de Nochevieja', 'confirmado', '23:00:00', 4, 1),
(145, 5, '2', '2024-12-31', 'Reunión de Amigos', 'pendiente', '18:00:00', 5, 0),
(146, 6, '8', '2024-12-24', 'Cena de Navidad', 'confirmado', '20:00:00', 6, 1),
(147, 7, '4', '2024-12-24', 'Fiesta de Navidad', 'pendiente', '19:30:00', 7, 0),
(148, 1, '5', '2024-12-24', 'Reunión Familiar', 'confirmado', '22:00:00', 8, 1),
(149, 2, '3', '2024-12-24', 'Cena Especial', 'cancelado', '21:00:00', 9, 0),
(150, 3, '6', '2024-12-24', 'Celebración Navideña', 'pendiente', '20:30:00', 10, 1),
(151, 4, '4', '2024-12-24', 'Reunión de Amigos', 'confirmado', '18:30:00', 11, 0),
(152, 5, '2', '2024-12-24', 'Cena Navideña', 'cancelado', '19:00:00', 12, 1),
(153, 6, '5', '2024-12-12', 'Cena de Navidad Previa', 'pendiente', '18:00:00', 13, 0),
(154, 7, '3', '2024-12-12', 'Reunión Familiar', 'confirmado', '20:30:00', 14, 1),
(155, 1, '4', '2024-12-12', 'Fiesta de Fin de Año Temprana', 'cancelado', '19:15:00', 15, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `contrasena`, `email`) VALUES
(1, 'Admin', 'admin123', 'boharestaurant@gmail.com');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `detalle`
--
ALTER TABLE `detalle`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `fk_detalle` (`uuid_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `metodos_pagos`
--
ALTER TABLE `metodos_pagos`
  ADD PRIMARY KEY (`id_metodo`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `uuid_pedido` (`uuid_pedido`),
  ADD KEY `id_metodo` (`id_metodo`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`uuid_pedido`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `detalle`
--
ALTER TABLE `detalle`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `metodos_pagos`
--
ALTER TABLE `metodos_pagos`
  MODIFY `id_metodo` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

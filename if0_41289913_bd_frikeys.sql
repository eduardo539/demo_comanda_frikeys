-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql201.infinityfree.com
-- Tiempo de generación: 24-03-2026 a las 13:19:37
-- Versión del servidor: 11.4.10-MariaDB
-- Versión de PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `if0_41289913_bd_frikeys`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `categoria_id` int(11) NOT NULL,
  `categoria` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`categoria_id`, `categoria`) VALUES
(9, 'Cafés'),
(10, 'Frappés'),
(11, 'Bebidas'),
(12, 'Desayunos'),
(13, 'Comida Casera'),
(14, 'Comida Rápida'),
(15, 'Antojitos'),
(16, 'Postres');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `detalle_id` int(11) NOT NULL,
  `folio` varchar(100) NOT NULL,
  `fecha` datetime NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `total` double NOT NULL,
  `mesa_id` int(11) NOT NULL,
  `estado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`detalle_id`, `folio`, `fecha`, `producto_id`, `cantidad`, `total`, `mesa_id`, `estado_id`) VALUES
(4, 'FOL-20260310-6401', '2026-03-10 22:29:11', 13, 1, 50, 7, 3),
(5, 'FOL-20260310-1553', '2026-03-10 22:43:17', 17, 1, 40, 7, 3),
(6, 'FOL-20260310-1553', '2026-03-10 22:43:17', 25, 1, 90, 7, 3),
(7, 'FOL-20260311-9002', '2026-03-11 00:21:46', 16, 1, 55, 7, 1),
(8, 'FOL-20260311-9002', '2026-03-11 00:21:46', 19, 1, 30, 7, 1),
(9, 'FOL-20260311-4605', '2026-03-11 08:32:02', 41, 1, 55, 7, 1),
(10, 'FOL-20260311-4605', '2026-03-11 08:32:02', 22, 2, 140, 7, 1),
(11, 'FOL-20260311-6991', '2026-03-11 08:59:26', 39, 1, 70, 7, 3),
(12, 'FOL-20260311-7490', '2026-03-11 23:10:14', 13, 1, 50, 7, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `estado_gen_id` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`estado_gen_id`, `estado`) VALUES
(1, 'ACTIVO'),
(2, 'INACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_pedido`
--

CREATE TABLE `estado_pedido` (
  `estado_id` int(11) NOT NULL,
  `estado_pedido` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_pedido`
--

INSERT INTO `estado_pedido` (`estado_id`, `estado_pedido`) VALUES
(1, 'RECIBIDO'),
(2, 'PREPARANDO'),
(3, 'ENTREGADO'),
(4, 'CANCELADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `mesa_id` int(11) NOT NULL,
  `nombre_mesa` varchar(50) NOT NULL,
  `uuid` varchar(500) NOT NULL,
  `qr_img` text NOT NULL,
  `estado_gen_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesa`
--

INSERT INTO `mesa` (`mesa_id`, `nombre_mesa`, `uuid`, `qr_img`, `estado_gen_id`) VALUES
(7, 'MESA 1', 'dddc0500-c9fc-4f2f-9fea-0b60234644e5', '/../public/img_public/qr_dddc0500-c9fc-4f2f-9fea-0b60234644e5.png', 1),
(8, 'MESA 2', '86550ef4-e82d-4729-8b7d-ff18e636651f', '/../public/img_public/qr_86550ef4-e82d-4729-8b7d-ff18e636651f.png', 2),
(9, 'MESA 3', '2b9f20dd-db1d-4cec-9071-4ba02b5c0d04', '/../public/img_public/qr_2b9f20dd-db1d-4cec-9071-4ba02b5c0d04.png', 2),
(13, 'MESA 4', 'e525b366-9e17-4b4f-b16f-d22febd1b1af', '/../public/img_public/qr_e525b366-9e17-4b4f-b16f-d22febd1b1af.png', 2),
(14, 'MESA 5', 'e1a9b1e2-5064-4e37-a32d-66af223156ca', '/../public/img_public/qr_e1a9b1e2-5064-4e37-a32d-66af223156ca.png', 2),
(15, 'MESA 6', '45c1a1f0-ca01-4fd0-bd43-411a88ddf6a4', '/../public/img_public/qr_45c1a1f0-ca01-4fd0-bd43-411a88ddf6a4.png', 2),
(16, 'MESA 7', '46d7a509-9cef-4e86-ba88-d36e6798a701', '/../public/img_public/qr_46d7a509-9cef-4e86-ba88-d36e6798a701.png', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `producto_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` varchar(500) NOT NULL,
  `costo` double NOT NULL,
  `estado_gen_id` int(11) NOT NULL,
  `imagen` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`producto_id`, `categoria_id`, `nombre`, `descripcion`, `costo`, `estado_gen_id`, `imagen`) VALUES
(12, 9, 'Café Americano', 'Café negro preparado con espresso y agua caliente sabor suave y aromático', 35, 1, '/../public/img_public/99c5d2bf2e276426e607b91d6fe14879.jpg'),
(13, 9, 'Capuchino', 'Espresso con leche vaporizada y espuma cremosa con un toque de cacao o canela', 50, 1, '/../public/img_public/c6816035168e16815677e1956531d6f5.jpg'),
(14, 9, 'Café con Leche', 'Café tradicional combinado con leche caliente para un sabor suave', 45, 1, '/../public/img_public/27b7f6758d946bbd4491d14e789d3fb5.jpg'),
(15, 9, 'Moka', 'Café espresso mezclado con chocolate y leche caliente', 60, 1, '/../public/img_public/74636647cd4528d65192d3c9dcd5896b.jpg'),
(16, 9, 'Café Latte', 'Espresso con abundante leche caliente y ligera capa de espuma', 55, 1, '/../public/img_public/a433d7de1617f01b7128462eadcee225.jpg'),
(17, 11, 'Limonada Natural', 'Bebida refrescante preparada con limón natural agua y azúcar', 40, 1, '/../public/img_public/3eadbe74a9f1e1d3ef05999329a4536c.jpg'),
(18, 11, 'Té Helado', 'Té frío servido con hielo y un toque de limón', 35, 1, '/../public/img_public/27d04adf5c36c5689d2f4cae40b9bf91.jpg'),
(19, 11, 'Coca Cola', 'Bebida gaseosa fría en presentación individual', 30, 1, '/../public/img_public/9111433e51bcbc0993d5dab9b8d5ad20.jpg'),
(20, 11, 'Agua Mineral', 'Agua con gas refrescante', 30, 1, '/../public/img_public/4e9882c7b8415f59939ef8629b99eeff.png'),
(21, 10, 'Frappé de Galleta', 'Frappé preparado con galletas trituradas leche y crema batida', 75, 1, '/../public/img_public/ca4dfe12c78bc4a2654d3aa308bd38fd.jpg'),
(22, 10, 'Frappé de Chocolate', 'Bebida cremosa de chocolate mezclada con hielo y leche', 70, 1, '/../public/img_public/742f6e7679a38612c39bd8af50bf2dfe.jpg'),
(23, 10, 'Frappé de Vainilla', 'Frappé frío con sabor suave a vainilla', 65, 1, '/../public/img_public/db23dd203a00e000f77cc0cd86465765.jpg'),
(24, 12, 'Enchiladas Rojas', 'Enchiladas con pollo en salsa roja con crema queso y cebolla', 85, 1, '/../public/img_public/9274fc4b4f67df85194c7dec4fa03e6b.jpg'),
(25, 12, 'Chilaquiles', 'Totopos en salsa roja o verde con crema queso y pollo opcional', 90, 1, '/../public/img_public/a96c6b432a362b65f62ea3622aacf1ea.jpg'),
(26, 12, 'Huevos al Gusto', 'Huevos revueltos o estrellados acompañados con frijoles y tortillas', 75, 1, '/../public/img_public/4ae3235f53b990bcb96fc919af1e8419.jpg'),
(27, 12, 'Hot Cakes', 'Hot cakes esponjosos con mantequilla y miel o jarabe', 80, 1, '/../public/img_public/bbeb80a256712cca15336df57faeb5a2.jpg'),
(28, 13, 'Enchiladas Suizas', 'Tortillas rellenas de pollo bañadas en salsa verde cremosa y queso gratinado', 110, 1, '/../public/img_public/7c58900621b1b437aad5023e2b95481c.jpg'),
(29, 13, 'Milanesa con Papas', 'Filete empanizado crujiente acompañado con papas fritas y ensalada', 120, 1, '/../public/img_public/8eb420906faa537d0522dc13b4330303.jpg'),
(30, 13, 'Ensalada de Pollo', 'Mezcla fresca de lechuga tomate con pollo y aderezo', 95, 1, '/../public/img_public/2c4990a55823e11377b34e2e5a61cf9a.jpg'),
(31, 14, 'Hamburguesa Clásica', 'Carne de res con lechuga tomate queso y aderezos', 90, 1, '/../public/img_public/2388da19f2db71e9a0eb9dfc23869875.png'),
(32, 14, 'Hamburguesa Especial', 'Carne con doble queso, tocino y vegetales con aderezo de la casa', 110, 1, '/../public/img_public/5b798f8c14089e959a186d4458e8bcef.jpg'),
(33, 14, 'Hot Dog', 'Salchicha en pan suave con cátsup, mostaza y complementos', 45, 1, '/../public/img_public/e62b088cf17c36d7e3d7a1934778ccc0.jpg'),
(34, 14, 'Nuggets de Pollo', '6 Trozos de pollo empanizado crujiente acompañados con papas', 85, 1, '/../public/img_public/35d4c00a301e2a466d6a15cca732ba9b.jpg'),
(35, 15, 'Papas Fritas', 'Papas crujientes fritas sazonadas con sal', 60, 1, '/../public/img_public/7339f8ef05279aca6bc8b1bef4c5b353.jpg'),
(36, 15, 'Nachos con Queso', 'Totopos crujientes cubiertos con queso derretido', 75, 1, '/../public/img_public/8b84c528879b4cb68bdb5688749034d2.jpg'),
(37, 15, 'Quesadillas', '2 Quesadillas rellenas de queso derretido servidas calientes', 65, 1, '/../public/img_public/184671a47a98558b4b44e2ca90fc0eec.jpg'),
(38, 15, 'Alitas de Pollo', 'Alitas crujientes bañadas en salsa BBQ o picante', 110, 1, '/../public/img_public/9593b8d5a7c76fe791771fd87d8f139a.jpg'),
(39, 16, 'Pastel de Chocolate', 'Rebanada de pastel suave de chocolate con cobertura dulce', 70, 1, '/../public/img_public/77885cea066999870d15ff68ba8b6cd8.jpg'),
(40, 16, 'Pay de Queso', 'Postre cremoso sobre base crujiente de galleta', 75, 1, '/../public/img_public/40b9f3adf17385fd6eadfdd7aaa9a1f4.jpg'),
(41, 16, 'Brownie', 'Pastelito de chocolate denso y húmedo', 55, 1, '/../public/img_public/3354ae0e512938cfcf692723f29dff72.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `rol_id` int(11) NOT NULL,
  `nombre_rol` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`rol_id`, `nombre_rol`) VALUES
(1, 'ADMINISTRADOR'),
(2, 'COCINA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `user_id` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Apellidos` varchar(250) NOT NULL,
  `telefono` varchar(10) NOT NULL,
  `edad` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `passw` varchar(200) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `estado_gen_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`user_id`, `Nombre`, `Apellidos`, `telefono`, `edad`, `usuario`, `passw`, `rol_id`, `estado_gen_id`) VALUES
(2, 'LINDA JHOANA', 'MELENDREZ', '1234568789', 23, 'linda123', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 1, 1),
(4, 'Karla Eunice', 'Martínez', '1234568790', 21, 'karla123', 'dbbaf87ba5f76398286b95743d8fbca9928088b8c11f8fd87e792e23c31c1c07', 2, 1),
(8, 'Perla', 'Cortes', '7711205910', 19, 'perla13', 'dbbaf87ba5f76398286b95743d8fbca9928088b8c11f8fd87e792e23c31c1c07', 2, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`categoria_id`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`detalle_id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `mesa_id` (`mesa_id`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`estado_gen_id`);

--
-- Indices de la tabla `estado_pedido`
--
ALTER TABLE `estado_pedido`
  ADD PRIMARY KEY (`estado_id`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`mesa_id`),
  ADD KEY `uuid` (`estado_gen_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`producto_id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `nombre` (`estado_gen_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`rol_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `rol_id` (`rol_id`),
  ADD KEY `estado_gen_id` (`estado_gen_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `categoria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `detalle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `estado_gen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estado_pedido`
--
ALTER TABLE `estado_pedido`
  MODIFY `estado_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `mesa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `producto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `rol_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `estado_id` FOREIGN KEY (`estado_id`) REFERENCES `estado_pedido` (`estado_id`),
  ADD CONSTRAINT `mesa_id` FOREIGN KEY (`mesa_id`) REFERENCES `mesa` (`mesa_id`),
  ADD CONSTRAINT `producto_id` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`producto_id`);

--
-- Filtros para la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD CONSTRAINT `uuid` FOREIGN KEY (`estado_gen_id`) REFERENCES `estados` (`estado_gen_id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `categoria_id` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`),
  ADD CONSTRAINT `nombre` FOREIGN KEY (`estado_gen_id`) REFERENCES `estados` (`estado_gen_id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `estado_gen_id` FOREIGN KEY (`estado_gen_id`) REFERENCES `estados` (`estado_gen_id`),
  ADD CONSTRAINT `rol_id` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`rol_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

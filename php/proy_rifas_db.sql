-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 22-09-2025 a las 22:06:47
-- Versión del servidor: 10.11.11-MariaDB-ubu2204
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proy_rifas_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `numeros_rifa`
--

CREATE TABLE `numeros_rifa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_rifa` bigint(20) UNSIGNED NOT NULL,
  `id_reserva` bigint(20) UNSIGNED DEFAULT NULL,
  `numero` int(11) NOT NULL,
  `estado` enum('libre','reservado','vendido') DEFAULT 'libre',
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `numeros_rifa`
--

INSERT INTO `numeros_rifa` (`id`, `id_rifa`, `id_reserva`, `numero`, `estado`, `creado_en`, `actualizado_en`) VALUES
(1, 1, NULL, 1, 'libre', '2025-09-20 02:37:36', '2025-09-21 22:04:44'),
(2, 1, NULL, 2, 'libre', '2025-09-20 02:37:36', '2025-09-21 22:04:44'),
(3, 1, NULL, 3, 'libre', '2025-09-20 02:37:36', '2025-09-20 02:37:36'),
(4, 1, NULL, 4, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(5, 1, NULL, 5, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(6, 1, NULL, 6, 'libre', '2025-09-20 02:37:37', '2025-09-21 22:28:43'),
(7, 1, NULL, 7, 'libre', '2025-09-20 02:37:37', '2025-09-21 21:49:35'),
(8, 1, NULL, 8, 'libre', '2025-09-20 02:37:37', '2025-09-21 22:28:43'),
(9, 1, NULL, 9, 'libre', '2025-09-20 02:37:37', '2025-09-21 22:28:43'),
(10, 1, NULL, 10, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(11, 1, NULL, 11, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(12, 1, NULL, 12, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(13, 1, NULL, 13, 'libre', '2025-09-20 02:37:37', '2025-09-21 22:05:31'),
(14, 1, NULL, 14, 'libre', '2025-09-20 02:37:37', '2025-09-21 22:11:05'),
(15, 1, NULL, 15, 'libre', '2025-09-20 02:37:37', '2025-09-21 22:11:05'),
(16, 1, NULL, 16, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(17, 1, NULL, 17, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(18, 1, NULL, 18, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(19, 1, NULL, 19, 'libre', '2025-09-20 02:37:37', '2025-09-21 19:35:14'),
(20, 1, NULL, 20, 'libre', '2025-09-20 02:37:37', '2025-09-21 19:35:14'),
(21, 1, NULL, 21, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(22, 1, NULL, 22, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(23, 1, NULL, 23, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(24, 1, NULL, 24, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(25, 1, NULL, 25, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(26, 1, NULL, 26, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(27, 1, NULL, 27, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(28, 1, NULL, 28, 'libre', '2025-09-20 02:37:37', '2025-09-21 19:26:49'),
(29, 1, NULL, 29, 'libre', '2025-09-20 02:37:37', '2025-09-21 19:26:49'),
(30, 1, NULL, 30, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(31, 1, NULL, 31, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(32, 1, NULL, 32, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(33, 1, NULL, 33, 'libre', '2025-09-20 02:37:37', '2025-09-21 21:49:35'),
(34, 1, NULL, 34, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(35, 1, NULL, 35, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(36, 1, NULL, 36, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(37, 1, NULL, 37, 'libre', '2025-09-20 02:37:37', '2025-09-21 22:10:33'),
(38, 1, NULL, 38, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(39, 1, NULL, 39, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(40, 1, NULL, 40, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(41, 1, NULL, 41, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(42, 1, NULL, 42, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(43, 1, NULL, 43, 'libre', '2025-09-20 02:37:37', '2025-09-21 22:28:43'),
(44, 1, NULL, 44, 'libre', '2025-09-20 02:37:37', '2025-09-21 22:28:43'),
(45, 1, NULL, 45, 'libre', '2025-09-20 02:37:37', '2025-09-21 22:28:43'),
(46, 1, NULL, 46, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(47, 1, NULL, 47, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(48, 1, NULL, 48, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(49, 1, NULL, 49, 'libre', '2025-09-20 02:37:37', '2025-09-21 21:49:35'),
(50, 1, NULL, 50, 'libre', '2025-09-20 02:37:37', '2025-09-20 02:37:37'),
(51, 2, NULL, 1, 'libre', '2025-09-20 03:14:45', '2025-09-21 22:05:17'),
(52, 2, NULL, 2, 'libre', '2025-09-20 03:14:45', '2025-09-21 22:05:17'),
(53, 2, NULL, 3, 'libre', '2025-09-20 03:14:45', '2025-09-21 22:05:17'),
(54, 2, NULL, 4, 'libre', '2025-09-20 03:14:45', '2025-09-21 21:49:35'),
(55, 2, NULL, 5, 'libre', '2025-09-20 03:14:45', '2025-09-21 21:49:35'),
(56, 2, NULL, 6, 'libre', '2025-09-20 03:14:45', '2025-09-21 22:05:17'),
(57, 2, NULL, 7, 'libre', '2025-09-20 03:14:45', '2025-09-20 03:14:45'),
(58, 2, NULL, 8, 'libre', '2025-09-20 03:14:45', '2025-09-20 03:14:45'),
(59, 2, NULL, 9, 'libre', '2025-09-20 03:14:45', '2025-09-20 03:14:45'),
(60, 2, NULL, 10, 'libre', '2025-09-20 03:14:45', '2025-09-21 21:52:56'),
(61, 2, NULL, 11, 'libre', '2025-09-20 03:14:45', '2025-09-20 03:14:45'),
(62, 2, NULL, 12, 'libre', '2025-09-20 03:14:45', '2025-09-20 03:14:45'),
(63, 2, NULL, 13, 'libre', '2025-09-20 03:14:45', '2025-09-21 22:03:49'),
(64, 2, NULL, 14, 'libre', '2025-09-20 03:14:45', '2025-09-21 22:03:49'),
(65, 2, NULL, 15, 'libre', '2025-09-20 03:14:45', '2025-09-20 03:14:45'),
(66, 2, NULL, 16, 'libre', '2025-09-20 03:14:45', '2025-09-20 03:14:45'),
(67, 2, NULL, 17, 'libre', '2025-09-20 03:14:45', '2025-09-20 03:14:45'),
(68, 2, NULL, 18, 'libre', '2025-09-20 03:14:45', '2025-09-20 03:14:45'),
(69, 2, NULL, 19, 'libre', '2025-09-20 03:14:45', '2025-09-21 22:04:32'),
(70, 2, NULL, 20, 'libre', '2025-09-20 03:14:45', '2025-09-21 22:04:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_reserva` bigint(20) UNSIGNED NOT NULL,
  `id_pago_mp` varchar(255) DEFAULT NULL,
  `estado` enum('aprobado','rechazado','pendiente') DEFAULT 'pendiente',
  `monto` decimal(10,2) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `pagado_en` datetime DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `id_rifa` bigint(20) UNSIGNED NOT NULL,
  `estado` enum('reservado','pagado','cancelado','expirado') DEFAULT 'reservado',
  `reservado_en` datetime DEFAULT NULL,
  `expira_en` datetime DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `id_usuario`, `id_rifa`, `estado`, `reservado_en`, `expira_en`, `creado_en`, `actualizado_en`) VALUES
(1, 2, 2, 'expirado', '2025-09-20 05:03:14', '2025-09-20 05:18:14', '2025-09-20 05:03:14', '2025-09-21 21:49:35'),
(2, 2, 2, 'expirado', '2025-09-20 05:10:48', '2025-09-20 05:25:48', '2025-09-20 05:10:48', '2025-09-21 21:49:35'),
(3, 2, 2, 'expirado', '2025-09-20 05:13:39', '2025-09-20 05:28:39', '2025-09-20 05:13:39', '2025-09-21 21:49:35'),
(4, 2, 1, 'expirado', '2025-09-20 05:21:10', '2025-09-20 05:36:10', '2025-09-20 05:21:10', '2025-09-21 21:49:35'),
(5, 2, 2, 'expirado', '2025-09-21 21:49:44', '2025-09-21 22:04:44', '2025-09-21 21:49:44', '2025-09-21 22:05:17'),
(6, 2, 2, 'expirado', '2025-09-21 18:52:55', '2025-09-21 19:07:56', '2025-09-21 21:52:56', '2025-09-21 21:52:56'),
(7, 2, 2, 'expirado', '2025-09-21 19:03:49', '2025-09-21 19:18:49', '2025-09-21 22:03:49', '2025-09-21 22:03:49'),
(8, 2, 2, 'expirado', '2025-09-21 19:04:09', '2025-09-21 19:19:09', '2025-09-21 22:04:09', '2025-09-21 22:04:09'),
(9, 2, 2, 'expirado', '2025-09-21 19:04:32', '2025-09-21 19:19:32', '2025-09-21 22:04:32', '2025-09-21 22:04:32'),
(10, 2, 1, 'expirado', '2025-09-21 19:04:44', '2025-09-21 19:19:44', '2025-09-21 22:04:44', '2025-09-21 22:04:44'),
(11, 2, 1, 'expirado', '2025-09-21 19:05:30', '2025-09-21 19:20:31', '2025-09-21 22:05:31', '2025-09-21 22:05:31'),
(12, 2, 1, 'expirado', '2025-09-21 19:10:32', '2025-09-21 19:15:33', '2025-09-21 22:10:33', '2025-09-21 22:10:33'),
(13, 2, 1, 'expirado', '2025-09-21 19:11:04', '2025-09-21 19:16:04', '2025-09-21 22:11:04', '2025-09-21 22:11:04'),
(14, 2, 1, 'expirado', '2025-09-21 19:11:04', '2025-09-21 19:16:05', '2025-09-21 22:11:05', '2025-09-21 22:11:05'),
(15, 2, 1, 'expirado', '2025-09-21 22:13:55', '2025-09-21 22:18:55', '2025-09-21 22:13:55', '2025-09-21 22:28:43'),
(16, 2, 1, 'expirado', '2025-09-21 22:18:01', '2025-09-21 22:23:01', '2025-09-21 22:18:01', '2025-09-21 22:28:43'),
(17, 2, 1, 'expirado', '2025-09-21 19:20:10', '2025-09-21 19:25:10', '2025-09-21 19:20:10', '2025-09-21 19:26:49'),
(18, 2, 1, 'expirado', '2025-09-21 19:27:38', '2025-09-21 19:32:38', '2025-09-21 19:27:38', '2025-09-21 19:35:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rifas`
--

CREATE TABLE `rifas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `precio_por_numero` decimal(10,2) DEFAULT NULL,
  `total_numeros` int(11) DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `estado` enum('activa','finalizada','cancelada') DEFAULT 'activa',
  `id_ganador` bigint(20) UNSIGNED DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `rifas`
--

INSERT INTO `rifas` (`id`, `titulo`, `descripcion`, `imagen`, `precio_por_numero`, `total_numeros`, `fecha_inicio`, `fecha_fin`, `estado`, `id_ganador`, `creado_en`, `actualizado_en`) VALUES
(1, 'RIFA TEST 01', 'asdasd', NULL, 50.00, 50, '2025-09-20 23:43:00', '2025-09-30 23:37:00', 'activa', NULL, '2025-09-20 02:37:36', '2025-09-20 02:37:36'),
(2, 'tele', 'asd', 'rifa_1758338085_20c86279.png', 50.00, 20, '2025-09-20 00:14:00', '2025-09-30 00:14:00', 'activa', NULL, '2025-09-20 03:14:45', '2025-09-22 06:34:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  `rol` enum('admin','operador','usuario') NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `google_id`, `avatar`, `creado_en`, `actualizado_en`, `rol`) VALUES
(1, 'Federico Bisio', 'fedebisio1995@gmail.com', '108335224574592751209', 'https://lh3.googleusercontent.com/a/ACg8ocK5c-q2XPSnJ0MmyoE0YRf_WhVlVB3fvP1Dq_mhaem27jWO-A=s96-c', '2025-09-20 01:41:57', '2025-09-20 01:56:57', 'admin'),
(2, 'Federico Nicola', 'fedebis1995@gmail.com', '114688244803927886639', 'https://lh3.googleusercontent.com/a/ACg8ocIm8G67zFbU_1VF0zDIEw8dhuKSdu6M4OiCMKOTLb_4L4rfkg=s96-c', '2025-09-20 02:21:06', '2025-09-20 03:18:48', 'usuario'),
(3, 'Alejandro Perurena', 'aleperurena1@gmail.com', '110143581016804265345', 'https://lh3.googleusercontent.com/a/ACg8ocLF4_zDpw3l-Quibc7j1XIIySlnWh5fWVi_pHWgkXsJT5qyaw=s96-c', '2025-09-22 00:57:14', '2025-09-22 00:58:19', 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `numeros_rifa`
--
ALTER TABLE `numeros_rifa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_rifa` (`id_rifa`),
  ADD KEY `id_reserva` (`id_reserva`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_reserva` (`id_reserva`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_rifa` (`id_rifa`);

--
-- Indices de la tabla `rifas`
--
ALTER TABLE `rifas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rifa_ganador` (`id_ganador`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `google_id` (`google_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `numeros_rifa`
--
ALTER TABLE `numeros_rifa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `rifas`
--
ALTER TABLE `rifas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `numeros_rifa`
--
ALTER TABLE `numeros_rifa`
  ADD CONSTRAINT `numeros_rifa_ibfk_1` FOREIGN KEY (`id_rifa`) REFERENCES `rifas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `numeros_rifa_ibfk_2` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_rifa`) REFERENCES `rifas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `rifas`
--
ALTER TABLE `rifas`
  ADD CONSTRAINT `fk_rifa_ganador` FOREIGN KEY (`id_ganador`) REFERENCES `numeros_rifa` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

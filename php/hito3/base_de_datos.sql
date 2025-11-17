DROP TABLE IF EXISTS `numeros_rifa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `numeros_rifa` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_rifa` bigint(20) unsigned NOT NULL,
  `id_reserva` bigint(20) unsigned DEFAULT NULL,
  `numero` int(11) NOT NULL,
  `estado` enum('libre','reservado','vendido') DEFAULT 'libre',
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_rifa` (`id_rifa`),
  KEY `id_reserva` (`id_reserva`),
  CONSTRAINT `numeros_rifa_ibfk_1` FOREIGN KEY (`id_rifa`) REFERENCES `rifas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `numeros_rifa_ibfk_2` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_reserva` bigint(20) unsigned NOT NULL,
  `id_pago_mp` varchar(255) DEFAULT NULL,
  `estado` enum('aprobado','rechazado','pendiente') DEFAULT 'pendiente',
  `monto` decimal(10,2) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `preference_id` varchar(100) DEFAULT NULL,
  `init_point` varchar(255) DEFAULT NULL,
  `payment_id` varchar(100) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `payment_detail` text DEFAULT NULL,
  `merchant_order_id` varchar(100) DEFAULT NULL,
  `pagado_en` datetime DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_reserva` (`id_reserva`),
  CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `reservas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` bigint(20) unsigned NOT NULL,
  `id_rifa` bigint(20) unsigned NOT NULL,
  `estado` enum('reservado','pagado','cancelado','expirado') DEFAULT 'reservado',
  `reservado_en` datetime DEFAULT NULL,
  `expira_en` datetime DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_rifa` (`id_rifa`),
  CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_rifa`) REFERENCES `rifas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=310 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `rifas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rifas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `precio_por_numero` decimal(10,2) DEFAULT NULL,
  `total_numeros` int(11) DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `estado` enum('activa','finalizada','cancelada') DEFAULT 'activa',
  `id_ganador` bigint(20) unsigned DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_rifa_ganador` (`id_ganador`),
  CONSTRAINT `fk_rifa_ganador` FOREIGN KEY (`id_ganador`) REFERENCES `numeros_rifa` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  `rol` enum('admin','operador','usuario') NOT NULL DEFAULT 'usuario',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `google_id` (`google_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-31 23:37:31

-- MySQL dump 10.13  Distrib 9.1.0, for Win64 (x86_64)
--
-- Host: localhost    Database: user_auth_system
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `dashboard_content`
--

DROP TABLE IF EXISTS `dashboard_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dashboard_content` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `allowed_roles` set('admin','user','guest') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dashboard_content`
--

LOCK TABLES `dashboard_content` WRITE;
/*!40000 ALTER TABLE `dashboard_content` DISABLE KEYS */;
INSERT INTO `dashboard_content` VALUES (1,'Admin Panel','Access to user management, system settings, and administrative tools.','admin','2025-06-24 12:20:05'),(2,'User Statistics','View your personal statistics and account information.','admin,user','2025-06-24 12:20:05'),(3,'Welcome Message','Welcome to our secure authentication system!','admin,user,guest','2025-06-24 12:20:05'),(4,'Guest Information','Limited access area for guest users.','guest','2025-06-24 12:20:05'),(5,'Advanced Features','Access to premium features and advanced tools.','admin,user','2025-06-24 12:20:05');
/*!40000 ALTER TABLE `dashboard_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','guest') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'System Administrator','admin@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin','2025-06-24 12:20:05','2025-06-24 12:20:05'),(2,'Ocampo, Marc Niño Christopher Palma','marcninoocampo@gmail.com','$2y$12$L69k2giHd3M1KrkDvBI9jOfpLEnz1XM/r4b/HgiCI1YuZLcxJYOu6','user','2025-06-24 12:20:36','2025-06-24 12:20:36'),(3,'admin','admin@auxiliare.com','$2y$12$JMmu/84KrI7urPd.PRcEmeIrgsC2RXbaaS7fImTbOGke/QoNNm1/m','user','2025-06-24 12:28:17','2025-06-24 12:28:17'),(4,'admin2','admin2@auxiliare.com','$2y$12$wROJaUwXivcjUIROgLgOlOw1ovB9PHYshF9AqC9gfD4vgqIBn.Kw.','admin','2025-06-24 12:28:54','2025-06-24 12:28:54'),(5,'Marc Niño Christopher Ocampo','info@valleyoville.com','$2y$12$Y9W8SpLRlJFQ1qlNTQptiOk23Gfmct/jHlVgYe0T0Xqbu2WHEKK/q','admin','2025-06-24 12:35:11','2025-06-24 12:35:11'),(6,'Marc Niño Christopher Ocampo','mnco25@gmail.com','$2y$12$Wq72s5wkzA9CPTRVEPEfKeouA8JfpbnhpcQfFBeiJRYTp0gLswxcG','user','2025-06-24 12:47:20','2025-06-24 12:47:20'),(7,'Justin ALeta','justin555@gmail.com','$2y$12$o8DfUzypvdCvJs66Ucl6hOrTmuEKRilCkbSiATIkZkwKY/4wwW16y','user','2025-06-24 13:16:37','2025-06-24 13:16:37');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-24 21:23:50

-- MySQL dump 10.13  Distrib 8.3.0, for macos13.6 (arm64)
--
-- Host: localhost    Database: snowtricks
-- ------------------------------------------------------
-- Server version	8.3.0

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (19,'Rotations','rotations'),(20,'Inversions','inversions'),(21,'Grabs','grabs'),(22,'Slides','slides'),(23,'Butters','butters'),(24,'Combinaisons','combinaisons');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `author_id_id` int NOT NULL,
  `trick_id_id` int NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_9474526C69CCBE9A` (`author_id_id`),
  KEY `IDX_9474526CB46B9EE8` (`trick_id_id`),
  CONSTRAINT `FK_9474526C69CCBE9A` FOREIGN KEY (`author_id_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_9474526CB46B9EE8` FOREIGN KEY (`trick_id_id`) REFERENCES `tricks` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` VALUES (25,54,47,'Superbe trick, j\'ai ador√© faire ce 360 !','2024-05-06 10:00:00'),(26,57,46,'Vraiment impressionnant, tu ma√Ætrises bien ton style !','2024-05-06 10:05:00'),(27,55,39,'Le backflip est tellement cool, je veux le ma√Ætriser aussi !','2024-05-06 10:10:00'),(28,46,41,'Bravo pour le frontflip, √ßa demande du courage !','2024-05-06 10:15:00'),(29,49,42,'Je me demande comment tu fais pour attraper la planche comme √ßa en plein vol !','2024-05-06 10:20:00'),(30,52,42,'Le method grab est l\'un de mes tricks pr√©f√©r√©s, bien jou√© !','2024-05-06 10:25:00'),(31,53,38,'Les slides sont toujours impressionnants √† regarder, continue comme √ßa !','2024-05-06 10:30:00'),(32,56,47,'J\'aimerais bien ma√Ætriser le lipslide, tu as des conseils ?','2024-05-06 10:35:00'),(34,61,41,'test','2024-10-08 11:12:22');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20241008083604','2024-10-08 08:36:21',31);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `image` (
  `id` int NOT NULL AUTO_INCREMENT,
  `trick_id` int NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_main` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C53D045FB281BE2E` (`trick_id`),
  CONSTRAINT `FK_C53D045FB281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `tricks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image`
--

LOCK TABLES `image` WRITE;
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
INSERT INTO `image` VALUES (2,47,'max-kukurudziak-8h7TrklnHQ-unsplash-Grande-670510d4ab00f.jpg',0),(3,38,'victor-rodvang-kIavtjR0sZY-unsplash-Grande-670a1c6da9ce2.jpg',0),(4,42,'victor-rodvang-9W9VwZE2gqI-unsplash-Grande-670510912e370.jpg',0),(5,45,'holly-mandarich-FidWUAMGoPg-unsplash-Grande-670510c6a89f4.jpg',0),(6,42,'robson-hatsukami-morgan-FTJHdBkDpBE-unsplash-Grande-670510912e60c.jpg',0),(7,48,'benjamin-hayward-188K4M0BmTU-unsplash-Grande-670510e0ec02f.jpg',0),(8,48,'public/assets/uploads/image7.jpg',0),(9,41,'Ollie-3-max-836x555-6704ed9cbe6c2.jpg',0),(10,37,'mattias-olsson-nQz49efZEFs-unsplash-Grande-6704fc8eb2018.jpg',0),(11,37,'ostap-senyuk-SW4BTHmSGQg-unsplash-Grande-6704fc8eb26e6.jpg',1),(12,38,'victor-rodvang-kIavtjR0sZY-unsplash-Grande-6704fca8d941c.jpg',1),(13,39,'robson-hatsukami-morgan-5C6veSN6hec-unsplash-Grande-6704fcc244734.jpg',0),(14,40,'colton-duke-nagznm30g44-unsplash-Grande-6704fcd19a280.jpg',0),(15,40,'jakob-owens-eDnJQL21amc-unsplash-Grande-6704fcd19a54d.jpg',1),(16,43,'johannes-waibel-WdBQHcIiSIw-unsplash-Grande-670510af3e60b.jpg',0),(17,44,'jakob-owens-dtZdr7TRi-Y-unsplash-Grande-670510b93e3e2.jpg',0),(18,46,'philipp-kammerer-thFqCz8cXu0-unsplash-Grande-670510f1e667c.jpg',0);
/*!40000 ALTER TABLE `image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tricks`
--

DROP TABLE IF EXISTS `tricks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tricks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id_id` int NOT NULL,
  `author_id_id` int NOT NULL,
  `main_image_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_E1D902C19777D11E` (`category_id_id`),
  KEY `IDX_E1D902C169CCBE9A` (`author_id_id`),
  KEY `IDX_E1D902C1E4873418` (`main_image_id`),
  CONSTRAINT `FK_E1D902C169CCBE9A` FOREIGN KEY (`author_id_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_E1D902C19777D11E` FOREIGN KEY (`category_id_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `FK_E1D902C1E4873418` FOREIGN KEY (`main_image_id`) REFERENCES `image` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tricks`
--

LOCK TABLES `tricks` WRITE;
/*!40000 ALTER TABLE `tricks` DISABLE KEYS */;
INSERT INTO `tricks` VALUES (37,21,61,11,'Trois Sixty','Une rotation de 360 degr√©s dans les airs.','trois-sixty','2024-05-06 10:00:00'),(38,21,61,12,'Sept Vingt','Une rotation de 720 degr√©s dans les airs.','sept-vingt','2024-05-06 10:05:00'),(39,23,61,13,'Backflip','Une rotation vers l\'arri√®re dans les airs.','backflip','2024-05-06 10:10:00'),(40,24,61,15,'Frontflip','Une rotation vers l\'avant dans les airs.','frontflip','2024-05-06 10:15:00'),(41,21,61,9,'Indy Grab','Attraper le bord de la planche avec la main arri√®re tout en √©tant dans les airs.','indy-grab','2024-05-06 10:20:00'),(42,24,61,4,'Method Grab','Attraper le bord de la planche avec la main arri√®re tout en pliant le genou avant.','method-grab','2024-05-06 10:25:00'),(43,22,61,16,'Boardslide','Glisser le long d\'une barre ou d\'une bo√Æte avec la planche de snowboard perpendiculaire √† l\'√©l√©ment.','boardslide','2024-05-06 10:30:00'),(44,23,61,17,'Lipslide','Glisser le long d\'une barre ou d\'une bo√Æte avec la planche de snowboard parall√®le √† l\'√©l√©ment.','lipslide','2024-05-06 10:35:00'),(45,20,61,5,'Tail Butter','Presser la queue du snowboard dans la neige tout en tournant.','tail-butter','2024-05-06 10:40:00'),(46,24,61,18,'Nose Butter','Presser le nez du snowboard dans la neige tout en tournant.','nose-butter','2024-05-06 10:45:00'),(47,24,61,2,'Double Cork','Deux rotations horizontales et deux rotations compl√®tes.','double-cork','2024-05-06 10:50:00'),(48,22,61,7,'Combo 540','Une rotation de 540 degr√©s combin√©e avec un autre trick.','combo-540','2024-05-06 10:55:00');
/*!40000 ALTER TABLE `tricks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `token` binary(16) DEFAULT NULL COMMENT '(DC2Type:uuid)',
  `roles` json NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (46,'jean.dupont@example.com','2024-05-06 10:00:00','$2y$13$2LfYCZlKAfE3NeuwJ22FnuwZP.Kl6tVRvuo1QCE35mGyTnTAvFvn.','Jean Dupont',0,_binary 'bk \«Bêjpfs\0\‹k','[]'),(47,'marie.lefebvre@example.com','2024-05-06 10:05:00','$2y$13$vDiD/qGsieCCz.tlPpi85ugyrA2NHfiORxpAuYVgqbkyMDjN9gXFG','Marie Lefebvre',0,_binary 'j¨≈¥\':Kˇº}@≥£\’','[]'),(48,'pierre.martin@example.com','2024-05-06 10:10:00','$2y$13$fnzMvA685/yU7NlIAF9DzONa860NFmC5Xvn3yQV8jBCYK1xAAHs0i','Pierre Martin',0,_binary 'Lx¨V\"M2´≥¢l∞ò','[]'),(49,'sophie.bernard@example.com','2024-05-06 10:15:00','$2y$13$jO/3NvvcJVjn8eBPMjcL0uSt43WKgMpEKNIb7HaujgTKit.lFnBD6','Sophie Bernard',0,_binary '™7ò\\\ﬂA#ó\Àaî3¡ñC','[]'),(50,'luc.dubois@example.com','2024-05-06 10:20:00','$2y$13$TCILzCKKlVB60ICvqC2G5.opi7l0EgeASB/4oWywUAyjZRobWubje','Luc Dubois',0,_binary '≥\n(ñg˘O	ò)\€ui°¿','[]'),(51,'julie.thomas@example.com','2024-05-06 10:25:00','$2y$13$3kokdoUy1XIbO0fDcoBei.8dzPnwjXjxAgTGlH8CUlABEBUMcFm5e','Julie Thomas',0,_binary '\·ñ0\Àn\—A\Ú≤\r˝\Ù4´','[]'),(52,'philippe.robert@example.com','2024-05-06 10:30:00','$2y$13$2aoFZ4/L2Xei1eL3utNS1Obg87s9HETnNhkKUa7J8iZ1UXscBRVQG','Philippe Robert',0,_binary ',¨]ç`eNg∂R\ÃV\›T','[]'),(53,'caroline.moreau@example.com','2024-05-06 10:35:00','$2y$13$KiLpSlR.PuEzIw1HkUobquZiESNBlSOgjPts9V4Ji1VDdZR3vjJA6','Caroline Moreau',0,_binary '\…\Êk_\ÚgK;íß\Û\ÀHN\≈','[]'),(54,'nicolas.petit@example.com','2024-05-06 10:40:00','$2y$13$oRuH6GgjOgmA7FLLZXbAxOwzYfGITKtyn9sreDzIL5DJ4/VRC/0XG','Nicolas Petit',0,_binary '\¬Tï\ƒ\◊EPÜIŸÉﬁì\ô','[]'),(55,'isabelle.durand@example.com','2024-05-06 10:45:00','$2y$13$GNYnP0BynsmI/WgY.e4QXOKAEWHCfN9RjQLGsG9Hborx1DUxivaWe','Isabelle Durand',0,_binary '°Æ<ÉΩ\’Kí\ÿ	\Ãaˇ\Ì','[]'),(56,'francois.leroy@example.com','2024-05-06 10:50:00','$2y$13$fd3ZNkxyg3/yNHV6xAGbr.VtrFnRY3/WQA5ttQAulL2uN1uKLPmf6','Francois Leroy',0,_binary '\”ân¨@‰≤ÄqŒûî*¥','[]'),(57,'christine.morel@example.com','2024-05-06 10:55:00','$2y$13$e3.LdZXSno6kG2SYEA47WuYDema7v0GVT1uEs43oxhYT3PNfKe592','Christine Morel',0,_binary 'nU. eUH˚†mIéù\„Å\È','[]'),(58,'jeanpierre.girard@example.com','2024-05-06 11:00:00','$2y$13$05He3w8lpk6aT/q7Ef63quDIy0g7Zuzc/nhN4BhhqobZsMLF9suRa','Jean-Pierre Girard',0,_binary '\ÿ\”x\…\÷\„@™”∂(U¸\≈\ˆ','[]'),(59,'nathalie.lopez@example.com','2024-05-06 11:05:00','$2y$13$CKQZMA7gOjdYXqzzy5ndFukhiEUA81IGM0rYZUvsZ5j26W85j7Bgy','Nathalie Lopez',0,_binary 'Ä†é{\r\˜Gç∂¢nwk=\€','[]'),(60,'vincent.sanchez@example.com','2024-05-06 11:10:00','$2y$13$WQ8PTuUx3.m3czaabH1IW.49q8/BfWQld6Bp/bhfc6IbNWSi64zDu','Vincent Sanchez',0,_binary '\"4ˇ¥@∂Äë†£˛\Â','[]'),(61,'julien.desaindes@gmail.com','2024-10-08 10:26:46','$2y$13$id9QQqn57ZHVTnSxLsBRRObRtJ7qNmaPQfe4tYVxZAZqm95GM5772','Ju-des',1,NULL,'[\"ROLES_USER\"]');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_profile_picture`
--

DROP TABLE IF EXISTS `user_profile_picture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_profile_picture` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D7B9FD9AA76ED395` (`user_id`),
  CONSTRAINT `FK_D7B9FD9AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_profile_picture`
--

LOCK TABLES `user_profile_picture` WRITE;
/*!40000 ALTER TABLE `user_profile_picture` DISABLE KEYS */;
INSERT INTO `user_profile_picture` VALUES (1,61,'a8c1b20748c7abc981aaf6e344b77efa.jpg');
/*!40000 ALTER TABLE `user_profile_picture` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video`
--

DROP TABLE IF EXISTS `video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `video` (
  `id` int NOT NULL AUTO_INCREMENT,
  `trick_id` int NOT NULL,
  `url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7CC7DA2CB281BE2E` (`trick_id`),
  CONSTRAINT `FK_7CC7DA2CB281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `tricks` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video`
--

LOCK TABLES `video` WRITE;
/*!40000 ALTER TABLE `video` DISABLE KEYS */;
INSERT INTO `video` VALUES (1,41,'https://www.youtube.com/watch?v=example1','example1'),(2,37,'https://www.youtube.com/watch?v=example2','example2'),(3,43,'https://www.youtube.com/watch?v=example3','example3'),(4,38,'https://www.youtube.com/watch?v=example4','example4'),(5,37,'https://www.youtube.com/watch?v=example5','example5');
/*!40000 ALTER TABLE `video` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-12  9:18:29

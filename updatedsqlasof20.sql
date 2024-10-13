-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: p2hcms
-- ------------------------------------------------------
-- Server version	8.0.37

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `adid` int NOT NULL AUTO_INCREMENT,
  `adname` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `adsurname` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `adusername` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `adpass` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `adposition` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`adid`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (14,'fdfsd','fdsfsd','fd342342','$2y$10$3JWdYq6pDJb4uC1zyn41c.XKCnTYOtvS2.cW9I70/ehcEuNXSrmWW','HEALTHWORKER'),(15,'GFDG','GFDG','fd342342','$2y$10$5ZHuRTwPwxy8wN1qMARoL.xOpQxYc7HKkj75Qh7U5VreLmiJ2d85q','HEALTHWORKER'),(16,'dsada','dsadas','fd342342','$2y$10$z6s1A2BqGHICNSD3hoS/kOw0xW0XdHlUzUjB3jXl70IR.39b/0P9e','HEALTHWORKER'),(17,'dsad','dsada','123','$2y$10$Pn5qHXMLVVjAgSMNjeaLvOiSAtgBcDUNPROQKWDS0ABWhMxanqADq','HEALTHWORKER'),(19,'ADMIN','1','admin','$2y$10$FeRpMGTPLzF9eS8IQE1e7Oyea5YGeK/uNxT812CraGPitJmxe8WTm','ADMIN'),(20,'DASDA','DSADAS','fd342342','$2y$10$VCasTIiEvv53mDe99PrBtOvYwgPc6hQ6A./nzpMYc3krhX/O08gWW','HEALTHWORKER'),(21,'dasd','dsada','fd342342','$2y$10$GhsvhtqWUY386AUejR.M..sghlzh3TU.uJz7ef8bLU1gNkZ8WfEeC','HEALTHWORKER'),(22,'sdada','dsadsa','fd342342','$2y$10$C25C56Ncb46SuUDd78M0qOpRihlZMxlGN2oZ1Nhi7QHvppKynJIgK','HEALTHWORKER'),(23,'sdada','dsadsa','fd342342dsad','$2y$10$0.un7BgB3W0UBCAELxPxTuyTP8I3KpYY6FDpkdRtPeaXlvWhGV1gm','HEALTHWORKER'),(24,'gfgd','gfdgd','fd342342','$2y$10$RIXVSGn.0hLZ3D4xuPGt9Oe7.jgkrMUYG.P4sjgtC3O4ZAcz.2okW','HEALTHWORKER'),(25,'gfgd','gfdgd','fd342342','$2y$10$fmLBb8Q1DfPIXESKYV51oeRqmoIjyGLrUaAkVR43KHxAS5Mv1apNu','HEALTHWORKER'),(26,'gfgd','gfdgd','fd342342','$2y$10$7cboaibyWUKQ.jrSxC0eb.sT.2f8lzwr62fEWfoPTWuw5nXQkPndW','HEALTHWORKER'),(27,'gfgd','gfdgd','fd342342','$2y$10$K5HQAEA/nY6pzAQMNK6pvO.P.vVUL9SRuOVW80wN5jB2hHQB/1ngm','HEALTHWORKER'),(28,'gfgd','gfdgd','fd342342','$2y$10$5Bz3ip6raCKEKa4PCAl9O.nbqbzbuYKwusxQ0zFzhlEY2tfT8FAkW','HEALTHWORKER'),(30,'sdaadasadadssadsadasdsadadad','dsadasdasdasdasdsadasdsa','dsadasdasdas','$2y$10$JfPOMXtSoFjM5ywpLx8z2.kyaFDstCvSZK3SSzuykojHDNxflSuQW','HEALTHWORKER'),(31,'admin2','12','admin231','$2y$10$tCBUo9sNAuNXndS4Pe4fLujVkX7uSQkIiWcYgD1LupYdi8TvcJszy','HEALTHWORKER'),(32,'dsadas','dsadsa','dsada','$2y$10$bJSvkCBRLCMoHmca.5Zuxe0lr2NMkIQjM/MSug7QcY4gOJ0R5a9Cm','HEALTHWORKER');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin1`
--

DROP TABLE IF EXISTS `admin1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin1` (
  `ad_id` int NOT NULL,
  `ad_fname` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ad_lname` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ad_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ad_pass` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin1`
--

LOCK TABLES `admin1` WRITE;
/*!40000 ALTER TABLE `admin1` DISABLE KEYS */;
INSERT INTO `admin1` VALUES (1,'little mermaid ','gaspar','administator@gmail.com','password'),(0,'administrator ','BHW','administrator','password');
/*!40000 ALTER TABLE `admin1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emergency_supp`
--

DROP TABLE IF EXISTS `emergency_supp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `emergency_supp` (
  `er_supId` int NOT NULL AUTO_INCREMENT,
  `prdct_name` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pstck_in` int NOT NULL,
  `pstck_out` int NOT NULL,
  `pstck_exprd` int NOT NULL,
  `pstck_avail` int NOT NULL,
  PRIMARY KEY (`er_supId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emergency_supp`
--

LOCK TABLES `emergency_supp` WRITE;
/*!40000 ALTER TABLE `emergency_supp` DISABLE KEYS */;
INSERT INTO `emergency_supp` VALUES (1,'oxygen tank ',5,2,0,3);
/*!40000 ALTER TABLE `emergency_supp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `event_name` varchar(250) DEFAULT NULL,
  `event_description` varchar(9999) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'hgfh','hgfhfgdsadsadasa','2024-09-17 20:26:00'),(2,'hgfhfgh','hgfhf','2024-09-17 06:26:00'),(3,'hgfhfgh','hgfhf','2028-07-17 21:29:00'),(5,'dsadadadaddasdsadsadadsadsadsadsadadsadsadsadasdsadsadasdasdad','dsadasdada','2024-09-20 10:49:00'),(6,'saSA','dsadasda','2024-09-20 11:28:00');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inv_meds`
--

DROP TABLE IF EXISTS `inv_meds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inv_meds` (
  `med_id` int NOT NULL AUTO_INCREMENT,
  `meds_number` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `meds_name` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `med_dscrptn` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `stock_in` int NOT NULL,
  `stock_out` int NOT NULL,
  `stock_exp` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
  `stock_avail` int NOT NULL,
  PRIMARY KEY (`med_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inv_meds`
--

LOCK TABLES `inv_meds` WRITE;
/*!40000 ALTER TABLE `inv_meds` DISABLE KEYS */;
INSERT INTO `inv_meds` VALUES (16,'dsdas','dsadas','dsadasda',4,11,'2024-09-20',-3),(17,'ASCORBIC','DSADA','DSAD',500,499,'2024-09-20',1),(18,'ASCORBIC','ASCORBIC ACID2','ascorbiccccccccccc',5,2,'2024-09-20',3);
/*!40000 ALTER TABLE `inv_meds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inv_medsup`
--

DROP TABLE IF EXISTS `inv_medsup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inv_medsup` (
  `med_supId` int NOT NULL AUTO_INCREMENT,
  `prod_name` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `stck_in` int NOT NULL,
  `stck_out` int NOT NULL,
  `stck_expired` varchar(225) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `stck_avl` int NOT NULL,
  PRIMARY KEY (`med_supId`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inv_medsup`
--

LOCK TABLES `inv_medsup` WRITE;
/*!40000 ALTER TABLE `inv_medsup` DISABLE KEYS */;
INSERT INTO `inv_medsup` VALUES (7,'dsadada',3,3,'3',3),(10,'dsadada',3,3,'3',3),(12,'dsadada',3,3,'3',3),(13,'dsadada',3,3,'3',3),(14,'fdsfdfdsfs',54324,4324,'4324',423),(15,'fdsfdfdsfs',54324,4324,'4324',423),(16,'fdsfdfdsfs',54324,4324,'4324',423),(18,'dsad',2,2,'22',2);
/*!40000 ALTER TABLE `inv_medsup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p_appointment`
--

DROP TABLE IF EXISTS `p_appointment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `p_appointment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `p_name` varchar(225) NOT NULL,
  `p_purpose` varchar(45) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `a_healthworker` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p_appointment`
--

LOCK TABLES `p_appointment` WRITE;
/*!40000 ALTER TABLE `p_appointment` DISABLE KEYS */;
INSERT INTO `p_appointment` VALUES (3,'dsadsada','Follow-Up','2024-09-20 00:28:00','fdfsd fdsfsd'),(4,'dsadsada','Emergency','2024-09-20 00:32:00','fdfsd fdsfsd'),(5,'dsadsada','Follow-Up','2024-09-20 00:32:00','fdfsd fdsfsd'),(6,'dsadsada','Emergency','2024-09-20 15:36:00','fdfsd fdsfsd'),(7,'dsadsada','Follow-Up','2024-09-20 03:39:00','fdfsd fdsfsd'),(8,'dsadsada','Emergency','2024-09-20 18:42:00','fdfsd fdsfsd'),(9,'dsadsadadsadadasdassasadasasdasdsaasdsadadasdasdasdasasdasdas','Emergency','2024-09-20 00:52:00','fdfsd fdsfsd'),(10,'dsadsadadsadadasdassasadasasdasdsaasdsadadasdasdasdasasdasdas','Consultation','2024-09-20 11:56:00','fdfsd fdsfsd'),(11,'dsadasd','Follow-Up','2024-09-20 11:58:00','fdfsd fdsfsd'),(12,'dsadasd','Follow-Up','2024-09-20 12:00:00','fdfsd fdsfsd'),(13,'dsadasd','Emergency','2024-09-20 12:02:00','fdfsd fdsfsd'),(14,'dsadasd','Emergency','2024-09-20 12:02:00','fdfsd fdsfsd'),(15,'dsadasd','Follow-Up','2024-09-20 12:09:00','fdfsd fdsfsd');
/*!40000 ALTER TABLE `p_appointment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p_medication`
--

DROP TABLE IF EXISTS `p_medication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `p_medication` (
  `id` int NOT NULL AUTO_INCREMENT,
  `p_medpatient` varchar(45) DEFAULT NULL,
  `p_medication` varchar(250) DEFAULT NULL,
  `datetime` varchar(45) DEFAULT NULL,
  `a_healthworker` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p_medication`
--

LOCK TABLES `p_medication` WRITE;
/*!40000 ALTER TABLE `p_medication` DISABLE KEYS */;
INSERT INTO `p_medication` VALUES (41,'dMAEADA','[{\"name\":\"DSADA\",\"amount\":\"1\"}]','2024-09-20T14:18','fdfsd fdsfsd');
/*!40000 ALTER TABLE `p_medication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient`
--

DROP TABLE IF EXISTS `patient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patient` (
  `p_id` int NOT NULL AUTO_INCREMENT,
  `p_name` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `p_age` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `p_bday` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `p_address` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `p_contnum` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
  `p_contper` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `p_contnumper` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `p_type` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`p_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient`
--

LOCK TABLES `patient` WRITE;
/*!40000 ALTER TABLE `patient` DISABLE KEYS */;
INSERT INTO `patient` VALUES (20,'dMAEADA','3','2024-09-20','das','dasda','da','das','Pedia'),(21,'dsaa','54','2024-09-20','543gfdgf','gfdg','gfdfg','gfdf','Pedia'),(22,'dsadsaa','32','2024-10-11','dsadad','dasdas','dsa','dsad','Pedia');
/*!40000 ALTER TABLE `patient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `iduser` int NOT NULL AUTO_INCREMENT,
  `adname` varchar(255) NOT NULL,
  `adsurname` varchar(255) NOT NULL,
  `adusername` varchar(255) NOT NULL,
  `adpass` varchar(45) NOT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-09-20 14:19:26

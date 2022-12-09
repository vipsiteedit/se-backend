-- MySQL dump 10.13  Distrib 5.6.35, for Linux (x86_64)
--
-- Host: localhost    Database: ingserviss_elspe
-- ------------------------------------------------------
-- Server version	5.7.21-20-beget-5.7.21-20-1-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bank_accounts`
--

DROP TABLE IF EXISTS `bank_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` int(10) unsigned DEFAULT NULL,
  `id_payment` int(10) unsigned DEFAULT NULL,
  `codename` varchar(20) DEFAULT NULL,
  `title` varchar(125) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `account` (`account`),
  KEY `id_payment` (`id_payment`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_accounts`
--

LOCK TABLES `bank_accounts` WRITE;
/*!40000 ALTER TABLE `bank_accounts` DISABLE KEYS */;
INSERT INTO `bank_accounts` VALUES (1,1,1,'inn','ИНН','','2018-12-19 10:37:35','2012-12-20 09:15:00'),(2,1,1,'kpp','КПП','','2018-12-19 10:37:40','2012-12-20 09:15:10'),(3,1,1,'rs','Расчетный счет','','2018-12-19 10:37:46','2012-12-20 09:15:49'),(4,1,1,'ks','Кор.счет','','2018-12-19 10:37:50','2012-12-20 09:16:28'),(5,1,1,'bik','БИК','','2018-12-19 10:37:53','2012-12-20 09:16:41'),(6,1,1,'bank','БАНК','','2018-12-19 10:38:01','2012-12-20 09:17:27'),(7,1,1,'stamp','URL Штампа',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(8,1,4,'wm_r','WMR',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(9,1,4,'wm_z','WMZ',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(10,1,4,'wm_e','WME',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(11,1,3,'yan_m','Яндекс-деньги',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(12,1,5,'rbk_m','RBK-money',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(13,1,2,'z_pay','Z-paymoney',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `bank_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reg_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `inn` varchar(50) DEFAULT NULL,
  `kpp` varchar(32) DEFAULT NULL COMMENT 'код постановки на учет в налоговом органе',
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company`
--

LOCK TABLES `company` WRITE;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
/*!40000 ALTER TABLE `company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_person`
--

DROP TABLE IF EXISTS `company_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_person` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_company` int(10) unsigned DEFAULT NULL,
  `id_person` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_company` (`id_company`),
  KEY `id_person` (`id_person`),
  CONSTRAINT `company_person_ibfk_1` FOREIGN KEY (`id_company`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `company_person_ibfk_2` FOREIGN KEY (`id_person`) REFERENCES `se_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=4096;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_person`
--

LOCK TABLES `company_person` WRITE;
/*!40000 ALTER TABLE `company_person` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_userfields`
--

DROP TABLE IF EXISTS `company_userfields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_userfields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_company` int(10) unsigned NOT NULL,
  `id_userfield` int(10) unsigned NOT NULL,
  `value` text,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_company` (`id_company`),
  KEY `id_userfield` (`id_userfield`),
  CONSTRAINT `company_userfields_ibfk_1` FOREIGN KEY (`id_company`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `company_userfields_ibfk_2` FOREIGN KEY (`id_userfield`) REFERENCES `shop_userfields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_userfields`
--

LOCK TABLES `company_userfields` WRITE;
/*!40000 ALTER TABLE `company_userfields` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_userfields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `curr` varchar(255) NOT NULL,
  `course` float DEFAULT NULL,
  PRIMARY KEY (`curr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES ('RUB',1);
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_providers`
--

DROP TABLE IF EXISTS `email_providers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_providers` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT 'Наименование шлюза',
  `url` varchar(255) DEFAULT NULL,
  `settings` varchar(255) NOT NULL COMMENT 'настройки email сервиса (JSON формат)',
  `is_active` tinyint(1) DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_providers`
--

LOCK TABLES `email_providers` WRITE;
/*!40000 ALTER TABLE `email_providers` DISABLE KEYS */;
INSERT INTO `email_providers` VALUES (1,'sendpulse','https://login.sendpulse.com','{\"ID\":{\"type\":\"string\",\"value\":\"\"}, \"SECRET\":{\"type\":\"string\",\"value\":\"\"}}',1,'0000-00-00 00:00:00','2016-09-27 10:33:00');
/*!40000 ALTER TABLE `email_providers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `import_profile`
--

DROP TABLE IF EXISTS `import_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `import_profile` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `target` varchar(255) DEFAULT 'product',
  `settings` text COMMENT 'Настройки в json формате',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `import_profile`
--

LOCK TABLES `import_profile` WRITE;
/*!40000 ALTER TABLE `import_profile` DISABLE KEYS */;
INSERT INTO `import_profile` VALUES (1,'Профиль 1','product','{\"delimiter\":\"\\u0410\\u0432\\u0442\\u043e\\u043e\\u043f\\u0440\\u0435\\u0434\\u0435\\u043b\\u0435\\u043d\\u0438\\u0435\",\"separator\":\",\",\"encoding\":\"UTF-8\",\"key\":\"\\u041d\\u0430\\u0438\\u043c\\u0435\\u043d\\u043e\\u0432\\u0430\\u043d\\u0438\\u0435\",\"enclosure\":\"\\\"\",\"skip\":\"1\",\"cols\":[\"\\u0418\\u0434.\",\"\\u0410\\u0440\\u0442\\u0438\\u043a\\u0443\\u043b\",\"\\u041a\\u043e\\u0434 (URL)\",\"\",\"\\u041a\\u043e\\u0434 \\u043a\\u0430\\u0442\\u0435\\u0433\\u043e\\u0440\\u0438\\u0438\",\"\\u041f\\u0443\\u0442\\u044c \\u043a\\u0430\\u0442\\u0435\\u0433\\u043e\\u0440\\u0438\\u0438\",\"\\u041d\\u0430\\u0438\\u043c\\u0435\\u043d\\u043e\\u0432\\u0430\\u043d\\u0438\\u0435\",\"\\u0426\\u0435\\u043d\\u0430 \\u043f\\u0440.\",\"\\u0426\\u0435\\u043d\\u0430 \\u043e\\u043f\\u0442.\",\"\\u0426\\u0435\\u043d\\u0430 \\u043a\\u043e\\u0440\\u043f.\",\"\\u0426\\u0435\\u043d\\u0430 \\u0437\\u0430\\u043a\\u0443\\u043f.\",\"\\u041e\\u0441\\u0442\\u0430\\u0442\\u043e\\u043a\",\"\\u0411\\u0440\\u0435\\u043d\\u0434\",\"\\u0412\\u0435\\u0441\",\"\\u041e\\u0431\\u044a\\u0435\\u043c\",\"\\u0415\\u0434.\\u0418\\u0437\\u043c\",\"\\u041a\\u0440\\u0430\\u0442\\u043a\\u043e\\u0435 \\u043e\\u043f\\u0438\\u0441\\u0430\\u043d\\u0438\\u0435\",\"\\u041f\\u043e\\u043b\\u043d\\u043e\\u0435 \\u043e\\u043f\\u0438\\u0441\\u0430\\u043d\\u0438\\u0435\",\"\\u041a\\u043e\\u0434 \\u0432\\u0430\\u043b\\u044e\\u0442\\u044b\",\"\\u0422\\u0435\\u0433 title\",\"\\u041c\\u0435\\u0442\\u0430-\\u0442\\u0435\\u0433 keywords\",\"\\u041c\\u0435\\u0442\\u0430-\\u0442\\u0435\\u0433 description\",\"\\u0422\\u0438\\u043f \\u0442\\u043e\\u0432\\u0430\\u0440\\u0430\",\"\\u0424\\u043e\\u0442\\u043e 1\",\"\\u0424\\u043e\\u0442\\u043e 2\",\"\\u0424\\u043e\\u0442\\u043e 3\",\"\",\"\",\"\",\"\",\"\",\"\"]}','2018-12-19 08:55:43','2018-08-27 13:05:36');
/*!40000 ALTER TABLE `import_profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `integration`
--

DROP TABLE IF EXISTS `integration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `integration` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Наименование сервиса',
  `url_oauth` varchar(255) DEFAULT NULL,
  `url_api` varchar(255) DEFAULT NULL,
  `note` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `integration`
--

LOCK TABLES `integration` WRITE;
/*!40000 ALTER TABLE `integration` DISABLE KEYS */;
INSERT INTO `integration` VALUES (1,'Яндекс.Фото','http://upload.beget.edgestile.net/api/integrations/YandexPhotos/auth.php','http://api-fotki.yandex.ru',NULL,1,'0000-00-00 00:00:00','2016-02-09 10:55:48');
/*!40000 ALTER TABLE `integration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `integration_oauth`
--

DROP TABLE IF EXISTS `integration_oauth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `integration_oauth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_integration` int(10) unsigned NOT NULL COMMENT 'Ид. интеграции',
  `token` varchar(255) DEFAULT NULL,
  `expired` datetime DEFAULT NULL,
  `login` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_integration` (`id_integration`),
  CONSTRAINT `integration_oauth_ibfk_1` FOREIGN KEY (`id_integration`) REFERENCES `integration` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Настройки для oauth';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `integration_oauth`
--

LOCK TABLES `integration_oauth` WRITE;
/*!40000 ALTER TABLE `integration_oauth` DISABLE KEYS */;
/*!40000 ALTER TABLE `integration_oauth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `main`
--

DROP TABLE IF EXISTS `main`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `main` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang` char(3) DEFAULT 'rus',
  `shopname` varchar(255) DEFAULT NULL,
  `subname` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `company` varchar(250) DEFAULT NULL,
  `director` varchar(125) DEFAULT NULL,
  `posthead` varchar(125) DEFAULT NULL,
  `bookkeeper` varchar(125) DEFAULT NULL,
  `addr_f` text,
  `addr_u` text,
  `phone` varchar(125) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `esupport` varchar(250) DEFAULT NULL,
  `esales` varchar(250) DEFAULT NULL,
  `nds` double(10,2) DEFAULT NULL,
  `basecurr` char(3) DEFAULT 'RUB',
  `domain` varchar(255) DEFAULT NULL,
  `is_store` tinyint(1) NOT NULL DEFAULT '0',
  `is_pickup` tinyint(1) NOT NULL DEFAULT '0',
  `is_delivery` tinyint(1) NOT NULL DEFAULT '1',
  `local_delivery_cost` double NOT NULL DEFAULT '0',
  `city_from_delivery` varchar(127) DEFAULT NULL,
  `is_manual_curr_rate` tinyint(1) DEFAULT NULL,
  `time_modified` int(11) unsigned DEFAULT NULL,
  `folder` varchar(255) DEFAULT NULL,
  `sms_phone` varchar(255) DEFAULT NULL COMMENT 'Телефон для СМС информирование',
  `sms_sender` varchar(255) DEFAULT NULL COMMENT 'Отправитель SMS по умолчанию',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lang` (`lang`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `main`
--

LOCK TABLES `main` WRITE;
/*!40000 ALTER TABLE `main` DISABLE KEYS */;
INSERT INTO `main` VALUES (1,'rus','','','','','','Генеральный директор','','','','','','','',18.00,'RUB','',0,0,1,0,'',0,0,'','','','2019-01-27 08:21:11','2012-12-20 09:33:09');
/*!40000 ALTER TABLE `main` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `main_log`
--

DROP TABLE IF EXISTS `main_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `main_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `client_name` varchar(125) DEFAULT NULL,
  `manager_name` varchar(125) DEFAULT NULL,
  `comment` varchar(125) DEFAULT NULL,
  `log_type` char(1) DEFAULT 'O',
  `id_author` int(10) unsigned DEFAULT NULL,
  `datepayee` datetime DEFAULT NULL,
  `id_order` int(10) unsigned NOT NULL,
  `summa` double(10,4) DEFAULT NULL,
  `bonus` double(10,2) DEFAULT NULL,
  `type_payee` int(11) NOT NULL,
  `admin` varchar(40) NOT NULL,
  `ip` varchar(16) DEFAULT NULL,
  `status` char(1) DEFAULT 'Y',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_order` (`id_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `main_log`
--

LOCK TABLES `main_log` WRITE;
/*!40000 ALTER TABLE `main_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `main_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `money`
--

DROP TABLE IF EXISTS `money`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `money` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `money_title_id` int(10) unsigned NOT NULL,
  `name` char(3) NOT NULL,
  `date_replace` date NOT NULL,
  `kurs` double(20,6) NOT NULL,
  `base_currency` char(3) NOT NULL DEFAULT 'RUB',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `money_title_id` (`money_title_id`),
  CONSTRAINT `money_ibfk_1` FOREIGN KEY (`money_title_id`) REFERENCES `money_title` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `money`
--

LOCK TABLES `money` WRITE;
/*!40000 ALTER TABLE `money` DISABLE KEYS */;
INSERT INTO `money` VALUES (1,1,'RUB','0000-00-00',1.000000,'RUB','2016-06-06 07:03:32','0000-00-00 00:00:00'),(2,2,'USD','0000-00-00',1.000000,'RUB','0000-00-00 00:00:00','2014-12-18 12:42:12'),(3,2,'USD','2014-12-18',73.000000,'RUB','2014-12-18 13:08:55','2014-12-18 12:42:25'),(4,2,'USD','2014-12-19',63.370000,'RUB','0000-00-00 00:00:00','2014-12-19 08:24:12'),(5,2,'USD','2014-12-22',59.800000,'RUB','2014-12-22 13:48:20','2014-12-22 06:17:35'),(6,2,'USD','2014-12-23',56.170000,'RUB','0000-00-00 00:00:00','2014-12-23 06:17:21'),(7,2,'USD','2015-01-08',51.000000,'RUB','2015-01-08 14:27:38','2015-01-08 14:25:25'),(8,2,'USD','2015-01-12',56.000000,'RUB','0000-00-00 00:00:00','2015-01-12 06:02:23'),(9,2,'USD','2015-01-14',52.000000,'RUB','2015-01-14 14:58:22','2015-01-14 14:57:29'),(10,2,'USD','2015-01-15',56.000000,'RUB','2015-01-15 06:09:36','2015-01-15 06:08:52'),(11,2,'USD','2015-01-16',60.000000,'RUB','2015-01-16 06:06:38','2015-01-16 06:06:01');
/*!40000 ALTER TABLE `money` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `money_title`
--

DROP TABLE IF EXISTS `money_title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `money_title` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang` char(3) NOT NULL DEFAULT 'rus',
  `name` char(3) NOT NULL,
  `title` varchar(50) NOT NULL,
  `name_front` varchar(10) DEFAULT NULL,
  `name_flang` varchar(10) DEFAULT NULL,
  `cbr_kod` varchar(20) DEFAULT NULL,
  `minsum` double(10,2) NOT NULL DEFAULT '0.01',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `money_title`
--

LOCK TABLES `money_title` WRITE;
/*!40000 ALTER TABLE `money_title` DISABLE KEYS */;
INSERT INTO `money_title` VALUES (1,'rus','RUB','Российский рубль',NULL,'р.',NULL,0.01,'2016-06-06 07:04:07','2014-11-06 12:26:25'),(2,'rus','USD','Доллар США','$',NULL,'R01235',0.01,'0000-00-00 00:00:00','2014-12-18 12:42:12');
/*!40000 ALTER TABLE `money_title` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_category` int(10) unsigned DEFAULT NULL,
  `news_date` int(11) NOT NULL,
  `pub_date` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `short_txt` text,
  `text` text,
  `img` varchar(255) DEFAULT NULL,
  `active` enum('Y','N') DEFAULT 'Y',
  `send_letter` enum('Y','N') NOT NULL DEFAULT 'N',
  `url` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  `is_date_public` tinyint(1) DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `is_date_public` (`is_date_public`),
  KEY `sort` (`sort`),
  KEY `id_category` (`id_category`),
  CONSTRAINT `news_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `news_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_category`
--

DROP TABLE IF EXISTS `news_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `ident` varchar(40) DEFAULT NULL,
  `title` varchar(125) DEFAULT NULL,
  `lang` char(3) DEFAULT 'rus',
  `sort` int(11) DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `news_category_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `news_category` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_category`
--

LOCK TABLES `news_category` WRITE;
/*!40000 ALTER TABLE `news_category` DISABLE KEYS */;
INSERT INTO `news_category` VALUES (1,NULL,'news','Новости','rus',1,'0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `news_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_gcontacts`
--

DROP TABLE IF EXISTS `news_gcontacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_gcontacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_news` int(10) unsigned NOT NULL,
  `id_gcontact` int(10) unsigned NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_news` (`id_news`),
  KEY `id_gcontact` (`id_gcontact`),
  CONSTRAINT `news_gcontacts_ibfk_1` FOREIGN KEY (`id_gcontact`) REFERENCES `shop_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `news_gcontacts_ibfk_2` FOREIGN KEY (`id_news`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_gcontacts`
--

LOCK TABLES `news_gcontacts` WRITE;
/*!40000 ALTER TABLE `news_gcontacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `news_gcontacts` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `news_userfields`
--
DROP TABLE IF EXISTS `news_userfields`;

CREATE TABLE `news_userfields` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_news` int(10) UNSIGNED NOT NULL,
  `id_userfield` int(10) UNSIGNED NOT NULL,
  `value` text,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_person_userfields_se_user_id` (`id_news`),
  KEY `FK_person_userfields_shop_userfields_id` (`id_userfield`),
  CONSTRAINT `news_userfields_ibfk_1` FOREIGN KEY (`id_news`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `news_userfields_ibfk_2` FOREIGN KEY (`id_userfield`) REFERENCES `shop_userfields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `news_img`
--

DROP TABLE IF EXISTS `news_img`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_img` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_news` int(10) unsigned NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `picture_alt` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_news` (`id_news`),
  KEY `sort` (`sort`),
  CONSTRAINT `news_img_ibfk_1` FOREIGN KEY (`id_news`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_img`
--

LOCK TABLES `news_img` WRITE;
/*!40000 ALTER TABLE `news_img` DISABLE KEYS */;
/*!40000 ALTER TABLE `news_img` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_subscriber_se_group`
--

DROP TABLE IF EXISTS `news_subscriber_se_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_subscriber_se_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_news` int(10) unsigned DEFAULT NULL,
  `id_group` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_news` (`id_news`),
  KEY `id_group` (`id_group`),
  CONSTRAINT `news_subscriber_se_group_ibfk_1` FOREIGN KEY (`id_news`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_subscriber_se_group`
--

LOCK TABLES `news_subscriber_se_group` WRITE;
/*!40000 ALTER TABLE `news_subscriber_se_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `news_subscriber_se_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_object`
--

DROP TABLE IF EXISTS `permission_object`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_object` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_object`
--

LOCK TABLES `permission_object` WRITE;
/*!40000 ALTER TABLE `permission_object` DISABLE KEYS */;
INSERT INTO `permission_object` VALUES (1,'contacts','Контакты','0000-00-00 00:00:00','2016-04-13 10:29:27'),(2,'orders','Заказы','0000-00-00 00:00:00','2016-04-13 10:29:27'),(3,'products','Товары','0000-00-00 00:00:00','2016-04-13 10:29:27'),(4,'comments','Комментарии','0000-00-00 00:00:00','2016-04-13 10:29:27'),(5,'reviews','Отзывы','0000-00-00 00:00:00','2016-04-13 10:29:27'),(6,'news','Новости','0000-00-00 00:00:00','2016-04-13 10:29:27'),(7,'images','Картинки','0000-00-00 00:00:00','2016-04-13 10:29:27'),(8,'deliveries','Доставки','0000-00-00 00:00:00','2016-04-13 10:29:27'),(9,'paysystems','Платежные системы','0000-00-00 00:00:00','2016-04-13 10:29:27'),(10,'mails','Шаблоны писем','0000-00-00 00:00:00','2016-04-13 10:29:27'),(11,'settings','Настройки магазина','0000-00-00 00:00:00','2016-04-13 10:29:27'),(15,'currencies','Настройки валют','0000-00-00 00:00:00','2016-04-13 10:29:27'),(18,'payments','Платежи','0000-00-00 00:00:00','2016-04-13 10:29:27');
/*!40000 ALTER TABLE `permission_object` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_object_role`
--

DROP TABLE IF EXISTS `permission_object_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_object_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_object` int(10) unsigned NOT NULL,
  `id_role` int(10) unsigned NOT NULL,
  `mask` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT 'Маска прав (4 бита)',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_object` (`id_object`),
  KEY `id_role` (`id_role`),
  CONSTRAINT `permission_object_role_ibfk_1` FOREIGN KEY (`id_object`) REFERENCES `permission_object` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permission_object_role_ibfk_2` FOREIGN KEY (`id_role`) REFERENCES `permission_role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_object_role`
--

LOCK TABLES `permission_object_role` WRITE;
/*!40000 ALTER TABLE `permission_object_role` DISABLE KEYS */;
INSERT INTO `permission_object_role` VALUES (1,1,1,8,'0000-00-00 00:00:00','2016-04-13 10:29:27'),(2,2,1,8,'0000-00-00 00:00:00','2016-04-13 10:29:27'),(3,15,1,8,'0000-00-00 00:00:00','2016-04-13 10:29:27'),(4,11,1,8,'0000-00-00 00:00:00','2016-04-13 10:29:27'),(5,6,1,8,'0000-00-00 00:00:00','2016-04-13 10:29:27'),(6,10,1,8,'0000-00-00 00:00:00','2016-04-13 10:29:27'),(7,4,1,8,'0000-00-00 00:00:00','2016-04-13 10:29:27'),(8,3,1,8,'0000-00-00 00:00:00','2016-04-13 10:29:27'),(9,7,1,8,'0000-00-00 00:00:00','2016-04-13 10:29:27'),(10,9,1,8,'0000-00-00 00:00:00','2016-04-13 10:29:27'),(11,5,1,8,'0000-00-00 00:00:00','2016-04-13 10:29:27'),(12,8,1,8,'0000-00-00 00:00:00','2016-04-13 10:29:27'),(13,18,1,8,'0000-00-00 00:00:00','2016-04-13 10:29:27'),(29,8,3,15,'0000-00-00 00:00:00','2019-02-15 10:23:33'),(30,2,3,15,'0000-00-00 00:00:00','2019-02-15 10:23:33'),(31,7,3,15,'0000-00-00 00:00:00','2019-02-15 10:23:33'),(32,4,3,15,'0000-00-00 00:00:00','2019-02-15 10:23:33'),(33,1,3,15,'0000-00-00 00:00:00','2019-02-15 10:23:34'),(34,15,3,15,'0000-00-00 00:00:00','2019-02-15 10:23:34'),(35,11,3,15,'0000-00-00 00:00:00','2019-02-15 10:23:34'),(36,6,3,15,'0000-00-00 00:00:00','2019-02-15 10:23:34'),(37,5,3,15,'0000-00-00 00:00:00','2019-02-15 10:23:34'),(38,18,3,15,'0000-00-00 00:00:00','2019-02-15 10:23:34'),(39,9,3,15,'0000-00-00 00:00:00','2019-02-15 10:23:34'),(40,3,3,15,'0000-00-00 00:00:00','2019-02-15 10:23:34'),(41,10,3,15,'0000-00-00 00:00:00','2019-02-15 10:23:34');
/*!40000 ALTER TABLE `permission_object_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Роли пользователей';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_role`
--

LOCK TABLES `permission_role` WRITE;
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
INSERT INTO `permission_role` VALUES (1,'Менеджер',NULL,'0000-00-00 00:00:00','2016-04-13 10:29:27'),(3,'Администратор',NULL,'0000-00-00 00:00:00','2019-02-15 10:23:18');
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_role_user`
--

DROP TABLE IF EXISTS `permission_role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_role_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_role` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_role` (`id_role`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `permission_role_user_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `permission_role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permission_role_user_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `se_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_role_user`
--

LOCK TABLES `permission_role_user` WRITE;
/*!40000 ALTER TABLE `permission_role_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `permission_role_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person` (
  `id` int(10) unsigned NOT NULL,
  `id_up` int(10) unsigned DEFAULT NULL,
  `manager_id` int(10) unsigned DEFAULT NULL,
  `last_name` varchar(40) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `sec_name` varchar(40) DEFAULT NULL,
  `sex` enum('M','F','N') NOT NULL DEFAULT 'N',
  `birth_date` date DEFAULT NULL,
  `nick` varchar(25) DEFAULT NULL,
  `doc_ser` varchar(10) DEFAULT NULL,
  `doc_num` varchar(20) DEFAULT NULL,
  `doc_registr` varchar(255) DEFAULT NULL,
  `email` varchar(125) DEFAULT NULL,
  `post_index` varchar(20) DEFAULT NULL,
  `country_id` int(10) unsigned DEFAULT NULL,
  `state_id` int(10) unsigned DEFAULT NULL,
  `town_id` int(10) unsigned DEFAULT NULL,
  `overcity` varchar(255) DEFAULT NULL,
  `addr` varchar(255) DEFAULT NULL,
  `phone` varchar(125) DEFAULT NULL,
  `icq` varchar(20) DEFAULT NULL,
  `skype` varchar(127) DEFAULT NULL,
  `discount` float(5,2) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `reg_info` varchar(255) DEFAULT NULL,
  `note` mediumtext,
  `loyalty` smallint(6) DEFAULT '5',
  `subscriber_news` enum('Y','N') NOT NULL DEFAULT 'N',
  `avatar` varchar(255) DEFAULT NULL,
  `enable` enum('Y','N') NOT NULL DEFAULT 'N',
  `email_valid` enum('Y','N','C') DEFAULT 'C',
  `referer` text,
  `price_type` smallint(6) unsigned DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id` (`id`),
  KEY `id_up` (`id_up`),
  KEY `manager_id` (`manager_id`),
  CONSTRAINT `person_ibfk_1` FOREIGN KEY (`id`) REFERENCES `se_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `person_ibfk_2` FOREIGN KEY (`id_up`) REFERENCES `se_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Тип цены: 0 - розничная, 1 - мелкооптовая, 2 - оптовая';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `person`
--

LOCK TABLES `person` WRITE;
/*!40000 ALTER TABLE `person` DISABLE KEYS */;
INSERT INTO `person` VALUES (1,NULL,NULL,NULL,'Администратор',NULL,'N','1970-01-01',NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2019-02-15 00:00:00',NULL,NULL,5,'N',NULL,'N','C',NULL,0,'2019-02-15 10:30:13','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_userfields`
--

DROP TABLE IF EXISTS `person_userfields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_userfields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_person` int(10) unsigned NOT NULL,
  `id_userfield` int(10) unsigned NOT NULL,
  `value` text,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_person` (`id_person`),
  KEY `id_userfield` (`id_userfield`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `person_userfields`
--

LOCK TABLES `person_userfields` WRITE;
/*!40000 ALTER TABLE `person_userfields` DISABLE KEYS */;
/*!40000 ALTER TABLE `person_userfields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `se_account_operation`
--

DROP TABLE IF EXISTS `se_account_operation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `se_account_operation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `operation_id` int(10) unsigned NOT NULL,
  `lang` char(3) NOT NULL,
  `name` varchar(40) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `operation_id` (`operation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `se_account_operation`
--

LOCK TABLES `se_account_operation` WRITE;
/*!40000 ALTER TABLE `se_account_operation` DISABLE KEYS */;
INSERT INTO `se_account_operation` VALUES (1,0,'rus','Баланс','0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,1,'rus','Приход','0000-00-00 00:00:00','0000-00-00 00:00:00'),(3,2,'rus','Расход','0000-00-00 00:00:00','0000-00-00 00:00:00'),(4,3,'rus','Премия','0000-00-00 00:00:00','0000-00-00 00:00:00'),(5,4,'rus','Комиссионные','0000-00-00 00:00:00','0000-00-00 00:00:00'),(6,5,'rus','Возврат средств','0000-00-00 00:00:00','0000-00-00 00:00:00'),(7,6,'rus','Списание','0000-00-00 00:00:00','0000-00-00 00:00:00'),(8,7,'rus','Перевод','0000-00-00 00:00:00','0000-00-00 00:00:00'),(9,8,'rus','Перенос','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `se_account_operation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `se_group`
--

DROP TABLE IF EXISTS `se_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `se_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `level` tinyint(4) DEFAULT '1',
  `name` varchar(40) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `id_parent` int(10) unsigned DEFAULT NULL,
  `email_settings` varchar(255) DEFAULT NULL COMMENT 'Настройки для email рассылок',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=3276;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `se_group`
--

LOCK TABLES `se_group` WRITE;
/*!40000 ALTER TABLE `se_group` DISABLE KEYS */;
INSERT INTO `se_group` VALUES (9,1,NULL,'User',NULL,NULL,'0000-00-00 00:00:00','2017-04-11 08:57:17'),(10,2,NULL,'Super User',NULL,NULL,'2017-04-11 09:04:48','0000-00-00 00:00:00'),(11,3,NULL,'Admin',NULL,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(12,1,' ',NULL,NULL,NULL,'0000-00-00 00:00:00','2017-04-27 10:18:41');
/*!40000 ALTER TABLE `se_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `se_loginza`
--

DROP TABLE IF EXISTS `se_loginza`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `se_loginza` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` varchar(50) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `identity` varchar(255) NOT NULL,
  `provider` varchar(255) NOT NULL,
  `email` varchar(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `real_user_id` int(10) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`),
  KEY `user_id` (`user_id`),
  KEY `real_user_id` (`real_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `se_loginza`
--

LOCK TABLES `se_loginza` WRITE;
/*!40000 ALTER TABLE `se_loginza` DISABLE KEYS */;
/*!40000 ALTER TABLE `se_loginza` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `se_search`
--

DROP TABLE IF EXISTS `se_search`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `se_search` (
  `project` varchar(40) NOT NULL,
  `page` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `size` bigint(20) NOT NULL,
  `filetime` int(14) NOT NULL,
  `title` varchar(255) NOT NULL,
  `titlepage` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `searchtext` mediumtext NOT NULL,
  `modules` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`project`),
  KEY `page` (`page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `se_search`
--

LOCK TABLES `se_search` WRITE;
/*!40000 ALTER TABLE `se_search` DISABLE KEYS */;
/*!40000 ALTER TABLE `se_search` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `se_settings`
--

DROP TABLE IF EXISTS `se_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `se_settings` (
  `version` varchar(10) NOT NULL,
  `db_version` mediumint(9) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `se_settings`
--

LOCK TABLES `se_settings` WRITE;
/*!40000 ALTER TABLE `se_settings` DISABLE KEYS */;
INSERT INTO `se_settings` VALUES ('86',127);
/*!40000 ALTER TABLE `se_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `se_user`
--

DROP TABLE IF EXISTS `se_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `se_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(125) DEFAULT NULL,
  `password` varchar(40) DEFAULT NULL,
  `tmppassw` varchar(40) DEFAULT NULL,
  `is_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  `is_super_admin` enum('Y','N') NOT NULL DEFAULT 'N',
  `is_manager` tinyint(1) NOT NULL DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `id_company` int(10) unsigned DEFAULT NULL COMMENT 'компания пользователя (таблица - company)',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `is_active` (`is_active`),
  KEY `id_company` (`id_company`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `se_user`
--

LOCK TABLES `se_user` WRITE;
/*!40000 ALTER TABLE `se_user` DISABLE KEYS */;
INSERT INTO `se_user` VALUES (1,'admin','21232f297a57a5a743894a0e4a801fc3',NULL,'Y','Y',1,NULL,NULL,'2019-02-15 10:31:09','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `se_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `se_user_account`
--

DROP TABLE IF EXISTS `se_user_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `se_user_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `company_id` int(10) unsigned DEFAULT NULL,
  `order_id` int(10) unsigned DEFAULT NULL,
  `account` varchar(40) DEFAULT NULL,
  `date_payee` datetime DEFAULT NULL,
  `in_payee` double(10,4) DEFAULT NULL,
  `out_payee` double(10,4) DEFAULT NULL,
  `entbalanse` double(10,4) DEFAULT NULL,
  `curr` char(3) DEFAULT 'RUR',
  `operation` int(10) unsigned DEFAULT NULL,
  `docum` text,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `company_id` (`company_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `se_user_account_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `se_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `se_user_account_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `se_user_account_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `shop_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `se_user_account`
--

LOCK TABLES `se_user_account` WRITE;
/*!40000 ALTER TABLE `se_user_account` DISABLE KEYS */;
/*!40000 ALTER TABLE `se_user_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `se_user_group`
--

DROP TABLE IF EXISTS `se_user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `se_user_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `company_id` int(10) unsigned DEFAULT NULL,
  `group_id` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `se_user_group_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `se_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `se_user_group_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `se_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `se_user_group_ibfk_3` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=273 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `se_user_group`
--

LOCK TABLES `se_user_group` WRITE;
/*!40000 ALTER TABLE `se_user_group` DISABLE KEYS */;
INSERT INTO `se_user_group` VALUES (272,1,NULL,9,'0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `se_user_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `session` (
  `SID` varchar(32) NOT NULL DEFAULT '',
  `TIMES` int(11) DEFAULT NULL,
  `IDUSER` bigint(20) NOT NULL DEFAULT '0',
  `GROUPUSER` int(11) NOT NULL DEFAULT '0',
  `ADMINUSER` varchar(10) DEFAULT '',
  `USER` varchar(40) DEFAULT '',
  `LOGIN` varchar(30) DEFAULT '',
  `PASSW` varchar(32) DEFAULT '',
  `PAGES` varchar(30) DEFAULT '',
  `BLOCK` char(1) DEFAULT 'Y',
  `IP` varchar(15) DEFAULT '',
  UNIQUE KEY `SID` (`SID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session`
--

LOCK TABLES `session` WRITE;
/*!40000 ALTER TABLE `session` DISABLE KEYS */;
/*!40000 ALTER TABLE `session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_accomp`
--

DROP TABLE IF EXISTS `shop_accomp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_accomp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_price` int(10) unsigned NOT NULL,
  `id_acc` int(10) unsigned DEFAULT NULL,
  `id_group` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_price` (`id_price`),
  KEY `id_acc` (`id_acc`),
  KEY `id_group` (`id_group`),
  CONSTRAINT `shop_accomp_ibfk_1` FOREIGN KEY (`id_acc`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_accomp_ibfk_2` FOREIGN KEY (`id_price`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_accomp_ibfk_3` FOREIGN KEY (`id_group`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_accomp`
--

LOCK TABLES `shop_accomp` WRITE;
/*!40000 ALTER TABLE `shop_accomp` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_accomp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_atol_operation`
--

DROP TABLE IF EXISTS `shop_atol_operation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_atol_operation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_order` int(10) unsigned NOT NULL,
  `operation` varchar(255) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `log` text,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_shop_atol_id_order` (`id_order`),
  CONSTRAINT `FK_shop_atol_id_order` FOREIGN KEY (`id_order`) REFERENCES `shop_order` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_atol_operation`
--

LOCK TABLES `shop_atol_operation` WRITE;
/*!40000 ALTER TABLE `shop_atol_operation` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_atol_operation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_brand`
--

DROP TABLE IF EXISTS `shop_brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_brand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang` char(3) NOT NULL DEFAULT 'rus',
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `text` text,
  `content` text,
  `title` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` text,
  `sort` int(11) DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_brand`
--

LOCK TABLES `shop_brand` WRITE;
/*!40000 ALTER TABLE `shop_brand` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_comm`
--

DROP TABLE IF EXISTS `shop_comm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_comm` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_price` int(10) unsigned NOT NULL,
  `name` varchar(125) NOT NULL,
  `email` varchar(125) DEFAULT NULL,
  `commentary` text,
  `response` text,
  `date` date NOT NULL,
  `mark` int(11) NOT NULL,
  `showing` enum('Y','N') DEFAULT 'N',
  `is_active` enum('Y','N') DEFAULT 'N',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_price` (`id_price`),
  CONSTRAINT `shop_comm_ibfk_1` FOREIGN KEY (`id_price`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_comm`
--

LOCK TABLES `shop_comm` WRITE;
/*!40000 ALTER TABLE `shop_comm` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_comm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_contacts`
--

DROP TABLE IF EXISTS `shop_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `additional_phones` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text,
  `sort` int(10) NOT NULL DEFAULT '0',
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `url` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=512;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_contacts`
--

LOCK TABLES `shop_contacts` WRITE;
/*!40000 ALTER TABLE `shop_contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_contacts_geo`
--

DROP TABLE IF EXISTS `shop_contacts_geo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_contacts_geo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_contact` int(10) unsigned NOT NULL,
  `id_city` int(10) unsigned NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_contact` (`id_contact`),
  KEY `id_city` (`id_city`),
  CONSTRAINT `shop_contacts_geo_ibfk_1` FOREIGN KEY (`id_contact`) REFERENCES `shop_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=512;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_contacts_geo`
--

LOCK TABLES `shop_contacts_geo` WRITE;
/*!40000 ALTER TABLE `shop_contacts_geo` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_contacts_geo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_contract`
--

DROP TABLE IF EXISTS `shop_contract`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_contract` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_author` int(10) unsigned DEFAULT NULL,
  `id_order` int(10) unsigned DEFAULT NULL,
  `number` int(10) unsigned DEFAULT NULL,
  `date` date DEFAULT NULL,
  `file` varchar(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_author` (`id_author`),
  KEY `id_order` (`id_order`),
  CONSTRAINT `shop_contract_ibfk_1` FOREIGN KEY (`id_author`) REFERENCES `se_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_contract_ibfk_2` FOREIGN KEY (`id_order`) REFERENCES `shop_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_contract`
--

LOCK TABLES `shop_contract` WRITE;
/*!40000 ALTER TABLE `shop_contract` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_contract` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_coupons`
--

DROP TABLE IF EXISTS `shop_coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_coupons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `type` enum('p','a','g') NOT NULL DEFAULT 'p',
  `discount` float(10,2) DEFAULT NULL,
  `currency` char(3) NOT NULL DEFAULT 'RUR',
  `expire_date` date DEFAULT NULL,
  `min_sum_order` float(10,2) DEFAULT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `count_used` int(10) unsigned NOT NULL DEFAULT '1',
  `only_registered` enum('Y','N') NOT NULL DEFAULT 'N',
  `payment_id` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `code` (`code`),
  CONSTRAINT `shop_coupons_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `se_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_coupons`
--

LOCK TABLES `shop_coupons` WRITE;
/*!40000 ALTER TABLE `shop_coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_coupons_goods`
--

DROP TABLE IF EXISTS `shop_coupons_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_coupons_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned DEFAULT NULL,
  `price_id` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coupon_id` (`coupon_id`),
  KEY `group_id` (`group_id`),
  KEY `price_id` (`price_id`),
  CONSTRAINT `shop_coupons_goods_ibfk_1` FOREIGN KEY (`coupon_id`) REFERENCES `shop_coupons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_coupons_goods_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_coupons_goods_ibfk_3` FOREIGN KEY (`price_id`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_coupons_goods`
--

LOCK TABLES `shop_coupons_goods` WRITE;
/*!40000 ALTER TABLE `shop_coupons_goods` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_coupons_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_coupons_history`
--

DROP TABLE IF EXISTS `shop_coupons_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_coupons_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code_coupon` varchar(50) NOT NULL,
  `id_coupon` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned DEFAULT NULL,
  `id_order` int(10) unsigned NOT NULL,
  `discount` float(10,2) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_coupon` (`id_coupon`),
  KEY `id_user` (`id_user`),
  KEY `id_order` (`id_order`),
  CONSTRAINT `shop_coupons_history_ibfk_1` FOREIGN KEY (`id_coupon`) REFERENCES `shop_coupons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_coupons_history_ibfk_2` FOREIGN KEY (`id_order`) REFERENCES `shop_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_coupons_history_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `se_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_coupons_history`
--

LOCK TABLES `shop_coupons_history` WRITE;
/*!40000 ALTER TABLE `shop_coupons_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_coupons_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_crossgroup`
--

DROP TABLE IF EXISTS `shop_crossgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_crossgroup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `shop_crossgroup_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_crossgroup`
--

LOCK TABLES `shop_crossgroup` WRITE;
/*!40000 ALTER TABLE `shop_crossgroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_crossgroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_delivery`
--

DROP TABLE IF EXISTS `shop_delivery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_delivery` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_recipient` varchar(150) NOT NULL DEFAULT '',
  `id_order` int(10) unsigned DEFAULT NULL,
  `id_subdelivery` int(10) unsigned DEFAULT NULL,
  `telnumber` varchar(125) DEFAULT NULL,
  `email` varchar(125) DEFAULT NULL,
  `calltime` varchar(150) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `postindex` varchar(255) DEFAULT NULL,
  `id_city` int(10) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_city` (`id_city`),
  KEY `shop_delivery_ibfk_1` (`id_order`),
  KEY `FK_shop_delivery_shop_deliverytype_id` (`id_subdelivery`),
  CONSTRAINT `shop_delivery_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `shop_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_delivery`
--

LOCK TABLES `shop_delivery` WRITE;
/*!40000 ALTER TABLE `shop_delivery` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_delivery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_delivery_param`
--

DROP TABLE IF EXISTS `shop_delivery_param`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_delivery_param` (
  `id` int(10) unsigned NOT NULL,
  `id_delivery` int(10) unsigned NOT NULL,
  `type_param` enum('sum','weight','volume') DEFAULT 'sum',
  `price` double(16,2) unsigned NOT NULL,
  `min_value` double(16,3) DEFAULT NULL,
  `max_value` double(16,3) DEFAULT NULL,
  `priority` int(11) DEFAULT '0',
  `operation` enum('=','+','-') DEFAULT '=',
  `type_price` enum('a','s','d') DEFAULT 'a',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_delivery_param`
--

LOCK TABLES `shop_delivery_param` WRITE;
/*!40000 ALTER TABLE `shop_delivery_param` DISABLE KEYS */;
INSERT INTO `shop_delivery_param` VALUES (29,4,'sum',500.00,0.000,20000.000,0,'=','a','0000-00-00 00:00:00','0000-00-00 00:00:00'),(30,4,'sum',0.00,20001.000,0.000,0,'=','a','0000-00-00 00:00:00','0000-00-00 00:00:00'),(31,4,'sum',500.00,0.000,20000.000,0,'=','a','0000-00-00 00:00:00','0000-00-00 00:00:00'),(32,4,'sum',0.00,20001.000,0.000,0,'=','a','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `shop_delivery_param` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_delivery_payment`
--

DROP TABLE IF EXISTS `shop_delivery_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_delivery_payment` (
  `id` int(10) unsigned NOT NULL,
  `id_delivery` int(10) unsigned NOT NULL,
  `id_payment` int(10) unsigned NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_delivery_payment`
--

LOCK TABLES `shop_delivery_payment` WRITE;
/*!40000 ALTER TABLE `shop_delivery_payment` DISABLE KEYS */;
INSERT INTO `shop_delivery_payment` VALUES (245,3,8,'0000-00-00 00:00:00','2018-12-21 08:12:59'),(247,3,11,'0000-00-00 00:00:00','2018-12-21 08:12:59'),(249,3,13,'0000-00-00 00:00:00','2018-12-21 08:12:59'),(250,4,8,'0000-00-00 00:00:00','2018-12-21 08:13:21'),(252,4,11,'0000-00-00 00:00:00','2018-12-21 08:13:21'),(254,4,13,'0000-00-00 00:00:00','2018-12-21 08:13:21'),(255,5,8,'0000-00-00 00:00:00','2018-12-21 08:13:37'),(257,5,11,'0000-00-00 00:00:00','2018-12-21 08:13:37'),(259,5,13,'0000-00-00 00:00:00','2018-12-21 08:13:37');
/*!40000 ALTER TABLE `shop_delivery_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_delivery_region`
--

DROP TABLE IF EXISTS `shop_delivery_region`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_delivery_region` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_delivery` int(10) unsigned NOT NULL,
  `id_country` int(11) DEFAULT NULL,
  `id_region` int(11) DEFAULT NULL,
  `id_city` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_delivery` (`id_delivery`),
  CONSTRAINT `shop_delivery_region_ibfk_1` FOREIGN KEY (`id_delivery`) REFERENCES `shop_deliverytype` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_delivery_region`
--

LOCK TABLES `shop_delivery_region` WRITE;
/*!40000 ALTER TABLE `shop_delivery_region` DISABLE KEYS */;
INSERT INTO `shop_delivery_region` VALUES (1,6,NULL,NULL,23541,'2018-12-21 08:12:59','0000-00-00 00:00:00'),(2,4,NULL,50,NULL,'2018-12-21 08:13:21','0000-00-00 00:00:00'),(6,4,NULL,77,NULL,'2018-12-21 08:13:21','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `shop_delivery_region` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_delivery_settings`
--

DROP TABLE IF EXISTS `shop_delivery_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_delivery_settings` (
  `id` int(11) unsigned NOT NULL,
  `id_delivery` int(10) unsigned NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` text,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=1365;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_delivery_settings`
--

LOCK TABLES `shop_delivery_settings` WRITE;
/*!40000 ALTER TABLE `shop_delivery_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_delivery_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_deliverygroup`
--

DROP TABLE IF EXISTS `shop_deliverygroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_deliverygroup` (
  `id` int(10) unsigned NOT NULL,
  `id_group` int(10) unsigned NOT NULL,
  `id_type` int(10) unsigned NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_deliverygroup`
--

LOCK TABLES `shop_deliverygroup` WRITE;
/*!40000 ALTER TABLE `shop_deliverygroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_deliverygroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_deliverytype`
--

DROP TABLE IF EXISTS `shop_deliverytype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_deliverytype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(10) unsigned DEFAULT NULL,
  `code` varchar(125) DEFAULT NULL,
  `lang` char(3) NOT NULL DEFAULT 'rus',
  `name` varchar(125) NOT NULL,
  `time` varchar(10) NOT NULL,
  `price` double(10,2) NOT NULL,
  `curr` char(3) NOT NULL DEFAULT 'RUR',
  `note` text,
  `max_volume` int(11) unsigned DEFAULT NULL,
  `max_weight` double(10,3) unsigned DEFAULT NULL,
  `week` char(7) DEFAULT '1111111',
  `time_from` time DEFAULT NULL,
  `time_to` time DEFAULT NULL,
  `need_address` enum('Y','N') DEFAULT 'Y',
  `volume` int(10) unsigned DEFAULT NULL,
  `weight` int(10) unsigned DEFAULT NULL,
  `forone` enum('Y','N') DEFAULT 'N',
  `city_from_delivery` varchar(128) DEFAULT NULL,
  `status` enum('Y','N') DEFAULT 'Y',
  `sort` int(11) DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_parent` (`id_parent`),
  CONSTRAINT `shop_deliverytype_ibfk_1` FOREIGN KEY (`id_parent`) REFERENCES `shop_deliverytype` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_deliverytype`
--

LOCK TABLES `shop_deliverytype` WRITE;
/*!40000 ALTER TABLE `shop_deliverytype` DISABLE KEYS */;
INSERT INTO `shop_deliverytype` VALUES (3,NULL,'simple','rus','Москва','1',500.00,'RUB','В пределах МКАД 500 рублей.',0,0.000,'1111111',NULL,NULL,'Y',NULL,NULL,'N',NULL,'Y',0,'2018-12-21 08:12:59','2013-11-07 06:46:30'),(4,NULL,'region','rus','Московская область','1',25.00,'RUB','Цена доставки 25 рублей за километр',0,0.000,'1111111',NULL,NULL,'Y',NULL,NULL,'N',NULL,'Y',0,'2018-12-21 08:13:21','2013-11-07 06:47:11'),(5,NULL,'simple','rus','Доставка товара в регионы: до транспортной компании в Москве БЕСПЛАТНО!','1',0.00,'RUB',NULL,0,0.000,'1111111',NULL,NULL,'Y',NULL,NULL,'N',NULL,'Y',0,'2018-12-21 08:13:37','2013-11-07 06:47:25'),(6,3,'region','rus','','1',500.00,'RUB',NULL,0,0.000,'1111111',NULL,NULL,'Y',NULL,NULL,'N',NULL,'Y',0,'2018-12-21 08:12:59','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `shop_deliverytype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_discount_links`
--

DROP TABLE IF EXISTS `shop_discount_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_discount_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `discount_id` int(10) unsigned NOT NULL,
  `id_price` int(10) unsigned DEFAULT NULL,
  `id_group` int(10) unsigned DEFAULT NULL,
  `id_user` int(10) unsigned DEFAULT NULL,
  `id_usergroup` int(10) unsigned DEFAULT NULL,
  `priority` smallint(5) unsigned DEFAULT NULL,
  `type` enum('g','p','o','m','i') NOT NULL DEFAULT 'm',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `discount_id` (`discount_id`),
  KEY `id_price` (`id_price`),
  KEY `id_group` (`id_group`),
  KEY `id_user` (`id_user`),
  KEY `id_usergroup` (`id_usergroup`),
  CONSTRAINT `shop_discount_links_ibfk_1` FOREIGN KEY (`discount_id`) REFERENCES `shop_discounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_discount_links_ibfk_2` FOREIGN KEY (`id_group`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_discount_links_ibfk_3` FOREIGN KEY (`id_price`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_discount_links_ibfk_4` FOREIGN KEY (`id_user`) REFERENCES `se_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_discount_links_ibfk_5` FOREIGN KEY (`id_usergroup`) REFERENCES `se_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_discount_links`
--

LOCK TABLES `shop_discount_links` WRITE;
/*!40000 ALTER TABLE `shop_discount_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_discount_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_discounts`
--

DROP TABLE IF EXISTS `shop_discounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_discounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `action` enum('single','constant','increase','falling') NOT NULL DEFAULT 'single' COMMENT 'single - ',
  `step_time` int(10) unsigned NOT NULL DEFAULT '0',
  `step_discount` double(10,3) NOT NULL DEFAULT '0.000',
  `date_from` varchar(19) DEFAULT NULL,
  `date_to` varchar(19) DEFAULT NULL,
  `summ_from` double(10,2) DEFAULT NULL,
  `summ_to` double(10,2) DEFAULT NULL,
  `count_from` int(11) DEFAULT '-1',
  `count_to` int(11) DEFAULT '-1',
  `discount` double(10,3) DEFAULT '5.000',
  `type_discount` enum('percent','absolute') NOT NULL DEFAULT 'percent',
  `week` char(7) DEFAULT NULL,
  `sort` int(10) unsigned DEFAULT NULL,
  `summ_type` int(10) unsigned DEFAULT NULL,
  `customer_type` smallint(6) unsigned DEFAULT NULL COMMENT 'Тип контакта: 0 - для всех, 1 - только для физ.лиц, 2 - только для компаний',
  `week_product` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_discounts`
--

LOCK TABLES `shop_discounts` WRITE;
/*!40000 ALTER TABLE `shop_discounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_discounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_feature`
--

DROP TABLE IF EXISTS `shop_feature`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_feature` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_feature_group` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `type` enum('list','colorlist','number','bool','string') DEFAULT 'list',
  `image` varchar(255) DEFAULT NULL,
  `measure` varchar(20) DEFAULT NULL,
  `description` text,
  `sort` int(10) NOT NULL DEFAULT '0',
  `seo` tinyint(1) NOT NULL DEFAULT '1',
  `is_market` tinyint(1) NOT NULL DEFAULT '1',
  `placeholder` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_feature_group` (`id_feature_group`),
  CONSTRAINT `shop_feature_ibfk_1` FOREIGN KEY (`id_feature_group`) REFERENCES `shop_feature_group` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=3276;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_feature`
--

LOCK TABLES `shop_feature` WRITE;
/*!40000 ALTER TABLE `shop_feature` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_feature` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_feature_group`
--

DROP TABLE IF EXISTS `shop_feature_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_feature_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_main` int(10) unsigned NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `sort` int(10) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_main` (`id_main`),
  CONSTRAINT `shop_feature_group_ibfk_1` FOREIGN KEY (`id_main`) REFERENCES `main` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=5461;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_feature_group`
--

LOCK TABLES `shop_feature_group` WRITE;
/*!40000 ALTER TABLE `shop_feature_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_feature_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_feature_value_list`
--

DROP TABLE IF EXISTS `shop_feature_value_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_feature_value_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_feature` int(10) unsigned NOT NULL,
  `value` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `color` varchar(6) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort` int(10) NOT NULL DEFAULT '0',
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_feature` (`id_feature`),
  CONSTRAINT `shop_feature_value_list_ibfk_1` FOREIGN KEY (`id_feature`) REFERENCES `shop_feature` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=4096;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_feature_value_list`
--

LOCK TABLES `shop_feature_value_list` WRITE;
/*!40000 ALTER TABLE `shop_feature_value_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_feature_value_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_files`
--

DROP TABLE IF EXISTS `shop_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_price` int(10) unsigned DEFAULT NULL,
  `file` varchar(255) NOT NULL COMMENT 'Имя файла в папке files',
  `name` varchar(255) DEFAULT NULL COMMENT 'Текст отображаемой ссылки на файл',
  `sort` int(11) DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_price` (`id_price`),
  KEY `sort` (`sort`),
  CONSTRAINT `shop_files_ibfk_1` FOREIGN KEY (`id_price`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_files`
--

LOCK TABLES `shop_files` WRITE;
/*!40000 ALTER TABLE `shop_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_geo_variables`
--

DROP TABLE IF EXISTS `shop_geo_variables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_geo_variables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_contact` int(10) unsigned NOT NULL,
  `id_variable` int(10) unsigned NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id` (`id`),
  KEY `id_contact` (`id_contact`),
  KEY `id_variable` (`id_variable`),
  CONSTRAINT `shop_geo_variables_ibfk_1` FOREIGN KEY (`id_contact`) REFERENCES `shop_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_geo_variables_ibfk_2` FOREIGN KEY (`id_variable`) REFERENCES `shop_variables` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_geo_variables`
--

LOCK TABLES `shop_geo_variables` WRITE;
/*!40000 ALTER TABLE `shop_geo_variables` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_geo_variables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_group`
--

DROP TABLE IF EXISTS `shop_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `upid` int(10) unsigned DEFAULT NULL,
  `code_gr` varchar(255) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `id_main` int(10) unsigned DEFAULT NULL,
  `name` varchar(125) NOT NULL,
  `lang` char(3) NOT NULL DEFAULT 'rus',
  `page` varchar(30) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `picture_alt` varchar(255) DEFAULT NULL,
  `commentary` text,
  `footertext` mediumtext,
  `title` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` text,
  `page_title` varchar(255) DEFAULT NULL,
  `discount` enum('Y','N') NOT NULL DEFAULT 'Y',
  `special_price` enum('Y','N') NOT NULL DEFAULT 'Y',
  `typegroup` enum('g','s') DEFAULT 'g',
  `scount` int(10) unsigned DEFAULT NULL,
  `active` enum('Y','N') DEFAULT 'Y',
  `visits` int(10) unsigned NOT NULL,
  `filter` tinyint(1) NOT NULL DEFAULT '0',
  `compare` tinyint(1) NOT NULL DEFAULT '0',
  `id_modification_group_def` int(10) unsigned DEFAULT NULL COMMENT 'Ид. группы модификаций по умолчанию',
  `is_delete` tinyint(1) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_gr` (`code_gr`),
  KEY `upid` (`upid`),
  KEY `is_delete` (`is_delete`),
  KEY `position` (`position`),
  KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_group`
--

LOCK TABLES `shop_group` WRITE;
/*!40000 ALTER TABLE `shop_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_group_feature`
--

DROP TABLE IF EXISTS `shop_group_feature`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_group_feature` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_group` int(10) unsigned NOT NULL,
  `id_feature` int(10) unsigned NOT NULL,
  `sort` int(10) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_group` (`id_group`),
  KEY `id_feature` (`id_feature`),
  KEY `sort` (`sort`),
  CONSTRAINT `shop_group_feature_ibfk_1` FOREIGN KEY (`id_feature`) REFERENCES `shop_feature` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_group_feature_ibfk_2` FOREIGN KEY (`id_group`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_group_feature`
--

LOCK TABLES `shop_group_feature` WRITE;
/*!40000 ALTER TABLE `shop_group_feature` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_group_feature` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_group_filter`
--

DROP TABLE IF EXISTS `shop_group_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_group_filter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_group` int(10) unsigned NOT NULL,
  `id_feature` int(10) unsigned DEFAULT NULL,
  `default_filter` enum('price','brand','flag_hit','flag_new','discount') DEFAULT NULL,
  `expanded` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(10) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_group` (`id_group`),
  KEY `id_feature` (`id_feature`),
  KEY `sort` (`sort`),
  CONSTRAINT `shop_group_filter_ibfk_1` FOREIGN KEY (`id_feature`) REFERENCES `shop_feature` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_group_filter_ibfk_2` FOREIGN KEY (`id_group`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=1489;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_group_filter`
--

LOCK TABLES `shop_group_filter` WRITE;
/*!40000 ALTER TABLE `shop_group_filter` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_group_filter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_group_img`
--

DROP TABLE IF EXISTS `shop_group_img`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_group_img` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_group` int(10) unsigned NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `picture_alt` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_group` (`id_group`),
  CONSTRAINT `shop_group_img_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_group_img`
--

LOCK TABLES `shop_group_img` WRITE;
/*!40000 ALTER TABLE `shop_group_img` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_group_img` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_group_price`
--

DROP TABLE IF EXISTS `shop_group_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_group_price` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL,
  `price_id` int(10) unsigned NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `price_id` (`price_id`),
  CONSTRAINT `shop_group_price_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_group_price_ibfk_2` FOREIGN KEY (`price_id`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_group_price`
--

LOCK TABLES `shop_group_price` WRITE;
/*!40000 ALTER TABLE `shop_group_price` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_group_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_group_related`
--

DROP TABLE IF EXISTS `shop_group_related`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_group_related` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_group` int(10) unsigned NOT NULL,
  `id_related` int(10) unsigned NOT NULL,
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1 - похожий, 2 - сопуствующий, 3 - доп. подгруппа',
  `is_cross` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Двухсторонний',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_group` (`id_group`),
  KEY `id_related` (`id_related`),
  CONSTRAINT `shop_group_related_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_group_related`
--

LOCK TABLES `shop_group_related` WRITE;
/*!40000 ALTER TABLE `shop_group_related` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_group_related` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_group_tree`
--

DROP TABLE IF EXISTS `shop_group_tree`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_group_tree` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(10) unsigned NOT NULL,
  `id_child` int(10) unsigned NOT NULL,
  `level` tinyint(4) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_parent` (`id_parent`),
  KEY `id_child` (`id_child`),
  CONSTRAINT `shop_group_tree_ibfk_1` FOREIGN KEY (`id_child`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_group_tree_ibfk_2` FOREIGN KEY (`id_parent`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_group_tree`
--

LOCK TABLES `shop_group_tree` WRITE;
/*!40000 ALTER TABLE `shop_group_tree` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_group_tree` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_group_userfields`
--

DROP TABLE IF EXISTS `shop_group_userfields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_group_userfields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shopgroup` int(10) unsigned NOT NULL,
  `id_userfield` int(10) unsigned NOT NULL,
  `value` text,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_shopgroup` (`id_shopgroup`),
  KEY `id_userfield` (`id_userfield`),
  CONSTRAINT `shop_group_userfields_ibfk_1` FOREIGN KEY (`id_shopgroup`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_group_userfields_ibfk_2` FOREIGN KEY (`id_userfield`) REFERENCES `shop_userfields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_group_userfields`
--

LOCK TABLES `shop_group_userfields` WRITE;
/*!40000 ALTER TABLE `shop_group_userfields` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_group_userfields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_img`
--

DROP TABLE IF EXISTS `shop_img`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_img` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_price` int(10) unsigned DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `picture_alt` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `sort` int(10) NOT NULL DEFAULT '0',
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_price` (`id_price`),
  CONSTRAINT `shop_img_ibfk_1` FOREIGN KEY (`id_price`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_img`
--

LOCK TABLES `shop_img` WRITE;
/*!40000 ALTER TABLE `shop_img` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_img` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_integration_parameter`
--

DROP TABLE IF EXISTS `shop_integration_parameter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_integration_parameter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_main` int(10) unsigned DEFAULT NULL COMMENT 'Ид. магазина',
  `code` varchar(255) DEFAULT NULL COMMENT 'Код параметра',
  `value` varchar(255) DEFAULT NULL COMMENT 'Значение параметра',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_main` (`id_main`),
  CONSTRAINT `shop_integration_parameter_ibfk_1` FOREIGN KEY (`id_main`) REFERENCES `main` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8 COMMENT='Параметры интеграций с Яндекс.Маркет и другими сервисам';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_integration_parameter`
--

LOCK TABLES `shop_integration_parameter` WRITE;
/*!40000 ALTER TABLE `shop_integration_parameter` DISABLE KEYS */;
INSERT INTO `shop_integration_parameter` VALUES (46,1,'isYAStore','1','2016-10-31 06:26:59','2015-06-17 16:18:32'),(48,1,'isDelivery','1','2017-10-17 11:14:50','2015-06-17 16:18:32'),(49,1,'localDeliveryCost','350','2016-10-31 06:26:59','2015-06-17 16:18:32'),(50,1,'salesNotes',NULL,'2016-10-31 06:26:59','2015-06-17 16:18:32'),(79,1,'isPickup','1','2016-10-31 06:26:59','2016-03-22 10:14:49'),(80,1,'localDeliveryDays','0','2017-10-17 11:14:50','2016-03-22 10:14:49'),(81,1,'exportFeatures','1','2016-10-31 06:26:59','2016-03-22 10:14:49'),(82,1,'exportModifications',NULL,'2016-10-31 06:26:59','2016-03-22 10:14:49'),(83,1,'enabledVendorModel','1','2016-10-31 06:26:59','2016-03-22 10:14:49'),(84,1,'paramIdForTypePrefix','2','2016-10-31 06:26:59','2016-03-22 10:14:49'),(85,1,'paramIdForModel',NULL,'2016-10-31 06:26:59','2016-03-22 10:14:49');
/*!40000 ALTER TABLE `shop_integration_parameter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_label`
--

DROP TABLE IF EXISTS `shop_label`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_label` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(20) NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_label`
--

LOCK TABLES `shop_label` WRITE;
/*!40000 ALTER TABLE `shop_label` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_label` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_label_product`
--

DROP TABLE IF EXISTS `shop_label_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_label_product` (
  `id` int(10) unsigned NOT NULL,
  `id_label` int(10) unsigned NOT NULL,
  `id_product` int(10) unsigned NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_label_product`
--

LOCK TABLES `shop_label_product` WRITE;
/*!40000 ALTER TABLE `shop_label_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_label_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_leader`
--

DROP TABLE IF EXISTS `shop_leader`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_leader` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang` char(3) NOT NULL DEFAULT 'rus',
  `id_group` int(10) unsigned DEFAULT NULL,
  `id_price` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`),
  KEY `id_group` (`id_group`),
  KEY `id_price` (`id_price`),
  CONSTRAINT `shop_leader_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_leader_ibfk_2` FOREIGN KEY (`id_price`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_leader`
--

LOCK TABLES `shop_leader` WRITE;
/*!40000 ALTER TABLE `shop_leader` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_leader` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_license`
--

DROP TABLE IF EXISTS `shop_license`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_license` (
  `id` int(10) unsigned NOT NULL,
  `id_price` int(10) unsigned DEFAULT NULL,
  `id_order` int(10) unsigned DEFAULT NULL,
  `serial` varchar(255) DEFAULT NULL,
  `regkey` text,
  `datereg` date DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_license`
--

LOCK TABLES `shop_license` WRITE;
/*!40000 ALTER TABLE `shop_license` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_license` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_mail`
--

DROP TABLE IF EXISTS `shop_mail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_mail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_mail_group_id` int(10) unsigned DEFAULT '1',
  `lang` char(3) DEFAULT 'rus',
  `title` varchar(125) DEFAULT NULL,
  `letter` text,
  `subject` varchar(250) DEFAULT NULL,
  `mailtype` char(40) DEFAULT 'reguser',
  `itempost` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `shop_mail_group_id` (`shop_mail_group_id`),
  CONSTRAINT `shop_mail_ibfk_1` FOREIGN KEY (`shop_mail_group_id`) REFERENCES `shop_mail_group` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_mail`
--

LOCK TABLES `shop_mail` WRITE;
/*!40000 ALTER TABLE `shop_mail` DISABLE KEYS */;
INSERT INTO `shop_mail` VALUES (1,1,'rus','Письмо клиенту о регистрации','<p style=\"font-size:medium\"><br></p>\n<p>Здравствуйте, [USERNAME]! Вы зарегистрировались на сайте:&nbsp; \n</p>\n<p>Ваши коды авторизации: Ваш логин : [SHOP_USERLOGIN] Ваш пароль: [SHOP_USERPASSW] </p>\n<p>С уважением, Компания</p>\n<p>E-Mail: mail@mail.ru - отдел продаж </p>\n<p>Тел. +7 (495) 000-0000  , +7</p>','Регистрация на сайте: Моя компания','reguser',0,'2018-12-21 08:06:49','2012-12-24 14:42:19'),(2,1,'rus','Письмо Администратору о регистрации клиента','<p>Здравствуйте, Администратор! </p>\n<p>У Вас на сайте Моя компания   зарегистрировался клиент: [USERNAME] </p>\n<p>Его коды авторизации: </p>\n<p>Логин : [SHOP_USERLOGIN] </p>\n<p>Пароль: [SHOP_USERPASSW] </p>','Регистрации клиента Моя компания','regadm',0,'2018-03-27 10:17:53','2012-12-24 14:43:55'),(3,1,'rus','Письмо клиенту о заказе','<p>Здравствуйте, [NAMECLIENT]! </p>\n<p>Вы оформили предварительный заказ №[SHOP_ORDER_NUM] на сайте&nbsp;Моя компания   <a href=\"http://mysite.ru\"></a></p>\n<p>Ожидайте обработки вашего предварительного заказа менеджером! <br>\n<br>\n[SHOP_ORDER_VALUE_LIST] ; </p>\n<p>Сумма заказа :[SHOP_ORDER_SUMM] </p>\n<p>Сумма доставки:[SHOP_ORDER_DEVILERY] </p>\n<p>Сумма скидки: [SHOP_ORDER_DISCOUNT]</p>\n<p>Итого: [SHOP_ORDER_TOTAL] </p>\n<p>Режим работы: Пн.-Пт. 9.00-18.00.</p>\n<p>С уважением, Компания</p>\n','Предварительный заказ  [SHOP_ORDER_NUM]','orderuser',0,'2018-12-21 08:07:43','2012-12-24 14:54:54'),(4,1,'rus','Письмо Администратору о заказе','<p>Здравствуйте, Администратор!&nbsp;на сайте&nbsp;</p>\n<p>Ваш клиент:[NAMECLIENT] оформил заказ №[SHOP_ORDER_NUM] ,&nbsp;e-mail: [ORDER.EMAIL],&nbsp;Телефон: [ORDER.TELNUMBER],&nbsp;Сумма доставки: [ORDER_DELIVERY],&nbsp;Адрес доставки: [ORDER.ADDRESS],&nbsp;Комментарий к заказу: [ORDER.COMMENTARY]  </p>\n<p>[SHOP_ORDER_VALUE_LIST]&nbsp; </p>\n<p>Сумма заказа :[ORDER_SUMMA] </p>\n<p>Сумма доставки:[SHOP_ORDER_DEVILERY] </p>\n<p>Способ доставки: [ORDER.DELIVERY_NAME]</p>\n<p>Способ оплаты: [PAYMENT.NAME]</p>\n<p>Итого: [SHOP_ORDER_TOTAL] </p>\n<p>e-mail: [ORDER.EMAIL] </p>\n<p>Телефон: [ORDER.TELNUMBER] </p>\n<p>Удобное время звонка: [ORDER.CALLTIME] </p>\n<p>Наименование доставки: [ORDER.DELIVERY_NAME] </p>\n<p>Сумма доставки: [ORDER_DELIVERY] </p>\n<p>Общее число позиций в заказе (включая доставку): [ORDER.ITEMCOUNT] </p>\n<p>Индекс доставки: [ORDER.POSTINDEX] </p>\n<p>Адрес доставки: [ORDER.ADDRESS] </p>\n<p>Реквизиты компании: [USER.REQUISITES]</p>\n<p>Комментарий к заказу: [ORDER.COMMENTARY] </p>\n{attachment: order_list}','Order [SHOP_ORDER], Ваш клиент:[NAMECLIENT] оформил заказ №[SHOP_ORDER_NUM]','orderadm',0,'2018-03-27 10:19:10','2012-12-24 14:55:50'),(6,1,'rus','Письмо клиенту о регистрации','<p>Здравствуйте, [USERNAME]! </p>\n<p>Вы зарегистрировались на сайте</p>\n<p>Ваши коды авторизации: </p>\n<p>Ваш логин : [SHOP_USERLOGIN] </p>\n<p>Ваш пароль: [SHOP_USERPASSW] </p>\n<p>С уважением,   </p>\n<p>E-Mail:  - отдел продаж </p>\n<p>Тел. +7 (495) 000-0000  </p>','Регистрация на сайте ','reguser',0,'2018-12-21 08:08:04','2012-12-24 14:58:09'),(7,1,'rus','Письмо клиенту об оплате','<p>Уважаемый(ая) [CLIENTNAME] Ваш заказ №[ORDER.ID] оплачен через [PAYMENT.NAME] в сумме [ORDER_SUMMA]. Спасибо за доверие! </p>\n<p>С уважением, </p>\n<p>E-Mail: test@mail.ru    - отдел продаж </p>\n<p>Тел. +7 (495) 000-0000</p>','Your order [ORDER.ID] is paid!','payuser',0,'2018-12-21 08:08:21','2012-12-24 14:59:49'),(8,1,'rus','Письмо Администратору об оплате','Уважаемый Администратор!\r\n\r\nВаш клиент [CLIENTNAME] проивел плату заказа [ORDER.ID] \r\nс помошью [PAYMENT.NAME] в сумме [ORDER_SUMMA]\r\nДата транзакции: [ORDER.date_payee]\r\n\r\nПрошу проконтролировать данную операцию.\r\n','Automatic payment of the order [ORDER.ID]','payadm',0,'2012-12-24 15:00:21','2012-12-24 15:00:21'),(9,1,'rus','Письмо клиенту об изменении суммы заказа','Здравствуйте,  [NAMECLIENT]!\nСумма вашего заказа №[SHOP_ORDER_NUM] на сайте изменена.\nСумма доставки для вашего региона составит [SHOP_ORDER_DEVILERY].\n­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­\n<table width=\"600\" style=\"border:1px solid #000\"><tbody><tr><td>[SHOP_ORDER_VALUE_LIST]</td></tr></tbody></table>\n­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­­\nСумма заказа : [SHOP_ORDER_SUMM]\nСумма скидки: [SHOP_ORDER_DISCOUNT] \nСумма доставки: [SHOP_ORDER_DEVILERY] \nИтого: [SHOP_ORDER_TOTAL]\n<p>Посмотреть статус заказа и оплатить можно на странице \"ВАШ ЗАКАЗ\", по адресу .... </p>\n<p>С уважением, компания   </p>\n<p>E-Mail: mail@mail.ru   - отдел продаж </p>\n<p>Тел. +7 (495) 000-0000  </p>','Сумма вашего заказа №[SHOP_ORDER_NUM] изменена.','orduserch',0,'2018-03-27 10:20:24','2012-12-24 15:02:36'),(10,1,'rus','Письмо клиенту о предзаказе','<table style=\"margin-right:auto;margin-left:auto\" cellpadding=\"0\" cellspacing=\"0\" width=\"700px\">\n					<tbody>\n						<tr><td>\n						<p>Здравствуйте, <strong>[CLIENT.NAME].</strong></p>\n						<br>\n						<p><strong>Ваш предзаказ</strong></p>\n						<p>[PRODUCT.NAME]</p>\n						<br>\n						<p>Ваша заявка принята.</p>\n						<p>Когда товар появится на складе, Вам поступит уведомление о наличии.</p>\n						<br>\n						<p>С уважением, [ADMIN_COMPANY]</p>\n						<br>\n						<p>Наши контакты</p>\n						<p>[ADMIN_MAIL_SALES] - отдел продаж</p>\n						<p>[ADMIN_MAIL_SUPPORT] - техподдержка</p>\n						</td></tr>\n					</tbody>\n				</table>','Заявка на поступление товара','preorderuser',NULL,'0000-00-00 00:00:00','2016-05-11 08:46:45'),(11,1,'rus','Письмо администратору о предзаказе','<table style=\"margin-right:auto;margin-left:auto\" cellpadding=\"0\" cellspacing=\"0\" width=\"700\">\n          <tbody>\n            <tr><td>\n            <p>Здравствуйте, <strong>Администратор.</strong></p>\n<p>С вашего сайта [THISNAMESITE] поступила заявка на предзаказ.</p>\n<p>[PRODUCT.NAME]</p>\n<p>Данные пользователя:</p>\n            <p>Имя: [CLIENT.NAME]</p>\n            <p>E-mail: [CLIENT.EMAIL]</p>\n            <p>Телефон: [CLIENT.PHONE]</p>\n<p>Реквизиты: [USER.REQUISITES]</p>\n            </td></tr>\n          </tbody>\n        </table>','Заявка на поступление товара','preorderadmin',NULL,'2016-07-20 06:58:09','2016-05-11 08:46:45'),(12,1,'rus','Письмо клиенту о поступлении товара','<table style=\"margin-right:auto;margin-left:auto\" cellpadding=\"0\" cellspacing=\"0\" width=\"700\">\n					<tbody>\n						<tr><td>\n						<p>Здравствуйте, <strong>[CLIENT.NAME].</strong></p>\n						<br>\n						<p>Товар, на который Вы оставляли заявку, поступил на склад:</p>\n						<br>\n						<p>[PRODUCT.NAME]</p>\n						<br>\n						<p><a href=\"[PRODUCT.LINK]\">Перейти на сайт</a></p>\n						<br>\n						<p>С уважением, [ADMIN_COMPANY]</p>\n						<br>\n						<p>Наши контакты</p>\n						<p>[ADMIN_MAIL_SALES] - отдел продаж</p>\n						<p>[ADMIN_MAIL_SUPPORT] - техподдержка</p>\n						</td></tr>\n					</tbody>\n				</table>','Товар поступил на склад','notifystockuser',NULL,'0000-00-00 00:00:00','2016-05-11 08:46:45'),(13,1,'rus','Письмо клиенту о принятии заказа в работу','Здравствуйте, [NAMECLIENT]! Ваш заказ №[SHOP_ORDER_NUM] принят к исполнению. Посмотреть статус заказа и оплатить можно на странице Мои заказы. Любые пожелания и вопросы, касающиеся прибретения продукта и работы с ним, присылайте в наш отдел продаж по адресу [ADMIN_MAIL_SALES] С уважением, Компания [ADMIN_COMPANY] E-Mail: [ADMIN_MAIL_SALES] - отдел продаж [ADMIN_MAIL_SUPPORT] - техподдержка','Order [SHOP_ORDER_NUM] принят к обработке','orderdelivM',NULL,'2017-04-11 08:40:20','0000-00-00 00:00:00'),(14,1,'rus','Письмо клиенту об отправке заказа','Здравствуйте, [NAMECLIENT]! Ваш заказ №[SHOP_ORDER_NUM] отправлен на&nbsp;Ваш адрес. Посмотреть статус заказа и оплатить можно на странице Мои заказы. Любые пожелания и вопросы, касающиеся прибретения продукта и работы с ним, присылайте в наш отдел продаж по адресу [ADMIN_MAIL_SALES] С уважением, Компания [ADMIN_COMPANY] E-Mail: [ADMIN_MAIL_SALES] - отдел продаж [ADMIN_MAIL_SUPPORT] - техподдержка','Order [SHOP_ORDER_NUM] отправлен в Ваш адрес','orderdelivP',NULL,'2017-04-11 08:40:48','0000-00-00 00:00:00'),(15,1,'rus','Письмо клиенту о доставке заказа','Здравствуйте, [NAMECLIENT]! Ваш заказ №[SHOP_ORDER_NUM] доставлен. Посмотреть статус заказа и оплатить можно на странице Мои заказы. Любые пожелания и вопросы, касающиеся прибретения продукта и работы с ним, присылайте в наш отдел продаж по адресу [ADMIN_MAIL_SALES] С уважением, Компания [ADMIN_COMPANY] E-Mail: [ADMIN_MAIL_SALES] - отдел продаж [ADMIN_MAIL_SUPPORT] - техподдержка','Order [SHOP_ORDER_NUM] доставлен','orderdelivY',NULL,'2017-04-11 08:41:12','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `shop_mail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_mail_group`
--

DROP TABLE IF EXISTS `shop_mail_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_mail_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang` char(3) NOT NULL DEFAULT 'rus',
  `name` varchar(255) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_mail_group`
--

LOCK TABLES `shop_mail_group` WRITE;
/*!40000 ALTER TABLE `shop_mail_group` DISABLE KEYS */;
INSERT INTO `shop_mail_group` VALUES (1,'rus','Магазин','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `shop_mail_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_manager`
--

DROP TABLE IF EXISTS `shop_manager`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_manager` (
  `id` int(10) unsigned NOT NULL,
  `id_admin` int(10) unsigned DEFAULT NULL,
  `id_group` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_manager`
--

LOCK TABLES `shop_manager` WRITE;
/*!40000 ALTER TABLE `shop_manager` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_manager` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_manufacturer`
--

DROP TABLE IF EXISTS `shop_manufacturer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_manufacturer` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_manufacturer`
--

LOCK TABLES `shop_manufacturer` WRITE;
/*!40000 ALTER TABLE `shop_manufacturer` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_manufacturer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_measure_volume`
--

DROP TABLE IF EXISTS `shop_measure_volume`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_measure_volume` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL COMMENT 'Код по ОКЕИ',
  `name` varchar(255) NOT NULL,
  `designation` varchar(50) DEFAULT NULL COMMENT 'Условное обозначение',
  `value` double(10,6) DEFAULT NULL COMMENT 'Мера',
  `is_base` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Признак базовой меры',
  `precision` int(11) DEFAULT NULL COMMENT 'Точность числа (кол-во знаков после запятой)',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_measure_volume`
--

LOCK TABLES `shop_measure_volume` WRITE;
/*!40000 ALTER TABLE `shop_measure_volume` DISABLE KEYS */;
INSERT INTO `shop_measure_volume` VALUES (1,'111','Кубический сантиметр','см3',1.000000,1,0,NULL,'2017-06-26 07:38:29'),(2,'112','Литр','л',0.001000,0,3,NULL,'2017-06-26 07:38:29');
/*!40000 ALTER TABLE `shop_measure_volume` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_measure_weight`
--

DROP TABLE IF EXISTS `shop_measure_weight`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_measure_weight` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL COMMENT 'Код по ОКЕИ',
  `name` varchar(255) NOT NULL,
  `designation` varchar(50) DEFAULT NULL COMMENT 'Условное обозначение',
  `value` double(10,6) DEFAULT NULL COMMENT 'Мера',
  `is_base` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Признак базовой меры',
  `precision` int(11) DEFAULT NULL COMMENT 'Точность числа (кол-во знаков после запятой)',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_measure_weight`
--

LOCK TABLES `shop_measure_weight` WRITE;
/*!40000 ALTER TABLE `shop_measure_weight` DISABLE KEYS */;
INSERT INTO `shop_measure_weight` VALUES (1,'163','Грамм','г',1.000000,1,0,NULL,'2017-06-26 07:38:29'),(2,'166','Килограмм','кг',0.001000,0,3,NULL,'2017-06-26 07:38:29');
/*!40000 ALTER TABLE `shop_measure_weight` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_modifications`
--

DROP TABLE IF EXISTS `shop_modifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_modifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_mod_group` int(10) unsigned NOT NULL,
  `id_price` int(10) unsigned NOT NULL,
  `code` varchar(40) DEFAULT NULL,
  `value` double(10,2) NOT NULL,
  `value_opt` double(10,2) NOT NULL,
  `value_opt_corp` double(10,2) NOT NULL,
  `value_purchase` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `count` double(10,3) DEFAULT NULL,
  `sort` int(10) NOT NULL DEFAULT '0',
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `id_exchange` varchar(80) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_mod_group` (`id_mod_group`),
  KEY `id_price` (`id_price`),
  CONSTRAINT `shop_modifications_ibfk_1` FOREIGN KEY (`id_mod_group`) REFERENCES `shop_modifications_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_modifications_ibfk_2` FOREIGN KEY (`id_price`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_modifications`
--

LOCK TABLES `shop_modifications` WRITE;
/*!40000 ALTER TABLE `shop_modifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_modifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_modifications_feature`
--

DROP TABLE IF EXISTS `shop_modifications_feature`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_modifications_feature` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_price` int(10) unsigned DEFAULT NULL,
  `id_modification` int(10) unsigned DEFAULT NULL,
  `id_feature` int(10) unsigned NOT NULL,
  `id_value` int(10) unsigned DEFAULT NULL,
  `value_number` double DEFAULT NULL,
  `value_bool` tinyint(1) DEFAULT NULL,
  `value_string` varchar(255) DEFAULT NULL,
  `sort` int(10) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_price` (`id_price`),
  KEY `id_modification` (`id_modification`),
  KEY `id_feature` (`id_feature`),
  KEY `id_value` (`id_value`),
  CONSTRAINT `shop_modifications_feature_ibfk_1` FOREIGN KEY (`id_feature`) REFERENCES `shop_feature` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_modifications_feature_ibfk_2` FOREIGN KEY (`id_modification`) REFERENCES `shop_modifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_modifications_feature_ibfk_3` FOREIGN KEY (`id_price`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_modifications_feature_ibfk_4` FOREIGN KEY (`id_value`) REFERENCES `shop_feature_value_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_modifications_feature`
--

LOCK TABLES `shop_modifications_feature` WRITE;
/*!40000 ALTER TABLE `shop_modifications_feature` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_modifications_feature` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_modifications_group`
--

DROP TABLE IF EXISTS `shop_modifications_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_modifications_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_main` int(10) unsigned NOT NULL DEFAULT '1',
  `name` varchar(50) NOT NULL,
  `vtype` smallint(1) unsigned DEFAULT '0',
  `sort` int(10) DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_main` (`id_main`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_modifications_group`
--

LOCK TABLES `shop_modifications_group` WRITE;
/*!40000 ALTER TABLE `shop_modifications_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_modifications_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_modifications_img`
--

DROP TABLE IF EXISTS `shop_modifications_img`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_modifications_img` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_modification` int(10) unsigned NOT NULL,
  `id_img` int(10) unsigned NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_modification` (`id_modification`),
  KEY `id_img` (`id_img`),
  KEY `sort` (`sort`),
  CONSTRAINT `shop_modifications_img_ibfk_1` FOREIGN KEY (`id_modification`) REFERENCES `shop_modifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_modifications_img_ibfk_2` FOREIGN KEY (`id_img`) REFERENCES `shop_img` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=4096;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_modifications_img`
--

LOCK TABLES `shop_modifications_img` WRITE;
/*!40000 ALTER TABLE `shop_modifications_img` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_modifications_img` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_nowlooks`
--

DROP TABLE IF EXISTS `shop_nowlooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_nowlooks` (
  `id` int(10) unsigned NOT NULL,
  `time_expire` int(11) NOT NULL,
  `count_looks` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_nowlooks`
--

LOCK TABLES `shop_nowlooks` WRITE;
/*!40000 ALTER TABLE `shop_nowlooks` DISABLE KEYS */;
INSERT INTO `shop_nowlooks` VALUES (289,1548310859,1,'0000-00-00 00:00:00','2019-01-24 06:10:59'),(681,1548310666,2,'2019-01-24 06:07:46','2019-01-24 06:07:45'),(5644,1548310615,1,'0000-00-00 00:00:00','2019-01-24 06:06:55');
/*!40000 ALTER TABLE `shop_nowlooks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_option`
--

DROP TABLE IF EXISTS `shop_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_group` int(10) unsigned DEFAULT NULL COMMENT 'Возможность несколько опций объединить в группу',
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `note` text,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Тип опции для отображения на сайте, 0 - радиокнопки, 1 - список, 2 - чекбокс (множественный выбор)',
  `type_price` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Тип влияния на конечную стоимость товара, 0 - абсолютное значение, 1 - процент',
  `sort` int(10) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_counted` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Может ли пользователь изменять количество значения опции',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_group` (`id_group`),
  CONSTRAINT `shop_option_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `shop_option_group` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_option`
--

LOCK TABLES `shop_option` WRITE;
/*!40000 ALTER TABLE `shop_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_option_group`
--

DROP TABLE IF EXISTS `shop_option_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_option_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `sort` int(10) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_option_group`
--

LOCK TABLES `shop_option_group` WRITE;
/*!40000 ALTER TABLE `shop_option_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_option_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_option_value`
--

DROP TABLE IF EXISTS `shop_option_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_option_value` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_option` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` double(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Базовая стоимость опции',
  `price_type` int(10) unsigned DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort` int(10) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`),
  KEY `id_option` (`id_option`),
  CONSTRAINT `shop_option_value_ibfk_1` FOREIGN KEY (`id_option`) REFERENCES `shop_option` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_option_value`
--

LOCK TABLES `shop_option_value` WRITE;
/*!40000 ALTER TABLE `shop_option_value` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_option_value` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_order`
--

DROP TABLE IF EXISTS `shop_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `manager_id` int(10) unsigned DEFAULT NULL,
  `id_author` int(10) unsigned DEFAULT NULL,
  `id_company` int(10) unsigned DEFAULT NULL,
  `date_order` date NOT NULL DEFAULT '0000-00-00',
  `discount` double(10,2) unsigned NOT NULL,
  `curr` varchar(3) NOT NULL DEFAULT 'RUB',
  `status` enum('Y','N','K','P','W','T','A','D') NOT NULL DEFAULT 'N',
  `date_payee` date DEFAULT NULL,
  `payee_doc` text,
  `commentary` text,
  `account` int(11) DEFAULT NULL,
  `payment_type` int(10) unsigned DEFAULT NULL,
  `transact_amount` double(10,2) unsigned DEFAULT NULL,
  `transact_id` varchar(20) DEFAULT NULL,
  `transact_curr` char(4) DEFAULT NULL,
  `delivery_payee` double(10,2) unsigned NOT NULL DEFAULT '0.00',
  `delivery_type` int(10) unsigned DEFAULT NULL,
  `delivery_status` enum('N','Y','P','M') DEFAULT 'N',
  `delivery_date` date DEFAULT NULL,
  `id_admin` int(10) unsigned DEFAULT NULL,
  `date_credit` datetime DEFAULT NULL,
  `inpayee` enum('N','Y') DEFAULT 'N',
  `is_delete` enum('N','Y') DEFAULT 'N',
  `id_main` int(10) unsigned DEFAULT '1',
  `managers` text,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_order`
--

LOCK TABLES `shop_order` WRITE;
/*!40000 ALTER TABLE `shop_order` DISABLE KEYS */;
INSERT INTO `shop_order` VALUES (7,NULL,273,NULL,'2019-01-26',0.00,'RUB','N',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'N',NULL,NULL,NULL,'N','Y',1,NULL,'2019-01-26 11:32:52','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `shop_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_order_action`
--

DROP TABLE IF EXISTS `shop_order_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_order_action` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) DEFAULT NULL,
  `action` varchar(250) DEFAULT NULL,
  `datestart` datetime DEFAULT NULL,
  `id_order` int(10) unsigned DEFAULT NULL,
  `period` int(11) NOT NULL,
  `active` enum('Y','N') DEFAULT 'N',
  `logaction` text,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_order` (`id_order`),
  CONSTRAINT `shop_order_action_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `shop_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_order_action`
--

LOCK TABLES `shop_order_action` WRITE;
/*!40000 ALTER TABLE `shop_order_action` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_order_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_order_payee`
--

DROP TABLE IF EXISTS `shop_order_payee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_order_payee` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_order` int(10) unsigned NOT NULL,
  `id_author` int(10) unsigned DEFAULT NULL COMMENT 'Идентификатор плательщика',
  `id_company` int(10) unsigned DEFAULT NULL,
  `num` mediumint(9) unsigned NOT NULL COMMENT 'Номер платежа',
  `date` datetime NOT NULL COMMENT 'Дата платежа',
  `year` smallint(6) unsigned NOT NULL DEFAULT '2000' COMMENT 'Год платежа',
  `payment_type` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'С лицевого счета: 0 или ид. платежа > 0 (таблица: shop_payment)',
  `id_payment` int(10) unsigned DEFAULT NULL,
  `id_manager` int(10) unsigned DEFAULT NULL COMMENT 'Идентификатор пользователя',
  `amount` decimal(10,2) unsigned NOT NULL COMMENT 'Сумма платежа',
  `curr` char(3) DEFAULT 'RUB',
  `note` varchar(255) DEFAULT NULL COMMENT 'Примечание к платежу',
  `id_user_account_in` int(10) unsigned DEFAULT NULL,
  `id_user_account_out` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id_order` (`id_order`),
  KEY `id_author` (`id_author`),
  KEY `id_company` (`id_company`),
  KEY `shop_order_payee_ibfk_3` (`id_manager`),
  KEY `id_payment` (`id_payment`),
  CONSTRAINT `shop_order_payee_ibfk_1` FOREIGN KEY (`id_author`) REFERENCES `se_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_order_payee_ibfk_2` FOREIGN KEY (`id_company`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_order_payee_ibfk_3` FOREIGN KEY (`id_manager`) REFERENCES `se_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `shop_order_payee_ibfk_4` FOREIGN KEY (`id_order`) REFERENCES `shop_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_order_payee_ibfk_5` FOREIGN KEY (`id_payment`) REFERENCES `shop_payment` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Платежи к заказам';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_order_payee`
--

LOCK TABLES `shop_order_payee` WRITE;
/*!40000 ALTER TABLE `shop_order_payee` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_order_payee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_order_userfields`
--

DROP TABLE IF EXISTS `shop_order_userfields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_order_userfields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_order` int(10) unsigned NOT NULL,
  `id_userfield` int(10) unsigned NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_order` (`id_order`),
  KEY `id_userfield` (`id_userfield`),
  CONSTRAINT `shop_order_userfields_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `shop_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_order_userfields_ibfk_2` FOREIGN KEY (`id_userfield`) REFERENCES `shop_userfields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_order_userfields`
--

LOCK TABLES `shop_order_userfields` WRITE;
/*!40000 ALTER TABLE `shop_order_userfields` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_order_userfields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_payment`
--

DROP TABLE IF EXISTS `shop_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_payment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang` char(3) DEFAULT 'rus',
  `ident` varchar(40) DEFAULT NULL,
  `logoimg` varchar(40) DEFAULT NULL,
  `name_payment` varchar(255) DEFAULT NULL,
  `startform` mediumtext,
  `type` enum('p','e') DEFAULT 'e',
  `blank` mediumtext,
  `result` mediumtext,
  `success` text,
  `fail` text,
  `sort` int(11) DEFAULT '1',
  `filters` text,
  `hosts` text,
  `authorize` enum('Y','N') DEFAULT NULL,
  `way_payment` enum('b','a') NOT NULL DEFAULT 'b',
  `url_help` varchar(255) DEFAULT NULL,
  `active` enum('N','Y','T') DEFAULT 'N',
  `is_test` enum('Y','N') DEFAULT 'N',
  `customer_type` smallint(6) unsigned DEFAULT NULL COMMENT 'Тип контакта: 0 - для всех, 1 - только для физ.лиц, 2 - только для компаний',
  `is_ticket` tinyint(1) DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ident` (`ident`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_payment`
--

LOCK TABLES `shop_payment` WRITE;
/*!40000 ALTER TABLE `shop_payment` DISABLE KEYS */;
INSERT INTO `shop_payment` VALUES (1,'rus',NULL,NULL,'Безналичный расчет','1. Для оплаты по безналичному расчету внесите&nbsp; <a href=\"/rekvcompany/?referer\">реквизиты Вашего предприятия</a>, и нажмите на кнопку \"Выбрать оплату\"&nbsp; <br>\r\n2. Оплатите счет;<br>\r\n3. Для оперативности получения продукта, отправьте сканированную (разрешением не менее 150dpi) копию заверенной квитанции об оплате на наш почтовый адрес<br>\r\n4. Ожидайте письмо с подтверждением;<br>\r\n5. На странице \"Ваш заказ\" Вы можете посмотреть статус заказа и доставки;<br>\r\n','e','<p><strong><font size=\"4\">Спасибо за заказ.</font></strong></p>\r\n<p><strong><font size=\"4\">Ожидайте обработки заказа менеджером.</font></strong></p>',NULL,NULL,NULL,1,NULL,NULL,'N','b',NULL,'N','N',NULL,0,'2018-12-21 08:12:31','2014-10-08 14:24:25'),(2,'rus',NULL,'payment_zpay.jpg','Z-PAYMENT','<P>Оплата через платежную систему Z-PAYMENT</P>Через данную платежную систему  можно оплатить с помощью:<BR><BR><BR>Интернет переводов<BR><BR><LO> <LI>Z-PAYMENT  <LI>Банковской пластиковой карты<BR><BR><BR></LO>Банковских переводов<BR><LO> <LI>Перевод в Райффайзенбанк  <LI>СберБанк РФ  <LI>ТелеБанк ВТБ24  <LI>Банковский перевод в USD  <LI>Банковский перевод в EURO <BR><BR><BR></LO>Денежных переводов по  России<BR><LO> <LI>Почтовый перевод в рублях  <LI>Перевод Anelik (RUR)  <LI>Быстрая почта Райффайзенбанк (RUR)  <LI>Перевод Аллюр (RUR)  <LI>Перевод Юнистрим (RUR)  <LI>Перевод Migom (RUR)  <LI>Western Union (RUR) <BR><BR><BR></LO>Денежных переводов из-за  границы<BR><LO> <LI>Перевод MoneyGram (USD)  <LI>Western Union (USD)  <LI>Western Union (EUR)<BR><BR><BR></LO>Наличных расчетов<BR><LO> <LI>Оплата наличными  <LI>Оплата через терминалы </LO>Оплата через терминалы<BR><LO> <LI>Терминалы Мультикассы (multikassa.com)<BR><BR><BR></LO>Мобильных  платежей<BR><LO> <LI>Оплата по SMS для абонентов Билайн  <LI>Оплата SMS в России  <LI>Оплата SMS в Казахстане  <LI>Оплата SMS в Таджикистане  <LI>Оплата SMS в Киргизии  <LI>Оплата SMS в Австрии  <LI>Оплата SMS в Бельгии  <LI>Оплата SMS в Болгарии  <LI>Оплата SMS в Германии  <LI>Оплата SMS в Голландии  <LI>Оплата SMS в Великобритании  <LI>Оплата SMS в Латвии  <LI>Оплата SMS в Дании  <LI>Оплата SMS в Венгрии  <LI>Оплата SMS в Израиле  <LI>Оплата SMS в Литве  <LI>Оплата SMS в Испании  <LI>Оплата SMS в Норвегии  <LI>Оплата SMS в Норвегии  <LI>Оплата SMS в Португалии  <LI>Оплата SMS в Польше  <LI>Оплата SMS в Франции  <LI>Оплата SMS в Финляндии  <LI>Оплата SMS в Эстонии  <LI>Оплата SMS в Чехии  <LI>Оплата SMS в Швеции </LO></LI>','e','[SETCURRENCY:RUR]  <H3 class=\"contentTitle\">Оплата заказа: - Z-PAYMENT</H3> <FORM id=\"pay_zpayment\" name=\"pay_zpayment\" action=https://z-payment.ru/merchant.php  method=post target=_blank> <TABLE cellSpacing=\"3\" cellPadding=\"0\" width=\"300\" border=\"0\">   <TBODY>   <TR>     <TD width=\"50%\">Номер заказа</TD>     <TD>[ORDER.ID]</TD></TR>   <TR>     <TD>Сумма платежа</TD>     <TD>[ORDER_SUMMA]</TD></TR>   <TR>    <TD>Комментарий к платежу </TD>    <TD>Заказ:[ORDER.ID] / Договор: [USER.ID]</TD></TR>  <TR>    <TD>Ваш e-mail </TD>    <TD><INPUT maxLength=120 size=25 name=\"CLIENT_MAIL\" value=\"[USER.USEREMAIL]\"></TD></TR>  <TR> <TD align=middle colSpan=2><INPUT class=\"buttonSend\" type=\"submit\" value=\"Продолжить\">    <INPUT type=\"hidden\" value=\"[PAYMENT.Z_PAY]\" name=\"LMI_PAYEE_PURSE\"> </TD><INPUT type=\"hidden\"      value=\"[ORDER.ID]\" name=\"LMI_PAYMENT_NO\"></TD> <INPUT type=\"hidden\"      value=\"[ORDER.SUMMA]\" name=\"LMI_PAYMENT_AMOUNT\"></TD> <INPUT type=\"hidden\"      value=\"Заказ:[ORDER.ID] / Договор: [USER.ID]\" name=\"LMI_PAYMENT_DESC\"></TD>    </TR></TBODY></TABLE></FORM> ','SE_PAYEXECUTE( SAMETEXT(\"MD5(\"[POST.LMI_PAYEE_PURSE][POST.LMI_PAYMENT_AMOUNT][POST.LMI_PAYMENT_NO][POST.LMI_MODE][POST.LMI_SYS_INVS_NO][POST.LMI_SYS_TRANS_NO][POST.LMI_SYS_TRANS_DATE][POST.LMI_SECRET_KEY][POST.LMI_PAYER_PURSE][POST.LMI_PAYER_WM]\")\",\"[POST.LMI_HASH]\"), [POST.LMI_PAYMENT_AMOUNT], [POST.LMI_SYS_INVS_NO], [POST.LMI_PAYEE_PURSE]) YES ','<ACCESS><INPUT type=\"hidden\" value=\"[POST.LMI_SYS_INVS_NO]\"  name=\"LMI_SYS_INVS_NO\"> <INPUT type=\"hidden\" value=\"[POST.LMI_SYS_TRANS_DATE]\"  name=\"LMI_SYS_TRANS_DATE\"> <INPUT type=\"hidden\" value=\"[POST.LMI_SYS_TRANS_NO]\"  name=\"LMI_SYS_TRANS_NO\"> </ACCESS> <H4>Оплата проведена успешно</H4><BR>Ваш заказ № [ORDER.ID] оплачен  <TABLE class=\"tableTable\" border=\"0\">  <TBODY class=\"tableBody\">   <TR>    <TD>Номер счета Z-PAYMENT:</TD>    <TD>[POST.LMI_SYS_INVS_NO]</TD></TR>  <TR>    <TD>Номер платежа в Z-PAYMENT:</TD>    <TD>[POST.LMI_SYS_TRANS_NO]</TD></TR>  <TR>    <TD>Дата и время платежа:</TD>    <TD>[POST.LMI_SYS_TRANS_DATE]</TD></TR></TBODY></TABLE> ','<ACCESS></ACCESS> <H4>Ошибка платежа Z-PAYMENT</H4><BR>Оплата заказа № [ORDER.ID] не проведена<BR><BR> ',1,NULL,NULL,NULL,'b',NULL,'N','N',NULL,0,'2014-06-10 08:59:23','0000-00-00 00:00:00'),(3,'rus',NULL,'payment_yand.jpg','Яндекс деньги','Оплата через систему интернет-платежей Яндекс.Деньги. Вам необходимо иметь свой собственный счет (кошелек) Яндекс.Деньги (<A  href=\"http://www.money.yandex.ru\" target=_blank>www.money.yandex.ru</A>). При  заказе через сайт Вы можете выбрать способ оплаты \"оплата Яндекс.Деньги\" и  оплата пройдёт в автоматическом режиме. Примечание: в момент оплаты кошелёк  должен быть запущен на Вашем компьютере.','e','[SETCURRENCY:RUR]  <H3 class=\"contentTitle\">Оплата заказа: - Яндекс деньги</H3>Будет произведен  платеж на счет: [PAYMENT.YAN_M] в сумме: [ORDER_SUMMA]<BR>Перед тем как  продолжить оплату, необходимо активизировать Ваш Yandex  Интернет-кошелек.<BR><BR><BR> <FORM action=http://money.yandex.ru/select-wallet.xml method=post><INPUT  type=\"hidden\" value=\"643\" name=\"TargetCurrency\"> <INPUT type=\"hidden\" value=\"643\"  name=\"currency\"> <INPUT type=\"hidden\" value=\"2\" name=\"wbp_Version\"> <INPUT type=\"hidden\"  value=\"DirectPaymentIntoAccountRequest\" name=\"wbp_MessageType\"> <INPUT type=\"hidden\"  value=\"mailto:[MAIN.ESUPPORT]\" name=\"wbp_ShopAddres\"> <INPUT type=\"hidden\"  value=\"[PAYMENT.YAN_M] \" name=\"wbp_accountid\"> <INPUT type=\"hidden\"  value=\"643;[ORDER.SUMMA]\" name=\"wbp_currencyamount\"> <INPUT type=\"hidden\"  value=\"Заказ No:[ORDER.ID]\" name=\"wbp_shoptdescription\"> <INPUT type=\"hidden\"  value=\"Заказ No:[ORDER.ID]\" name=\"wbp_template_1\"> <INPUT type=\"hidden\"  name=\"wbp_ShopErrorInfo\"> <INPUT class=\"buttonSend\" type=\"submit\" value=\"Продолжить\">  </FORM> ',NULL,NULL,NULL,1,NULL,NULL,NULL,'b',NULL,'N','N',NULL,0,'2014-06-10 08:56:43','0000-00-00 00:00:00'),(4,'rus',NULL,'payment_webm.jpg','WebMoney','Оплата через систему интернет-платежей Web Money (Веб Мани). Вам необходимо иметь свой собственный счет (кошелек) в системе интернет платежей WebMoney (<a href=\"www.webmoney.ru\" target=_blank>www.webmoney.ru</a>) При оформлении заказа на нашем сайте Вы можете выбрать \"оплата Web Money\" и оплата пройдёт в автоматическом режиме.','e','<H3 class=\"contentTitle\">Оплата заказа: - WebMoney</H3> <FORM name=\"frm\" method=post>Выберите вид валюты для оплаты: <SELECT  onchange=document.frm.submit(); name=\"valuts\"> <OPTION value=\"wm_z\" [SELECTED:wm_z]>WMZ<OPTION value=\"wm_r\" [SELECTED:wm_r]>WMR<OPTION value=\"wm_e\" [SELECTED:wm_e]>WME</OPTION></SELECT> <INPUT type=\"hidden\" value=\"[POST.FP]\"  name=\"FP\"> <INPUT type=\"hidden\" value=\"[ORDER.ID]\" name=\"ORDER_PAYEE\">  </FORM><BR><BR>Сумма к оплате: [ORDER_SUMMA]<BR><BR>Для выполения прямого  платежа на кошелек продавца нажмите кнопку \"Продолжить оплату\" и пройдите  авторизацию в системе. Вы увидите все детали платежа на экране и сможете  подтвердить или отменить платеж. Для выполнения платежа вы должны быть  зарегистрированы в системе <A href=\"http://www.webmoney.ru/\"  target=_blank>WebMoney  Transfer</A><BR><BR><BR>[SETCURRENCY:[IF(RUR=wm_r,USD=wm_z,EUR=wm_e:USD)]]  <FORM action=https://merchant.webmoney.ru/lmi/payment.asp method=post  target=_blank><INPUT type=\"hidden\" value=\"1\" name=\"LMI_MODE\"> <INPUT type=\"hidden\"  value=\"[PAYMENT.[POST.VALUTS:wm_z]]\" name=\"LMI_PAYEE_PURSE\"> <INPUT type=\"hidden\"  value=\"[ORDER.SUMMA]\" name=\"LMI_PAYMENT_AMOUNT\"> <INPUT type=\"hidden\"  value=\"Заказ:[ORDER.ID]\" name=\"LMI_PAYMENT_NO\"> <INPUT type=\"hidden\"  value=\"Заказ:[ORDER.ID] / Договор: [USER.ID]\" name=\"LMI_PAYMENT_DESC\"> <INPUT  type=\"hidden\" value=\"1\" name=\"LMI_SUCCESS_METHOD\"> <INPUT type=\"hidden\"  value=\"http://[THISNAMESITE]/modules/sshop_payment/result.php\"  name=\"LMI_RESULT_URL\"> <INPUT type=\"hidden\"  value=\"http://[THISNAMESITE]/modules/sshop_payment/success.php\"  name=\"LMI_SUCCESS_URL\"> <INPUT type=\"hidden\"  value=\"http://[THISNAMESITE]/modules/sshop_payment/fail.php\" name=\"LMI_FAIL_URL\">  <INPUT type=\"hidden\" value=\"0\" name=\"LMI_SIM_MODE\"> <INPUT type=\"hidden\"  value=\"http://[THISNAMESITE]/[PAGENAME]?razdel=[RAZDEL]>amp;sub=3\" name=\"PAGEPAYEE\">  <INPUT class=\"buttonSend\" type=\"submit\" value=\"Продолжить\">  </FORM> ','SE_PAYEXECUTE( SAMETEXT(\"MD5(\"[POST.LMI_PAYEE_PURSE][POST.LMI_PAYMENT_AMOUNT][POST.LMI_PAYMENT_NO][POST.LMI_MODE][POST.LMI_SYS_INVS_NO][POST.LMI_SYS_TRANS_NO][POST.LMI_SYS_TRANS_DATE][POST.LMI_SECRET_KEY][POST.LMI_PAYER_PURSE][POST.LMI_PAYER_WM]\")\",\"[POST.LMI_HASH]\"), [POST.LMI_PAYMENT_AMOUNT], [POST.LMI_SYS_INVS_NO], [POST.LMI_PAYEE_PURSE]) ','<ACCESS><INPUT type=\"hidden\" value=\"[POST.LMI_SYS_INVS_NO]\"  name=\"LMI_SYS_INVS_NO\"> <INPUT type=\"hidden\" value=\"[POST.LMI_SYS_TRANS_DATE]\"  name=\"LMI_SYS_TRANS_DATE\"> <INPUT type=\"hidden\" value=\"[POST.LMI_SYS_TRANS_NO]\"  name=\"LMI_SYS_TRANS_NO\"> </ACCESS> <H4>Оплата проведена успешно</H4><BR>Ваш заказ № [ORDER.ID] оплачен <TABLE class=\"tableTable\" border=\"0\"> <TBODY class=\"tableBody\"> <TR> <TD>Номер счета WebMoney Transfer:</TD> <TD>[POST.LMI_SYS_INVS_NO]</TD></TR> <TR> <TD>Номер платежа в WebMoney:</TD> <TD>[POST.LMI_SYS_TRANS_NO]</TD></TR> <TR> <TD>Дата и время платежа:</TD> <TD>[POST.LMI_SYS_TRANS_DATE]</TD></TR></TBODY></TABLE> ','<ACCESS> </ACCESS> <H4>Ошибка платежа WebMoney</H4><BR> Оплата заказа № [ORDER.ID] не проведена<BR> <BR> ',1,NULL,NULL,NULL,'b',NULL,'N','N',NULL,0,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(5,'rus',NULL,'payment_rbk.jpg','RBK Money','RBK Money – это электронная платежная система, с помощью которой Вы  сможете совершать платежи с персонального компьютера, коммуникатора или  мобильного телефона. Для того, чтобы оплатить услуги достаточно войти в свой  электронный кошелек и сделать пару кликов  <TABLE cellSpacing=\"0\" cellPadding=\"2\" width=\"100%\" border=\"0\"> <TBODY> <TR> <TD width=\"638\">   <H3>Широкий выбор способов оплаты.</H3>   <P>В Вашем распоряжении широкий спектр платежных инструментов для оплаты    товаров и услуг: </P>   <UL>     <LI>электронные кошельки RBK Money;      <LI>Яндекс.Деньги;      <LI>платежные терминалы крупнейших федеральных сетей;      <LI>     <P>системы денежных переводов «CONTACT» и «Юнистрим».</P></LI></UL>   <P>24 часа в сутки Вы можете оплатить предлагаемые нами товары как с    помощью электронного кошелька, так и напрямую – выписав счет и оплатив его    в любом банке или терминале самообслуживания.</P>   <H3>Онлайн-консультации по вопросам оплаты.</H3>   <P>В любое время суток Вы, можете обратиться в службу поддержки RBK Money,    которая работает в режиме 24х7х365 без перерывов и выходных. RBK Money    гарантирует быстрый и исчерпывающий ответ по е-mail, телефону или в    круглосуточном онлайн-чате.</P>   <H3><BR></H3></TD></TR></TBODY></TABLE>','e','[SETCURRENCY:RUR]  <H4>Оплата заказа [ORDER.ID] через платежную систему RBK Money</H4>Сумма заказа:  [ORDER_SUMMA]  <FORM name=\"pay\" action=\" https://rbkmoney.ru/processpurchase.aspx\"  method=post><INPUT type=\"hidden\" value=\"[PAYMENT.RUPAY]\" name=\"pay_id\"> <INPUT  type=\"hidden\" value=\"[ORDER.SUMMA]\" name=\"sum_pol\"> <INPUT type=\"hidden\"  value=\"Оплата заказа No: [ORDER.ID]\" name=\"name_service\"> <INPUT type=\"hidden\"  value=\"[ORDER.ACCOUNT]\" name=\"order_id\"> <INPUT type=\"hidden\" value=\"810\"  name=\"currencycode\"> <INPUT type=\"hidden\"  value=\"http://[THISNAMESITE]/modules/shop_payment/success.php\"  name=\" success_url \"> <INPUT type=\"hidden\"  value=\"http://[THISNAMESITE]/modules/shop_payment/fail.php\" name=\" fail_url \">  <INPUT type=\"hidden\" value=\"[CLIENTNAME]\" name=\"user_name\"> <INPUT type=\"hidden\"  value=\"[USER.USEREMAIL]\" name=\"user_email\"> <INPUT type=\"submit\" value=\"оплатить\" name=\"button\"> </FORM> ','SE_PAYEXECUTE(SAMETEXT(\"MD5(\"[POST.rupay_action]::[POST.rupay_site_id]::[POST.rupay_order_id]::[POST.rupay_sum]::[POST.rupay_id]::[POST.rupay_data]::[POST.rupay_status]::dFrt34hg67\")\",\"[POST.rupay_hash]\"),[POST.rupay_sum],[POST.rupay_id],RuPay)','<ACCESS><INPUT type=\"hidden\" value=\"[POST.rupay_id]\" name=\"rupay_id\"> <INPUT  type=\"hidden\" value=\"[POST.rupay_data]\" name=\"rupay_data\"> <INPUT type=\"hidden\"  value=\"[POST.rupay_status]\" name=\"rupay_status\"> </ACCESS> <H4>Оплата проведена успешно</H4><BR>Ваш заказ № [ORDER.ID] оплачен  ','<ACCESS></ACCESS> <H4>Ошибка платежа RBK Money</H4><BR>Оплата заказа № [ORDER.ID] не проведена<BR><BR> ',1,NULL,NULL,NULL,'b',NULL,'N','N',NULL,0,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(6,'rus',NULL,'payment_sbr.jpg','Сбербанк (для физических лиц)','<P>1. Распечатайте квитанцию и оплатите через ближайший банк.<BR>2. Для  оперативности получения продукта, отправьте сканированную (разрешением не менее  150dpi) копию заверенной квитанции об оплате на <A  href=\"mailto: [MAIN.ESUPPORT]\">[MAIN.ESUPPORT]</A><BR>3. Ожидайте письмо с  подтверждением;<BR>4. На странице \"Ваш заказ\" Вы можете посмотреть статус заказа  и доставки;<BR></P> ','p','<FONT face=Arial><FONT size=1>[SETCURRENCY:RUR] <SCRIPT language=javascript>     function saveAs (pic1) {               document.execCommand(\'SaveAs\',true,\'blank.html\');               if (typeof pic1 == \'object\')              pic1 = pic1.src;               window.win = open (pic1);               setTimeout(\'win.document.execCommand(\"SaveAs\")\', 500);               setTimeout(\'win.close();\', 500);     } </SCRIPT> <STYLE> .n6 {FONT-SIZE: 6pt; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica} .t10,.t11 {FONT-SIZE: 10pt; FONT-FAMILY: \"Times New Roman\", Times, serif} .n7 {FONT-SIZE: 7pt; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica} .b12 {FONT-SIZE: 10pt; BORDER-LEFT-COLOR: black; BORDER-BOTTOM-WIDTH: thin; BORDER-BOTTOM-COLOR: black; CURSOR: hand; BORDER-TOP-COLOR: black; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica; BORDER-RIGHT-COLOR: black} .b10 {BORDER-RIGHT: black 0px solid; BORDER-TOP: black 0px solid; FONT-WEIGHT: bold; FONT-SIZE: 10pt; BORDER-LEFT: black 0px solid; CURSOR: hand; BORDER-BOTTOM: black 1px solid; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica} .n10 {BORDER-RIGHT: black 0px solid; BORDER-TOP: black 0px solid; FONT-WEIGHT: normal; FONT-SIZE: 10pt; BORDER-LEFT: black 0px solid; CURSOR: hand; BORDER-BOTTOM: black 1px solid; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica} .b10i {BORDER-RIGHT: black 0px solid; BORDER-TOP: black 0px solid; FONT-WEIGHT: bold; FONT-SIZE: 10pt; BORDER-LEFT: black 0px solid; CURSOR: hand; BORDER-BOTTOM: black 1px solid; FONT-STYLE: italic; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica} .t10n {FONT-WEIGHT: normal; FONT-SIZE: 10pt; FONT-FAMILY: \"Times New Roman\", Times, serif} .n10_ {FONT-WEIGHT: normal; FONT-SIZE: 10pt; BORDER-LEFT-COLOR: black; BORDER-BOTTOM-COLOR: #000000; CURSOR: hand; BORDER-TOP-COLOR: black; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica; BORDER-RIGHT-COLOR: black} FONT {FONT-SIZE: 9pt; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica, sans-serif} .smf {FONT-SIZE: 7pt; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica, sans-serif; TEXT-ALIGN: justify} .brd {BORDER-RIGHT: #000000 1px dotted; BORDER-TOP: #000000 1px dotted; BORDER-LEFT: #000000 1px dotted; BORDER-BOTTOM: #000000 1px dotted}</STYLE> </FONT></FONT> <P align=right><A onclick=\"saveAs(\'stamp.gif\'); return false;\"  href=\"javascript:%20void%200;\"><FONT face=Arial size=1><IMG height=\"17\"  alt=\"Сохранить страницу\" src=\"disk.gif\" width=\"17\" border=\"0\"  name=\"disk\"></FONT></A><FONT face=Arial size=1> </FONT><A  href=\"javascript:window.print();\"><FONT face=Arial size=1><IMG height=\"17\"  alt=\"Печать страницы\" src=\"print.gif\" width=\"17\" border=\"0\"  name=\"print\"></FONT></A></P> <TABLE height=\"99%\" cellSpacing=\"0\" cellPadding=\"0\" width=\"99%\" border=\"0\"> <TBODY> <TR> <TD width=\"33%\" height=\"33%\"><FONT face=Arial size=1>&nbsp;</FONT></TD> <TD width=\"33%\" height=\"33%\"><FONT face=Arial size=1>&nbsp;</FONT></TD> <TD width=\"33%\" height=\"33%\"><FONT face=Arial size=1>&nbsp;</FONT></TD></TR> <TR> <TD width=\"33%\" height=\"33%\"><FONT face=Arial size=1>&nbsp;</FONT></TD> <TD borderColor=#000000 width=\"33%\" height=\"33%\">   <DIV align=center>   <TABLE borderColor=#999999 height=\"100\" cellSpacing=\"0\" cellPadding=\"0\"        width=\"100\" border=\"1\">     <TBODY>     <TR>       <TD>         <DIV align=center>         <TABLE cellSpacing=\"0\" cellPadding=\"0\" border=\"0\">           <TBODY>           <TR>             <TD colSpan=4>               <DIV  style=\"WIDTH: 191px; HEIGHT: 30px; BACKGROUND-COLOR: #ffffff\"></DIV></TD>             <TD class=\"t10\">               <DIV align=right><FONT face=Arial size=1>Форма ПД-4</FONT></DIV></TD>             <TD>               <DIV style=\"WIDTH: 14px; HEIGHT: 30px; BACKGROUND-COLOR: #ffffff\"></DIV></TD></TR>           <TR>             <TD rowSpan=35>               <DIV style=\"WIDTH: 34px; HEIGHT: 492px; BACKGROUND-COLOR: #ffffff\"></DIV></TD>             <TD class=\"t11\" vAlign=bottom>               <DIV align=center><FONT face=Arial size=1>Извещение</FONT></DIV></TD>             <TD rowSpan=17><IMG style=\"BACKGROUND-COLOR: #000000\" height=\"242\" src=\"lineblace.gif\" width=\"4\"></TD>             <TD rowSpan=17>               <DIV style=\"WIDTH: 6px; HEIGHT: 242px; BACKGROUND-COLOR: #ffffff\"></DIV></TD>             <TD class=\"b10\" vAlign=bottom>               <DIV align=center><FONT size=1>[MAIN.COMPANY]&nbsp;[PAYMENT.INN] / [PAYMENT.KPP]</FONT></DIV></TD>             <TD rowSpan=35>               <DIV style=\"WIDTH: 14px; HEIGHT: 492px; BACKGROUND-COLOR: #ffffff\"></DIV></TD></TR>           <TR>             <TD rowSpan=14>               <DIV style=\"WIDTH: 147px; HEIGHT: 199px; BACKGROUND-COLOR: #ffffff\"></DIV></TD>             <TD class=\"n6\" vAlign=top height=\"10\">               <DIV align=center><FONT size=1>(наименование и ИНН / КПП получателя платежа)</FONT></DIV></TD></TR>           <TR>             <TD class=\"b10\" vAlign=bottom>               <DIV align=center><FONT size=1>р/сч. [PAYMENT.RS]</FONT></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(номер счета получателя                платежа)</FONT></DIV></TD></TR>           <TR>             <TD class=\"b10\" vAlign=bottom>               <DIV align=center><FONT size=1>[PAYMENT.BANK]</FONT></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(наименование банка получателя платежа)</FONT></DIV></TD></TR>           <TR>             <TD class=\"b10\" vAlign=bottom>               <DIV align=center><FONT size=1>к/сч. [PAYMENT.KS], БИК                [PAYMENT.BIK]</FONT></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(банковские реквизиты                получателя платежа)</FONT></DIV></TD></TR>           <TR></TR>           <TR>             <TD class=\"b10\" vAlign=top>               <DIV align=center><FONT size=1>Оплата заказа&nbsp; [ORDER.ID]                от&nbsp; [FORMATDATE,[ORDER.DATE_ORDER],\'d. m.Y\']                г.</FONT></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(наименование платежа)</FONT></DIV></TD></TR>           <TR>             <TD class=\"n10\" vAlign=bottom>               <DIV align=center><FONT size=1><STRONG>[CLIENTNAME]</STRONG></FONT></DIV></TD></TR>           <TR>             <TD class=\"n10\" vAlign=top>               <DIV align=center>               <DIV align=center><FONT size=1>паспорт серия&nbsp;[USER.DOC_SER], №                [USER.DOC_NUM],выдан</FONT></DIV></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(Ф.И.О. и адрес плательщика)</FONT></DIV></TD></TR>           <TR>             <TD class=\"t10\" vAlign=bottom>               <DIV><FONT face=Arial size=1>&nbsp;&nbsp;Дата: [CURDATE] Сумма                платежа: [ORDER_SUMMA]</FONT></DIV></TD></TR>           <TR>             <TD class=\"t10\">               <DIV align=center><FONT face=Arial size=1>Кассир</FONT></DIV></TD>             <TD class=\"t10\" vAlign=bottom>               <DIV align=center><FONT face=Arial><FONT size=1>Плательщик                <SPAN class=\"t10n\">(подпись):                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></FONT></FONT></DIV></TD></TR>           <TR>             <TD>               <DIV style=\"FONT-SIZE: 0pt; WIDTH: 147px; HEIGHT: 3px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD>             <TD>               <DIV style=\"FONT-SIZE: 0pt; WIDTH: 295px; HEIGHT: 3px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD></TR>           <TR>             <TD colSpan=4><IMG style=\"BACKGROUND-COLOR: #000000\" height=\"4\"                    src=\"lineblace.gif\" width=\"452\"></TD></TR>           <TR>             <TD>               <DIV style=\"WIDTH: 147px; HEIGHT: 8px; BACKGROUND-COLOR: #ffffff\"></DIV></TD>             <TD rowSpan=17><IMG style=\"BACKGROUND-COLOR: #000000\" height=\"246\" src=\"lineblace.gif\" width=\"4\"></TD>             <TD rowSpan=17>               <DIV style=\"FONT-SIZE: 0pt; WIDTH: 6px; HEIGHT: 246px; BACKGROUND-COLOR: #ffffff\"></DIV></TD>             <TD>               <DIV style=\"FONT-SIZE: 0pt; WIDTH: 295px; HEIGHT: 8px; BACKGROUND-COLOR: #ffffff\"></DIV></TD></TR>           <TR>             <TD class=\"n7\" vAlign=top>               <DIV align=center><FONT size=1>место для круглой печати</FONT></DIV></TD>             <TD class=\"b10\" vAlign=bottom>               <DIV align=center><FONT size=1>[MAIN.COMPANY]&nbsp;&nbsp;[PAYMENT.INN] / [PAYMENT.KPP]</FONT></DIV></TD></TR>           <TR>             <TD rowSpan=11>               <DIV style=\"FONT-SIZE: 0pt; WIDTH: 147px; HEIGHT: 150px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(наименование и ИНН / КПП получателя платежа)</FONT></DIV></TD></TR>           <TR>             <TD class=\"b10\" vAlign=bottom>               <DIV align=center><FONT size=1>р/сч. [PAYMENT.RS]</FONT></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(номер счета получателя платежа)</FONT></DIV></TD></TR>           <TR>             <TD class=\"b10\" vAlign=bottom>               <DIV align=center><FONT size=1>[PAYMENT.BANK]</FONT></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(наименование банка получателя платежа)</FONT></DIV></TD></TR>           <TR>             <TD class=\"b10\" vAlign=bottom>               <DIV align=center><FONT size=1>к/сч. [PAYMENT.KS], БИК [PAYMENT.BIK]</FONT></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(банковские реквизиты получателя платежа)</FONT></DIV></TD></TR>           <TR></TR>           <TR>             <TD class=\"b10\" vAlign=top>               <DIV align=center><FONT size=1>Оплата заказа [ORDER.ID] от&nbsp; [FORMATDATE,[ORDER.DATE_ORDER],\'d. m.Y\']                г.</FONT></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(наименование                платежа)</FONT></DIV></TD></TR>           <TR>             <TD class=\"n10\" vAlign=bottom>               <DIV align=center>               <DIV align=center><FONT                    size=1><STRONG>[CLIENTNAME]</STRONG></FONT></DIV></DIV></TD></TR>           <TR>             <TD class=\"t11\" vAlign=top>               <DIV align=center><FONT face=Arial                    size=1>Квитанция</FONT></DIV></TD>             <TD class=\"n10\" vAlign=top>               <DIV align=center><FONT size=1>               <DIV align=center>паспорт серия&nbsp;[USER.DOC_SER], №                [USER.DOC_NUM],выдан</DIV></FONT></DIV></TD></TR>           <TR>             <TD><FONT face=Arial size=1>&nbsp;</FONT></TD>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(Ф.И.О. и адрес                плательщика)</FONT></DIV></TD></TR>           <TR>             <TD class=\"t10\" vAlign=bottom height=\"20\">               <DIV align=center><FONT face=Arial                  size=1>Кассир</FONT></DIV></TD>             <TD class=\"t10\" vAlign=bottom height=\"20\">               <DIV><FONT face=Arial size=1>&nbsp;&nbsp;Дата: [CURDATE] Сумма                платежа: [ORDER_SUMMA]</FONT></DIV></TD></TR>           <TR>             <TD>               <DIV                    style=\"FONT-SIZE: 0pt; WIDTH: 147px; HEIGHT: 20px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD>             <TD class=\"t10\" vAlign=bottom>               <DIV align=center><FONT face=Arial><FONT size=1>Плательщик                <SPAN class=\"t10n\">(подпись):                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></FONT></FONT></DIV></TD></TR>           <TR>             <TD colSpan=4>               <DIV style=\"FONT-SIZE: 0pt; WIDTH: 91px; HEIGHT: 3px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD>             <TD>               <DIV style=\"FONT-SIZE: 0pt; WIDTH: 295px; HEIGHT: 3px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD>             <TD>               <DIV style=\"FONT-SIZE: 0pt; WIDTH: 14px; HEIGHT: 3px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD></TR>           <TR>             <TD colSpan=6>               <DIV style=\"FONT-SIZE: 0pt; WIDTH: 500px; HEIGHT: 18px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial          size=1></FONT></DIV></TD></TR></TBODY></TABLE></DIV></TD></TR></TBODY></TABLE></DIV></TD> <TD width=\"33%\" height=\"33%\"><FONT face=Arial size=1>&nbsp;</FONT></TD></TR> <TR> <TD width=\"33%\" height=\"33%\"><FONT face=Arial size=1>&nbsp;</FONT></TD> <TD class=\"n7\" vAlign=top width=\"33%\" height=\"33%\">   <DIV align=center><FONT size=1><BR></FONT></DIV></TD> <TD width=\"33%\" height=\"33%\"><FONT face=Arial size=1>&nbsp;</FONT></TD></TR></TBODY></TABLE> ',NULL,NULL,'1. Распечатайте квитанцию и оплатите в любом ближайшем Банке.<BR>2. Для  оперативности получения продукта, отправьте сканированную (разрешением не менее  150dpi) копию заверенной квитанции об оплате на <A  href=\"mailto:[MAIN.ESUPPORT]\">[MAIN.ESUPPORT]</A>&nbsp;<BR>4. Ожидайте письмо с  подтверждением;<BR>5. На странице \"Ваш заказ\" Вы можете мосмотреть статус заказа  и доставки;<BR> ',1,NULL,NULL,NULL,'b',NULL,'N','N',NULL,0,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(7,'rus',NULL,'payment_blic.jpg','Денежный перевод \"БЛИЦ\" Сбербанка России','Срочные денежные переводы Блиц. Доступны везде! <LO> <LI>в рублях;  <LI>без открытия счета;  <LI>в любом отделении Сбербанка России;  <LI>деньги поступают в течение 1 часа; <li>комиссия: 1,75% от суммы, min</lo> 100 руб.<BR><BR>Как отправить Блиц-перевод:<BR><BR> <OL> <LI>Обратиться в филиал Сбербанка России.  <LI>Предъявить паспорт.  <LI>Сообщить ФИО и данные паспорта получателя (делайте запрос через сайт, либо  по телефону: +7 (XXX) XXX-XX-XX&nbsp;&nbsp;&nbsp;&nbsp;и мы Вам сообщим данные для  перевода).  <LI>Оплатить перевод и получить контрольный номер (указан в квитанции – 6-7 цифр).  <LI>Позвонить по телефону:&nbsp;указанные выше&nbsp;и сообщить контрольный  номер Блиц-перевода.  <LI>Заказ Вам будет выслан в этот же день. В этом главное преимущество системы  \"Блиц\" перевода.</LI></OL></LI>','p','<FONT face=Arial><FONT size=1>[SETCURRENCY:RUR] <SCRIPT language=javascript>     function saveAs (pic1) {               document.execCommand(\'SaveAs\',true,\'blank.html\');               if (typeof pic1 == \'object\')              pic1 = pic1.src;               window.win = open (pic1);               setTimeout(\'win.document.execCommand(\"SaveAs\")\', 500);               setTimeout(\'win.close();\', 500);     } </SCRIPT> <STYLE> .n6 {   FONT-SIZE: 6pt; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica } .t10 {   FONT-SIZE: 10pt; FONT-FAMILY: \"Times New Roman\", Times, serif } .t11 {   FONT-SIZE: 10pt; FONT-FAMILY: \"Times New Roman\", Times, serif } .n7 {  FONT-SIZE: 7pt; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica } .b12 {  FONT-SIZE: 10pt; BORDER-LEFT-COLOR: black; BORDER-BOTTOM-WIDTH: thin; BORDER-BOTTOM-COLOR: black; CURSOR: hand; BORDER-TOP-COLOR: black; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica; BORDER-RIGHT-COLOR: black } .b10 {  BORDER-RIGHT: black 0px solid; BORDER-TOP: black 0px solid; FONT-WEIGHT: bold; FONT-SIZE: 10pt; BORDER-LEFT: black 0px solid; CURSOR: hand; BORDER-BOTTOM: black 1px solid; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica } .n10 {  BORDER-RIGHT: black 0px solid; BORDER-TOP: black 0px solid; FONT-WEIGHT: normal; FONT-SIZE: 10pt; BORDER-LEFT: black 0px solid; CURSOR: hand; BORDER-BOTTOM: black 1px solid; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica } .b10i {  BORDER-RIGHT: black 0px solid; BORDER-TOP: black 0px solid; FONT-WEIGHT: bold; FONT-SIZE: 10pt; BORDER-LEFT: black 0px solid; CURSOR: hand; BORDER-BOTTOM: black 1px solid; FONT-STYLE: italic; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica } .t10n {  FONT-WEIGHT: normal; FONT-SIZE: 10pt; FONT-FAMILY: \"Times New Roman\", Times, serif } .n10_ {  FONT-WEIGHT: normal; FONT-SIZE: 10pt; BORDER-LEFT-COLOR: black; BORDER-BOTTOM-COLOR: #000000; CURSOR: hand; BORDER-TOP-COLOR: black; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica; BORDER-RIGHT-COLOR: black } FONT {  FONT-SIZE: 9pt; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica, sans-serif } .smf {  FONT-SIZE: 7pt; FONT-FAMILY: Arial, \"Arial Cyr\", Helvetica, sans-serif; TEXT-ALIGN: justify } .brd {   BORDER-RIGHT: #000000 1px dotted; BORDER-TOP: #000000 1px dotted; BORDER-LEFT: #000000 1px dotted; BORDER-BOTTOM: #000000 1px dotted }</STYLE> </FONT></FONT> <P align=right><A onclick=\"saveAs(\'stamp.gif\'); return false;\"  href=\"javascript:%20void%200;\"><FONT face=Arial size=1><IMG height=\"17\"  alt=\"Сохранить страницу\" src=\"disk.gif\" width=\"17\" border=\"0\"  name=\"disk\"></FONT></A><FONT face=Arial size=1> </FONT><A  href=\"javascript:window.print();\"><FONT face=Arial size=1><IMG height=\"17\"  alt=\"Печать страницы\" src=\"print.gif\" width=\"17\" border=\"0\"  name=\"print\"></FONT></A></P> <TABLE height=\"99%\" cellSpacing=\"0\" cellPadding=\"0\" width=\"99%\" border=\"0\"> <TBODY> <TR> <TD width=\"33%\" height=\"33%\"><FONT face=Arial size=1>&nbsp;</FONT></TD> <TD width=\"33%\" height=\"33%\"><FONT face=Arial size=1>&nbsp;</FONT></TD> <TD width=\"33%\" height=\"33%\"><FONT face=Arial size=1>&nbsp;</FONT></TD></TR> <TR> <TD width=\"33%\" height=\"33%\"><FONT face=Arial size=1>&nbsp;</FONT></TD> <TD borderColor=#000000 width=\"33%\" height=\"33%\">   <DIV align=center>   <TABLE borderColor=#999999 height=\"100\" cellSpacing=\"0\" cellPadding=\"0\"        width=\"100\" border=\"1\">     <TBODY>     <TR>       <TD>         <DIV align=center>         <TABLE cellSpacing=\"0\" cellPadding=\"0\" border=\"0\">           <TBODY>           <TR>             <TD colSpan=4>               <DIV                    style=\"WIDTH: 191px; HEIGHT: 30px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD>             <TD class=\"t10\">               <DIV align=right><FONT face=Arial size=1>Форма                ПД-4</FONT></DIV></TD>             <TD>               <DIV                    style=\"WIDTH: 14px; HEIGHT: 30px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD></TR>           <TR>             <TD rowSpan=35>               <DIV                    style=\"WIDTH: 34px; HEIGHT: 492px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD>             <TD class=\"t11\" vAlign=bottom>               <DIV align=center><FONT face=Arial                    size=1>Извещение</FONT></DIV></TD>             <TD rowSpan=17><IMG style=\"BACKGROUND-COLOR: #000000\"                    height=\"242\" src=\"lineblace.gif\" width=\"4\"></TD>             <TD rowSpan=17>               <DIV                    style=\"WIDTH: 6px; HEIGHT: 242px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD>             <TD class=\"b10\" vAlign=bottom>               <DIV align=center><FONT                    size=1>[MAIN.COMPANY]&nbsp;&nbsp;&nbsp; [PAYMENT.INN] /                [PAYMENT.KPP]</FONT></DIV></TD>             <TD rowSpan=35>               <DIV                    style=\"WIDTH: 14px; HEIGHT: 492px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD></TR>           <TR>             <TD rowSpan=14>               <DIV                    style=\"WIDTH: 147px; HEIGHT: 199px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD>             <TD class=\"n6\" vAlign=top height=\"10\">               <DIV align=center><FONT size=1>(наименование и ИНН / КПП                получателя платежа)</FONT></DIV></TD></TR>           <TR>             <TD class=\"b10\" vAlign=bottom>               <DIV align=center><FONT size=1>р/сч.              [PAYMENT.RS]</FONT></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(номер счета получателя                платежа)</FONT></DIV></TD></TR>           <TR>             <TD class=\"b10\" vAlign=bottom>               <DIV align=center><FONT                size=1>[PAYMENT.BANK]</FONT></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(наименование банка получателя                платежа)</FONT></DIV></TD></TR>           <TR>             <TD class=\"b10\" vAlign=bottom>               <DIV align=center><FONT size=1>к/сч. [PAYMENT.KS], БИК                [PAYMENT.BIK]</FONT></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(банковские реквизиты                получателя платежа)</FONT></DIV></TD></TR>           <TR></TR>           <TR>             <TD class=\"b10\" vAlign=top>               <DIV align=center><FONT size=1>Оплата по Договору [CONTRACT]                от&nbsp; [FORMATDATE,[ORDER.DATE_ORDER],\'d. m.Y\']                г.</FONT></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(наименование                платежа)</FONT></DIV></TD></TR>           <TR>             <TD class=\"n10\" vAlign=bottom>               <DIV align=center><FONT                    size=1><STRONG>[CLIENTNAME]</STRONG></FONT></DIV></TD></TR>           <TR>             <TD class=\"n10\" vAlign=top>               <DIV align=center>               <DIV align=center><FONT size=1>паспорт                серия&nbsp;[USER.DOC_SER], №                [USER.DOC_NUM],выдан</FONT></DIV></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(Ф.И.О. и адрес                плательщика)</FONT></DIV></TD></TR>           <TR>             <TD class=\"t10\" vAlign=bottom>               <DIV><FONT face=Arial size=1>&nbsp;&nbsp;Дата: [CURDATE] Сумма                платежа: [ORDER_SUMMA]</FONT></DIV></TD></TR>           <TR>             <TD class=\"t10\">               <DIV align=center><FONT face=Arial                  size=1>Кассир</FONT></DIV></TD>             <TD class=\"t10\" vAlign=bottom>               <DIV align=center><FONT face=Arial><FONT size=1>Плательщик                <SPAN class=\"t10n\">(подпись):                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></FONT></FONT></DIV></TD></TR>           <TR>             <TD>               <DIV                    style=\"FONT-SIZE: 0pt; WIDTH: 147px; HEIGHT: 3px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD>             <TD>               <DIV                    style=\"FONT-SIZE: 0pt; WIDTH: 295px; HEIGHT: 3px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD></TR>           <TR>             <TD colSpan=4><IMG style=\"BACKGROUND-COLOR: #000000\" height=\"4\"                    src=\"lineblace.gif\" width=\"452\"></TD></TR>           <TR>             <TD>               <DIV                    style=\"WIDTH: 147px; HEIGHT: 8px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD>             <TD rowSpan=17><IMG style=\"BACKGROUND-COLOR: #000000\"                    height=\"246\" src=\"lineblace.gif\" width=\"4\"></TD>             <TD rowSpan=17>               <DIV                    style=\"FONT-SIZE: 0pt; WIDTH: 6px; HEIGHT: 246px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD>             <TD>               <DIV                    style=\"FONT-SIZE: 0pt; WIDTH: 295px; HEIGHT: 8px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD></TR>           <TR>             <TD class=\"n7\" vAlign=top>               <DIV align=center><FONT size=1>место для круглой                печати</FONT></DIV></TD>             <TD class=\"b10\" vAlign=bottom>               <DIV align=center><FONT size=1>[MAIN.COMPANY]&nbsp;&nbsp;                [PAYMENT.INN] / [PAYMENT.KPP]</FONT></DIV></TD></TR>           <TR>             <TD rowSpan=11>               <DIV                    style=\"FONT-SIZE: 0pt; WIDTH: 147px; HEIGHT: 150px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(наименование и ИНН / КПП                получателя платежа)</FONT></DIV></TD></TR>           <TR>             <TD class=\"b10\" vAlign=bottom>               <DIV align=center><FONT size=1>р/сч.              [PAYMENT.RS]</FONT></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(номер счета получателя                платежа)</FONT></DIV></TD></TR>           <TR>             <TD class=\"b10\" vAlign=bottom>               <DIV align=center><FONT                size=1>[PAYMENT.BANK]</FONT></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(наименование банка получателя                платежа)</FONT></DIV></TD></TR>           <TR>             <TD class=\"b10\" vAlign=bottom>               <DIV align=center><FONT size=1>к/сч. [PAYMENT.KS], БИК                [PAYMENT.BIK]</FONT></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(банковские реквизиты                получателя платежа)</FONT></DIV></TD></TR>           <TR></TR>           <TR>             <TD class=\"b10\" vAlign=top>               <DIV align=center><FONT size=1>Оплата по Договору [CONTRACT]                от&nbsp; [FORMATDATE,[ORDER.DATE_ORDER],\'d. m.Y\']                г.</FONT></DIV></TD></TR>           <TR>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(наименование                платежа)</FONT></DIV></TD></TR>           <TR>             <TD class=\"n10\" vAlign=bottom>               <DIV align=center>               <DIV align=center><FONT                    size=1><STRONG>[CLIENTNAME]</STRONG></FONT></DIV></DIV></TD></TR>           <TR>             <TD class=\"t11\" vAlign=top>               <DIV align=center><FONT face=Arial                    size=1>Квитанция</FONT></DIV></TD>             <TD class=\"n10\" vAlign=top>               <DIV align=center><FONT size=1>               <DIV align=center>паспорт серия&nbsp;[USER.DOC_SER], №                [USER.DOC_NUM],выдан</DIV></FONT></DIV></TD></TR>           <TR>             <TD><FONT face=Arial size=1>&nbsp;</FONT></TD>             <TD class=\"n6\" vAlign=top>               <DIV align=center><FONT size=1>(Ф.И.О. и адрес                плательщика)</FONT></DIV></TD></TR>           <TR>             <TD class=\"t10\" vAlign=bottom height=\"20\">               <DIV align=center><FONT face=Arial                  size=1>Кассир</FONT></DIV></TD>             <TD class=\"t10\" vAlign=bottom height=\"20\">               <DIV><FONT face=Arial size=1>&nbsp;&nbsp;Дата: [CURDATE] Сумма                платежа: [ORDER_SUMMA]</FONT></DIV></TD></TR>           <TR>             <TD>               <DIV                    style=\"FONT-SIZE: 0pt; WIDTH: 147px; HEIGHT: 20px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD>             <TD class=\"t10\" vAlign=bottom>               <DIV align=center><FONT face=Arial><FONT size=1>Плательщик                <SPAN class=\"t10n\">(подпись):                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></FONT></FONT></DIV></TD></TR>           <TR>             <TD colSpan=4>               <DIV                    style=\"FONT-SIZE: 0pt; WIDTH: 91px; HEIGHT: 3px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD>             <TD>               <DIV                    style=\"FONT-SIZE: 0pt; WIDTH: 295px; HEIGHT: 3px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD>             <TD>               <DIV                    style=\"FONT-SIZE: 0pt; WIDTH: 14px; HEIGHT: 3px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial size=1></FONT></DIV></TD></TR>           <TR>             <TD colSpan=6>               <DIV                    style=\"FONT-SIZE: 0pt; WIDTH: 500px; HEIGHT: 18px; BACKGROUND-COLOR: #ffffff\"><FONT                    face=Arial          size=1></FONT></DIV></TD></TR></TBODY></TABLE></DIV></TD></TR></TBODY></TABLE></DIV></TD> <TD width=\"33%\" height=\"33%\"><FONT face=Arial size=1>&nbsp;</FONT></TD></TR> <TR> <TD width=\"33%\" height=\"33%\"><FONT face=Arial size=1>&nbsp;</FONT></TD> <TD class=\"n7\" vAlign=top width=\"33%\" height=\"33%\">   <DIV align=center><FONT size=1><BR></FONT></DIV></TD> <TD width=\"33%\" height=\"33%\"><FONT face=Arial    size=1>&nbsp;</FONT></TD></TR></TBODY></TABLE> ',NULL,NULL,NULL,1,NULL,NULL,NULL,'b',NULL,'N','N',NULL,0,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(8,'rus',NULL,NULL,'Наличными в офисе компании',NULL,'e','<p><strong><font size=\"4\">Спасибо за заказ.</font></strong></p>\r\n<p><strong><font size=\"4\">Ожидайте обработки заказа менеджером.</font></strong></p>',NULL,NULL,NULL,1,NULL,NULL,'N','b',NULL,'Y','N',NULL,0,'2014-11-20 16:04:01','2014-11-20 16:04:01'),(9,'ukr',NULL,NULL,'Банк','Заказ № [ID_ORDER]<br>Сумма оплаты:[SUMM_ORDER:RUR]<br> <br>1. Для оплаты по безналичному расчету внесите&nbsp; <A href=\"/rekvcompany\" target=_blank>реквизиты Вашего предприятия</A>, и  нажмите на кнопку \"Выписать счет\"; (Проверьте) <BR> 2. Оплатите счет;<BR> 3. Для оперативности получения продукта, отправьте сканированную (разрешением не менее  150dpi) копию заверенной квитанции об оплате на  <A href=\"mailto:[MAIN_EMAIL]\">[MAIN_EMAIL]</A>&nbsp;или на факс [MAIN_FAX]<BR>4. Ожидайте письмо с подтверждением;<BR>5. На странице \"Мои  заказы\" войдите в № заказа, в котором Вам будет предоставлены ссылка для  скачивания программы и коды доступа для активизации;<BR>6. Документы (Договор,  Счет-фактура и Акт) будет высланы на Ваш почтовый адрес, указанный как адрес  доставки;<BR>7. Заверьте документы и вышлите вторую копию на адрес  компании.<BR> <BR> <FORM name=frm action=\"/order/payment.php\" METHOD=POST target=_blank> <INPUT TYPE=hidden name=ORDER_PAYEE value=\"[ID_ORDER]\"> <INPUT TYPE=submit class=\"buttonSend\" value=\"Выписать счет\"> </FORM> ','p','<center> <P> <TABLE  style=\"BORDER-COLLAPSE: collapse; mso-table-layout-alt: fixed; mso-padding-alt: 0cm 2.8pt 0cm 2.8pt\"  cellSpacing=0 cellPadding=0 border=0 > <TBODY > <TR style=\"mso-row-margin-right: .75pt\" > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: black 1pt solid; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 187.1pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-top-alt: solid black .5pt; mso-border-left-alt: solid black .5pt\"  vAlign=top width=249 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=1 >&nbsp;</FONT ><FONT    size=2 >Постачальник _______________________</FONT ></B ></P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 177.2pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt\"  vAlign=top width=236 colSpan=6 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 ></FONT ></B >&nbsp;</P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 0cm; BORDER-TOP: #e0dfe3; PADDING-LEFT: 0cm; PADDING-BOTTOM: 0cm; BORDER-LEFT: #e0dfe3; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-cell-special: placeholder\"  width=1 >   <P style=\"MARGIN: 0cm 0cm 0pt\" ><FONT size=2 ></FONT >&nbsp;</P ></TD ></TR > <TR style=\"mso-row-margin-right: .75pt\" > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 187.1pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt\"  vAlign=top width=249 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><SPAN style=\"mso-spacerun: yes\" ><FONT    size=2 >&nbsp;Адреса ____________________________</FONT ></SPAN ></B ></P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 177.2pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt\"  vAlign=top width=236 colSpan=6 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><FONT    size=2 >РАХУНОК-ФАКТУРА</FONT ></P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 0cm; BORDER-TOP: #e0dfe3; PADDING-LEFT: 0cm; PADDING-BOTTOM: 0cm; BORDER-LEFT: #e0dfe3; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-cell-special: placeholder\"  width=1 >   <P style=\"MARGIN: 0cm 0cm 0pt\" ><FONT size=2 ></FONT >&nbsp;</P ></TD ></TR > <TR style=\"mso-row-margin-right: .75pt\" > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 187.1pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt\"  vAlign=top width=249 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 >&nbsp;Р/рахунок    __________________________ </FONT ></B ></P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 63.8pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt\"  vAlign=top width=85 colSpan=3 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><FONT    size=2 >№</FONT ></P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: black 1pt solid; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 70.85pt; PADDING-TOP: 0cm; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-border-top-alt: solid black .5pt; mso-border-left-alt: solid black .5pt; mso-border-bottom-alt: solid black .5pt\"  vAlign=top width=94 colSpan=2 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 ></FONT ></B >&nbsp;</P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 42.55pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt\"  vAlign=top width=57 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 ></FONT ></B >&nbsp;</P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 0cm; BORDER-TOP: #e0dfe3; PADDING-LEFT: 0cm; PADDING-BOTTOM: 0cm; BORDER-LEFT: #e0dfe3; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-cell-special: placeholder\"  width=1 >   <P style=\"MARGIN: 0cm 0cm 0pt\" ><FONT size=2 ></FONT >&nbsp;</P ></TD ></TR > <TR style=\"mso-row-margin-right: .75pt\" > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 187.1pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt\"  vAlign=top width=249 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 >&nbsp;в    _________________________________</FONT ></B ></P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 28.55pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt\"  vAlign=top width=38 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 ></FONT ></B >&nbsp;</P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: #e0dfe3; WIDTH: 148.65pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent\"  vAlign=top width=198 colSpan=5 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 ></FONT ></B >&nbsp;</P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; BORDER-TOP: #e0dfe3; BORDER-LEFT: #e0dfe3; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-cell-special: placeholder\"  width=1 >   <P style=\"MARGIN: 0cm 0cm 0pt\" ><FONT size=2 ></FONT >&nbsp;</P ></TD ></TR > <TR > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 187.1pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt\"  vAlign=top width=249 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT    size=2 >&nbsp;__________________МФО____________</FONT ></B ></P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 28.55pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt\"  vAlign=top width=38 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 >від</FONT ></B ></P ></TD > <TD  style=\"BORDER-RIGHT: black 1pt solid; PADDING-RIGHT: 2.8pt; BORDER-TOP: black 1pt solid; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 149.4pt; PADDING-TOP: 0cm; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-border-alt: solid black .5pt\"  vAlign=top width=199 colSpan=6 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 >&nbsp;\"____\"    ___________________ р.</FONT ></B ></P ></TD ></TR > <TR style=\"mso-row-margin-right: .75pt\" > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 187.1pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt\"  vAlign=top width=249 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 >&nbsp;ЄДРПОУ    __________________________</FONT ></B ></P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 30.95pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt\"  vAlign=top width=41 colSpan=2 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 ></FONT ></B >&nbsp;</P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: #e0dfe3; WIDTH: 68.25pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent\"  vAlign=top width=91 colSpan=2 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 ></FONT ></B >&nbsp;</P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: #e0dfe3; WIDTH: 78pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent\"  vAlign=top width=104 colSpan=2 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 ></FONT ></B >&nbsp;</P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; BORDER-TOP: #e0dfe3; BORDER-LEFT: #e0dfe3; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-cell-special: placeholder\"  width=1 >   <P style=\"MARGIN: 0cm 0cm 0pt\" ><FONT size=2 ></FONT >&nbsp;</P ></TD ></TR > <TR > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 187.1pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt\"  vAlign=top width=249 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 >&nbsp;Тел./ф.    ____________________________</FONT ></B ></P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 99.2pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt\"  vAlign=top width=132 colSpan=4 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 >до платіжн. вимоги    №</FONT ></B ></P ></TD > <TD  style=\"BORDER-RIGHT: black 1pt solid; PADDING-RIGHT: 2.8pt; BORDER-TOP: black 1pt solid; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 78.75pt; PADDING-TOP: 0cm; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-border-alt: solid black .5pt\"  vAlign=top width=105 colSpan=3 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT  size=2 ></FONT ></B >&nbsp;</P ></TD ></TR > <TR style=\"mso-row-margin-right: .75pt\" > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: black 1pt solid; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: #e0dfe3; WIDTH: 187.1pt; PADDING-TOP: 0cm; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-border-top-alt: solid black .5pt; mso-border-bottom-alt: solid black .5pt\"  vAlign=top width=249 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 ></FONT ></B >&nbsp;</P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: #e0dfe3; WIDTH: 177.2pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent\"  vAlign=top width=236 colSpan=6 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 ></FONT ></B >&nbsp;</P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; BORDER-TOP: #e0dfe3; BORDER-LEFT: #e0dfe3; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-cell-special: placeholder\"  width=1 >   <P style=\"MARGIN: 0cm 0cm 0pt\" ><FONT size=2 ></FONT >&nbsp;</P ></TD ></TR > <TR style=\"mso-yfti-lastrow: yes\" > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 2.8pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 187.1pt; PADDING-TOP: 0cm; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt; mso-border-bottom-alt: solid black .5pt\"  vAlign=top width=249 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 >Платник&nbsp;    </FONT ></B ></P ></TD > <TD  style=\"BORDER-RIGHT: black 1pt solid; PADDING-RIGHT: 2.8pt; BORDER-TOP: black 1pt solid; PADDING-LEFT: 2.8pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 177.95pt; PADDING-TOP: 0cm; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-border-alt: solid black .5pt\"  vAlign=top width=237 colSpan=7 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT  size=2 >Доповнення</FONT ></B ></P ></TD ></TR > <TR height=0 > <TD  style=\"BORDER-RIGHT: #e0dfe3; BORDER-TOP: #e0dfe3; BORDER-LEFT: #e0dfe3; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent\"  width=249 ><FONT size=2 ></FONT ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; BORDER-TOP: #e0dfe3; BORDER-LEFT: #e0dfe3; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent\"  width=38 ><FONT size=2 ></FONT ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; BORDER-TOP: #e0dfe3; BORDER-LEFT: #e0dfe3; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent\"  width=3 ><FONT size=2 ></FONT ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; BORDER-TOP: #e0dfe3; BORDER-LEFT: #e0dfe3; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent\"  width=44 ><FONT size=2 ></FONT ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; BORDER-TOP: #e0dfe3; BORDER-LEFT: #e0dfe3; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent\"  width=47 ><FONT size=2 ></FONT ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; BORDER-TOP: #e0dfe3; BORDER-LEFT: #e0dfe3; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent\"  width=47 ><FONT size=2 ></FONT ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; BORDER-TOP: #e0dfe3; BORDER-LEFT: #e0dfe3; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent\"  width=57 ><FONT size=2 ></FONT ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; BORDER-TOP: #e0dfe3; BORDER-LEFT: #e0dfe3; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent\"  width=1 ><FONT size=2 ></FONT ></TD ></TR ></TBODY ></TABLE ></P > <P style=\"MARGIN: 0cm 0cm 0pt; mso-pagination: none\" ><B  style=\"mso-bidi-font-weight: normal\" ><FONT size=2 ></FONT ></B >&nbsp;</P > <P > <TABLE  style=\"BORDER-COLLAPSE: collapse; mso-table-layout-alt: fixed; mso-padding-alt: 0cm 5.4pt 0cm 5.4pt\"  cellSpacing=0 cellPadding=0 border=0 >  <TBODY >  <TR > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 5.4pt; BORDER-TOP: black 1pt solid; PADDING-LEFT: 5.4pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 166.55pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-top-alt: solid black .5pt; mso-border-left-alt: solid black .5pt\"  vAlign=top width=222 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; TEXT-ALIGN: center; mso-pagination: none\"    align=center ><I style=\"mso-bidi-font-style: normal\" ><FONT    size=2 >Найменування</FONT ></I ></P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 5.4pt; BORDER-TOP: black 1pt solid; PADDING-LEFT: 5.4pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 42.5pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-top-alt: solid black .5pt; mso-border-left-alt: solid black .5pt\"  vAlign=top width=57 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; TEXT-ALIGN: center; mso-pagination: none\"    align=center ><I style=\"mso-bidi-font-style: normal\" ><FONT size=2 >Од.    вим.</FONT ></I ></P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 5.4pt; BORDER-TOP: black 1pt solid; PADDING-LEFT: 5.4pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 42.55pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-top-alt: solid black .5pt; mso-border-left-alt: solid black .5pt\"  vAlign=top width=57 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; TEXT-ALIGN: center; mso-pagination: none\"    align=center ><I style=\"mso-bidi-font-style: normal\" ><FONT    size=2 >К-сть</FONT ></I ></P ></TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 5.4pt; BORDER-TOP: black 1pt solid; PADDING-LEFT: 5.4pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 49.6pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-top-alt: solid black .5pt; mso-border-left-alt: solid black .5pt\"  vAlign=top width=66 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; TEXT-ALIGN: center; mso-pagination: none\"    align=center ><I style=\"mso-bidi-font-style: normal\" ><FONT    size=2 >Ціна</FONT ></I ></P ></TD > <TD  style=\"BORDER-RIGHT: black 1pt solid; PADDING-RIGHT: 5.4pt; BORDER-TOP: black 1pt solid; PADDING-LEFT: 5.4pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 64.55pt; PADDING-TOP: 0cm; BORDER-BOTTOM: #e0dfe3; BACKGROUND-COLOR: transparent; mso-border-top-alt: solid black .5pt; mso-border-left-alt: solid black .5pt; mso-border-right-alt: solid black .5pt\"  vAlign=top width=86 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; TEXT-ALIGN: center; mso-pagination: none\"    align=center ><I style=\"mso-bidi-font-style: normal\" ><FONT    size=2 >Сума</FONT ></I ></P ></TD ></TR >   <SHOPLIST>  <TR >  <TD style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 5.4pt; BORDER-TOP: black 1pt solid; PADDING-LEFT: 5.4pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 166.55pt; PADDING-TOP: 0cm; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-border-top-alt: solid black .5pt; mso-border-left-alt: solid black .5pt; mso-border-bottom-alt: solid black .5pt\"  vAlign=top width=222 >&nbsp;[SHOPLIST.NAME]</TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 5.4pt; BORDER-TOP: black 1pt solid; PADDING-LEFT: 5.4pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 42.5pt; PADDING-TOP: 0cm; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-border-top-alt: solid black .5pt; mso-border-left-alt: solid black .5pt; mso-border-bottom-alt: solid black .5pt\"  vAlign=top width=57 >&nbsp;  </TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 5.4pt; BORDER-TOP: black 1pt solid; PADDING-LEFT: 5.4pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 42.55pt; PADDING-TOP: 0cm; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-border-top-alt: solid black .5pt; mso-border-left-alt: solid black .5pt; mso-border-bottom-alt: solid black .5pt\"  vAlign=top width=57 >&nbsp;[SHOPLIST.COUNT]</TD > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 5.4pt; BORDER-TOP: black 1pt solid; PADDING-LEFT: 5.4pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 49.6pt; PADDING-TOP: 0cm; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-border-top-alt: solid black .5pt; mso-border-left-alt: solid black .5pt; mso-border-bottom-alt: solid black .5pt\"  vAlign=top width=66 >&nbsp;[SHOPLIST.PRICE]</TD >  <TD  style=\"BORDER-RIGHT: black 1pt solid; PADDING-RIGHT: 5.4pt; BORDER-TOP: black 1pt solid; PADDING-LEFT: 5.4pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 64.55pt; PADDING-TOP: 0cm; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-border-alt: solid black .5pt\"  vAlign=top width=86 >[SHOPLIST.SUMMA]&nbsp;</TD ></TR > </SHOPLIST>  <TR > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 5.4pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 5.4pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 301.2pt; PADDING-TOP: 0cm; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt; mso-border-bottom-alt: solid black .5pt\"  vAlign=top width=402 colSpan=4 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 >Всього</FONT ></B ></P ></TD > <TD  style=\"BORDER-RIGHT: black 1pt solid; PADDING-RIGHT: 5.4pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 5.4pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 64.55pt; PADDING-TOP: 0cm; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt; mso-border-bottom-alt: solid black .5pt; mso-border-right-alt: solid black .5pt\"  vAlign=top width=86 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><FONT    size=2 >[ORDER_SUMMA]</FONT >&nbsp;</P ></TD ></TR > <TR > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 5.4pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 5.4pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 301.2pt; PADDING-TOP: 0cm; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt; mso-border-bottom-alt: solid black .5pt\"  vAlign=top width=402 colSpan=4 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 >Податок на додану    вартість (ПДВ)</FONT ></B ></P ></TD > <TD  style=\"BORDER-RIGHT: black 1pt solid; PADDING-RIGHT: 5.4pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 5.4pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 64.55pt; PADDING-TOP: 0cm; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt; mso-border-bottom-alt: solid black .5pt; mso-border-right-alt: solid black .5pt\"  vAlign=top width=86 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><FONT    size=2 ></FONT >&nbsp;</P ></TD ></TR > <TR style=\"mso-yfti-lastrow: yes\" > <TD  style=\"BORDER-RIGHT: #e0dfe3; PADDING-RIGHT: 5.4pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 5.4pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 301.2pt; PADDING-TOP: 0cm; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt; mso-border-bottom-alt: solid black .5pt\"  vAlign=top width=402 colSpan=4 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><B    style=\"mso-bidi-font-weight: normal\" ><FONT size=2 >Загальна сума з    ПДВ</FONT ></B ></P ></TD > <TD  style=\"BORDER-RIGHT: black 1pt solid; PADDING-RIGHT: 5.4pt; BORDER-TOP: #e0dfe3; PADDING-LEFT: 5.4pt; PADDING-BOTTOM: 0cm; BORDER-LEFT: black 1pt solid; WIDTH: 64.55pt; PADDING-TOP: 0cm; BORDER-BOTTOM: black 1pt solid; BACKGROUND-COLOR: transparent; mso-border-left-alt: solid black .5pt; mso-border-bottom-alt: solid black .5pt; mso-border-right-alt: solid black .5pt\"  vAlign=top width=86 >   <P    style=\"MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; mso-pagination: none\" ><FONT    size=2 ></FONT >&nbsp;</P ></TD ></TR ></TBODY ></TABLE ></P > <P style=\"MARGIN: 0cm 0cm 0pt -2cm; mso-pagination: none\" ><B  style=\"mso-bidi-font-weight: normal\" ><FONT size=2 ></FONT ></B >&nbsp;</P > <P style=\"MARGIN: 0cm 0cm 0pt; mso-pagination: none\" ><B  style=\"mso-bidi-font-weight: normal\" ><FONT size=2 >Загальна сума, що підлягає  оплаті ________________________________________________&nbsp; </FONT ></B ></P > <P style=\"MARGIN: 0cm 0cm 0pt; mso-pagination: none\" ><B  style=\"mso-bidi-font-weight: normal\" ><FONT size=2 ></FONT ></B >&nbsp;</P > <P style=\"MARGIN: 0cm 0cm 0pt; mso-pagination: none\" ><B  style=\"mso-bidi-font-weight: normal\" ><FONT  size=2 >___________________________________________________________ грн.  ____________ коп.</FONT ></B ></P > <P style=\"MARGIN: 0cm 0cm 0pt; mso-pagination: none\" ><B  style=\"mso-bidi-font-weight: normal\" ><FONT size=2 ></FONT ></B >&nbsp;</P > <P style=\"MARGIN: 0cm 0cm 0pt; mso-pagination: none\" ><B  style=\"mso-bidi-font-weight: normal\" ><FONT  size=2 >Директор ______________________<SPAN  style=\"mso-tab-count: 2\" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Гол. бухгалтер __________________________ </FONT ></SPAN ></B ></P > </center> ',NULL,NULL,NULL,1,NULL,NULL,NULL,'b',NULL,'N','N',NULL,0,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(10,'ukr',NULL,'payment_webm.jpg','WebMoney','Оплата через систему интернет-платежей Web Money (Веб Мани). Вам необходимо иметь свой собственный счет (кошелек) в системе интернет платежей WebMoney (<a href=\"www.webmoney.ru\" target=_blank>www.webmoney.ru</a>) При оформлении заказа на нашем сайте Вы можете выбрать \"оплата Web Money\" и оплата пройдёт в автоматическом режиме. Примечание: в момент оплаты кошелёк должен быть запущен на Вашем компьютере.','e','<H3 class=contentTitle>Оплата заказа: - WebMoney</H3> <form name=frm method=post>Выберите вид валюты для оплаты: <select onchange=\"document.frm.submit();\" name=\"valuts\"> <option>    ----- <option value=\"wm_z\" [SELECTED:wm_z]>WMZ <option value=\"wm_r\" [SELECTED:wm_r]>WMR <option value=\"wm_e\" [SELECTED:wm_e]>WME <option value=\"wm_u\" [SELECTED:wm_u]>WMU </select> <input type=hidden name=FORMA_PAYEE value=\"4\"> <input type=hidden name=ORDER_PAYEE value=\"[ORDER.ID]\"> </form><br><br> Сумма к оплате: [ORDER_SUMMA]<br><br>Для выполения прямого платежа на кошелек продавца нажмите кнопку \"Продолжить оплату\" и пройдите авторизацию в системе. Вы увидите все детали платежа на экране и сможете подтвердить или отменить платеж. Для выполнения платежа вы должны быть зарегистрированы в системе <a href=\"http://www.webmoney.ru/\" target=_blank>WebMoney Transfer</a><br><br><br> [SETCURRENCY:[IF(RUR=wm_r,USD=wm_z,EUR=wm_e,UKH=wm_u:USD)]] <form method=\"POST\" action=\"https://merchant.webmoney.ru/lmi/payment.asp\" target=_blank> <input type=\"hidden\" name=\"LMI_MODE\" value=\"1\"> <input type=\"hidden\" name=\"LMI_PAYEE_PURSE\" value=\"[PAYMENT.[POST.VALUTS:wm_z]]\"> <input type=\"hidden\" name=\"LMI_PAYMENT_AMOUNT\" value=\"[ORDER.SUMMA]\"> <input type=\"hidden\" name=\"LMI_PAYMENT_NO\" value=\"Заказ:[ORDER.ID]\"> <input type=\"hidden\" name=\"LMI_PAYMENT_DESC\" value=\"Заказ:[ORDER.ID] / Договор: [USER.ID]\"> <input type=\"hidden\" name=\"LMI_SUCCESS_METHOD\" value=\"1\"> <input type=\"hidden\" name=\"LMI_RESULT_URL\" value=\"http://[THISNAMESITE]/modules/shop_payee_order/result.php\"> <input type=\"hidden\" name=\"LMI_SUCCESS_URL\" value=\"http://[THISNAMESITE]/modules/shop_payee_order/success.php\"> <input type=\"hidden\" name=\"LMI_FAIL_URL\" value=\"http://[THISNAMESITE]/modules/shop_payee_order/fail.php\"> <input type=\"hidden\" name=\"LMI_SIM_MODE\" value=\"0\"> <input type=\"hidden\" name=\"PAGEPAYEE\" value=\"http://[THISNAMESITE]/[PAGENAME]?razdel=[RAZDEL]>sub=3\"> <input type=\"submit\" class=\"buttonSend\" value=\"Продолжить оплату\"> </form> ','SE_PAYEXECUTE( SAMETEXT(\"MD5(\"[POST.LMI_PAYEE_PURSE][POST.LMI_PAYMENT_AMOUNT][POST.LMI_PAYMENT_NO][POST.LMI_MODE][POST.LMI_SYS_INVS_NO][POST.LMI_SYS_TRANS_NO][POST.LMI_SYS_TRANS_DATE][POST.LMI_SECRET_KEY][POST.LMI_PAYER_PURSE][POST.LMI_PAYER_WM]\")\",\"[POST.LMI_HASH]\"), [POST.LMI_PAYMENT_AMOUNT], [POST.LMI_SYS_INVS_NO], [POST.LMI_PAYEE_PURSE]) ','<ACCESS> <INPUT type=hidden name=\"LMI_SYS_INVS_NO\" value=\"[POST.LMI_SYS_INVS_NO]\" > <INPUT type=hidden name=\"LMI_SYS_TRANS_DATE\" value=\"[POST.LMI_SYS_TRANS_DATE]\" > <INPUT type=hidden name=\"LMI_SYS_TRANS_NO\" value=\"[POST.LMI_SYS_TRANS_NO]\" > </ACCESS> <H4>Оплата  проведена успешно</H4> <br> Ваш заказ  № [ORDER.ID] оплачен <table class=tableTable border=0> <TBODY class=tableBody> <tr><td>  Номер счета WebMoney Transfer:</td><td> [POST.LMI_SYS_INVS_NO]</td></tr> <tr><td>Номер платежа в WebMoney:</td><td> [POST.LMI_SYS_TRANS_NO]</td></tr>  <tr><td>Дата и время  платежа:</td><td> [POST.LMI_SYS_TRANS_DATE]</td></tr>  </TBODY> </table> ','<ACCESS> </ACCESS> <H4>Ошибка платежа WebMoney</H4><BR> Оплата заказа № [ORDER.ID] не проведена<BR> <BR> ',1,NULL,NULL,NULL,'b',NULL,'N','N',NULL,0,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(11,'rus',NULL,NULL,'Наличными при доставке товара',NULL,'e','<p><strong><font size=\"4\">Спасибо за заказ.</font></strong></p>\r\n<p><strong><font size=\"4\">Ожидайте обработки заказа менеджером.</font></strong></p>',NULL,NULL,NULL,1,NULL,NULL,'N','b',NULL,'Y','N',NULL,0,'2014-11-20 16:03:54','2013-11-06 11:33:15'),(13,'rus',NULL,NULL,'Пластиковой картой',NULL,'e','<p><strong><font size=\"4\">Спасибо за заказ.</font></strong></p>\r\n<p><strong><font size=\"4\">Ожидайте обработки заказа менеджером.</font></strong></p>',NULL,NULL,NULL,1,NULL,NULL,'N','b',NULL,'Y','N',NULL,0,'2014-11-20 16:03:47','2014-06-24 16:20:45');
/*!40000 ALTER TABLE `shop_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_payment_merchant`
--

DROP TABLE IF EXISTS `shop_payment_merchant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_payment_merchant` (
  `id` int(10) unsigned NOT NULL,
  `ident` varchar(40) DEFAULT NULL,
  `deflang` char(3) NOT NULL DEFAULT 'rus',
  `payment_id` int(10) unsigned DEFAULT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `summ` double(10,2) NOT NULL,
  `amount` double(10,2) NOT NULL,
  `curr` char(3) NOT NULL DEFAULT 'RUR',
  `payer` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL,
  `date_payee` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_payment_merchant`
--

LOCK TABLES `shop_payment_merchant` WRITE;
/*!40000 ALTER TABLE `shop_payment_merchant` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_payment_merchant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_preorder`
--

DROP TABLE IF EXISTS `shop_preorder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_preorder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_price` int(10) unsigned NOT NULL,
  `count` float(5,2) unsigned DEFAULT '1.00',
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `send_mail` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_price` (`id_price`),
  CONSTRAINT `shop_preorder_ibfk_1` FOREIGN KEY (`id_price`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_preorder`
--

LOCK TABLES `shop_preorder` WRITE;
/*!40000 ALTER TABLE `shop_preorder` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_preorder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_price`
--

DROP TABLE IF EXISTS `shop_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_price` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang` char(3) DEFAULT NULL,
  `id_group` int(10) unsigned DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `article` varchar(40) DEFAULT NULL,
  `name` varchar(125) DEFAULT NULL,
  `price` double(10,2) DEFAULT '0.00',
  `price_opt` double(10,2) NOT NULL DEFAULT '0.00',
  `price_opt_corp` double(10,2) NOT NULL DEFAULT '0.00',
  `price_purchase` decimal(10,2) unsigned DEFAULT '0.00' COMMENT 'Закупочная цена товара',
  `img` varchar(255) DEFAULT NULL,
  `img_alt` varchar(255) DEFAULT NULL,
  `note` text,
  `text` text,
  `title` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` text,
  `page_title` varchar(255) DEFAULT NULL,
  `presence_count` double(10,3) DEFAULT '-1.000',
  `step_count` double(10,3) NOT NULL DEFAULT '1.000',
  `min_count` double(10,3) NOT NULL DEFAULT '1.000',
  `bonus` double(10,2) NOT NULL DEFAULT '0.00',
  `curr` char(3) NOT NULL DEFAULT 'RUB',
  `presence` varchar(125) DEFAULT NULL,
  `nds` float(5,2) DEFAULT NULL,
  `manufacturer` varchar(125) DEFAULT NULL,
  `id_brand` int(10) unsigned DEFAULT NULL,
  `id_type` int(10) unsigned DEFAULT NULL COMMENT 'Тип товара',
  `date_manufactured` date DEFAULT NULL,
  `max_discount` float(5,2) DEFAULT NULL,
  `measure` varchar(50) DEFAULT NULL,
  `volume` decimal(10,3) unsigned DEFAULT NULL,
  `weight` decimal(10,3) unsigned DEFAULT NULL,
  `id_action` int(10) unsigned DEFAULT NULL,
  `marka` varchar(127) DEFAULT NULL,
  `app_models` varchar(127) DEFAULT NULL,
  `orig_numbers` varchar(255) DEFAULT NULL,
  `special_price` enum('Y','N') NOT NULL DEFAULT 'Y',
  `discount` enum('Y','N') NOT NULL DEFAULT 'Y',
  `flag_new` enum('Y','N') NOT NULL DEFAULT 'N',
  `flag_hit` enum('Y','N') NOT NULL DEFAULT 'N',
  `special_offer` enum('Y','N') DEFAULT 'N',
  `enabled` enum('Y','N') NOT NULL DEFAULT 'Y',
  `is_show_feature` tinyint(1) NOT NULL DEFAULT '1',
  `rate` decimal(10,2) DEFAULT '0.00',
  `votes` int(11) NOT NULL DEFAULT '0',
  `unsold` enum('Y','N') NOT NULL DEFAULT 'N',
  `vizits` int(10) unsigned NOT NULL DEFAULT '0',
  `is_market` tinyint(1) NOT NULL DEFAULT '1',
  `market_category` smallint(6) unsigned DEFAULT NULL,
  `market_available` tinyint(1) NOT NULL DEFAULT '1',
  `sort` int(10) unsigned DEFAULT NULL,
  `id_exchange` varchar(50) DEFAULT NULL,
  `signal_dt` varchar(10) DEFAULT NULL,
  `delivery_time` varchar(80) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `id_exchange` (`id_exchange`) USING BTREE,
  KEY `lang` (`lang`),
  KEY `id_group` (`id_group`),
  KEY `sort` (`sort`),
  CONSTRAINT `shop_price_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `shop_group` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_price`
--

LOCK TABLES `shop_price` WRITE;
/*!40000 ALTER TABLE `shop_price` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_price_group`
--

DROP TABLE IF EXISTS `shop_price_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_price_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_price` int(10) unsigned NOT NULL,
  `id_group` int(10) unsigned NOT NULL,
  `is_main` tinyint(1) NOT NULL DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_price` (`id_price`),
  KEY `id_group` (`id_group`),
  KEY `is_main` (`is_main`),
  CONSTRAINT `shop_price_group_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_price_group`
--

LOCK TABLES `shop_price_group` WRITE;
/*!40000 ALTER TABLE `shop_price_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_price_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_price_measure`
--

DROP TABLE IF EXISTS `shop_price_measure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_price_measure` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_price` int(10) unsigned NOT NULL,
  `id_weight_view` int(10) unsigned DEFAULT NULL,
  `id_weight_edit` int(10) unsigned DEFAULT NULL,
  `id_volume_view` int(10) unsigned DEFAULT NULL,
  `id_volume_edit` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_price` (`id_price`),
  KEY `id_weight_view` (`id_weight_view`),
  KEY `id_weight_edit` (`id_weight_edit`),
  KEY `id_volume_view` (`id_volume_view`),
  KEY `id_volume_edit` (`id_volume_edit`),
  CONSTRAINT `shop_price_measure_ibfk_1` FOREIGN KEY (`id_price`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_price_measure`
--

LOCK TABLES `shop_price_measure` WRITE;
/*!40000 ALTER TABLE `shop_price_measure` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_price_measure` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_price_param`
--

DROP TABLE IF EXISTS `shop_price_param`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_price_param` (
  `id` int(10) unsigned NOT NULL,
  `price_id` int(10) unsigned NOT NULL,
  `param_id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `value` varchar(255) NOT NULL,
  `price` double(10,2) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `imgparam` varchar(255) DEFAULT NULL,
  `imgparam_alt` varchar(255) DEFAULT NULL,
  `vtype` enum('add','calc') NOT NULL DEFAULT 'add',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_price_param`
--

LOCK TABLES `shop_price_param` WRITE;
/*!40000 ALTER TABLE `shop_price_param` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_price_param` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `shop_price_userfields`;
CREATE TABLE IF NOT EXISTS `shop_price_userfields` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_price` int(10) UNSIGNED NOT NULL,
  `id_userfield` int(10) UNSIGNED NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_order` (`id_price`),
  KEY `id_userfield` (`id_userfield`),
  CONSTRAINT `shop_price_userfields_ibfk_1` FOREIGN KEY (`id_price`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_price_userfields_ibfk_2` FOREIGN KEY (`id_userfield`) REFERENCES `shop_userfields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Table structure for table `shop_product_option`
--

DROP TABLE IF EXISTS `shop_product_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_product_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_product` int(10) unsigned NOT NULL,
  `id_option_value` int(10) unsigned NOT NULL,
  `price` double(10,2) NOT NULL DEFAULT '0.00',
  `sort` int(10) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Будет ли значение опции выбранно по умолчанию',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_product` (`id_product`),
  KEY `id_option_value` (`id_option_value`),
  CONSTRAINT `shop_product_option_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_product_option_ibfk_2` FOREIGN KEY (`id_option_value`) REFERENCES `shop_option_value` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_product_option`
--

LOCK TABLES `shop_product_option` WRITE;
/*!40000 ALTER TABLE `shop_product_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_product_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_product_option_position`
--

DROP TABLE IF EXISTS `shop_product_option_position`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_product_option_position` (
  `id` int(10) unsigned NOT NULL,
  `id_product` int(10) unsigned NOT NULL,
  `id_option` int(10) unsigned NOT NULL,
  `position` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Позиция отображения: 0 - нигде, 1 - оба варианта, 2 - только снизу (основной контент), 3 - только справа (плавающий блок)',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=56 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_product_option_position`
--

LOCK TABLES `shop_product_option_position` WRITE;
/*!40000 ALTER TABLE `shop_product_option_position` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_product_option_position` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_product_type`
--

DROP TABLE IF EXISTS `shop_product_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_product_type` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Типы товаров';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_product_type`
--

LOCK TABLES `shop_product_type` WRITE;
/*!40000 ALTER TABLE `shop_product_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_product_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_product_type_feature`
--

DROP TABLE IF EXISTS `shop_product_type_feature`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_product_type_feature` (
  `id` int(10) unsigned NOT NULL,
  `id_type` int(10) unsigned NOT NULL,
  `id_feature` int(10) unsigned NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Связка типов товаров с параметрами';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_product_type_feature`
--

LOCK TABLES `shop_product_type_feature` WRITE;
/*!40000 ALTER TABLE `shop_product_type_feature` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_product_type_feature` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_rating`
--

DROP TABLE IF EXISTS `shop_rating`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_rating` (
  `id` int(10) unsigned NOT NULL,
  `id_price` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `mark` smallint(1) unsigned NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_rating`
--

LOCK TABLES `shop_rating` WRITE;
/*!40000 ALTER TABLE `shop_rating` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_reviews`
--

DROP TABLE IF EXISTS `shop_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_reviews` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_price` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `mark` smallint(1) unsigned NOT NULL DEFAULT '0',
  `merits` text,
  `demerits` text,
  `comment` text,
  `use_time` smallint(1) unsigned NOT NULL DEFAULT '1',
  `date` datetime NULL DEFAULT NOW(),
  `likes` int(10) unsigned NOT NULL DEFAULT '0',
  `dislikes` int(10) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_price` (`id_price`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `shop_reviews_ibfk_1` FOREIGN KEY (`id_price`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_reviews_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `se_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_reviews`
--

LOCK TABLES `shop_reviews` WRITE;
/*!40000 ALTER TABLE `shop_reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_reviews_votes`
--

DROP TABLE IF EXISTS `shop_reviews_votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_reviews_votes` (
  `id` int(10) unsigned NOT NULL,
  `id_review` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `vote` smallint(1) NOT NULL DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_reviews_votes`
--

LOCK TABLES `shop_reviews_votes` WRITE;
/*!40000 ALTER TABLE `shop_reviews_votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_reviews_votes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_sameprice`
--

DROP TABLE IF EXISTS `shop_sameprice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_sameprice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_price` int(10) unsigned DEFAULT NULL,
  `id_acc` int(10) unsigned DEFAULT NULL,
  `cross` tinyint(1) NOT NULL DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_price` (`id_price`),
  KEY `id_acc` (`id_acc`),
  CONSTRAINT `shop_sameprice_ibfk_1` FOREIGN KEY (`id_price`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_sameprice_ibfk_2` FOREIGN KEY (`id_acc`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30892 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_sameprice`
--

LOCK TABLES `shop_sameprice` WRITE;
/*!40000 ALTER TABLE `shop_sameprice` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_sameprice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_section`
--

DROP TABLE IF EXISTS `shop_section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_section` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(40) NOT NULL COMMENT 'Код раздела',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=4096 COMMENT='Разделы главной страницы';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_section`
--

LOCK TABLES `shop_section` WRITE;
/*!40000 ALTER TABLE `shop_section` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_section` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_section_item`
--

DROP TABLE IF EXISTS `shop_section_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_section_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_section` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `text` text,
  `id_price` int(10) unsigned DEFAULT NULL COMMENT 'Ид. элемента из таблицы shop_price',
  `id_group` int(10) unsigned DEFAULT NULL COMMENT 'Ид. элемента из таблицы shop_group',
  `id_brand` int(10) unsigned DEFAULT NULL COMMENT 'Ид. элемента из таблицы shop_brand',
  `id_new` int(10) unsigned DEFAULT NULL COMMENT 'Ид. элемента из таблицы news',
  `url` varchar(255) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `picture_alt` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_section` (`id_section`),
  KEY `id_price` (`id_price`),
  KEY `id_group` (`id_group`),
  KEY `id_brand` (`id_brand`),
  KEY `id_new` (`id_new`),
  CONSTRAINT `shop_section_item_ibfk_1` FOREIGN KEY (`id_section`) REFERENCES `shop_section` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_section_item_ibfk_2` FOREIGN KEY (`id_brand`) REFERENCES `shop_brand` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_section_item_ibfk_3` FOREIGN KEY (`id_group`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_section_item_ibfk_4` FOREIGN KEY (`id_price`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_section_item_ibfk_5` FOREIGN KEY (`id_new`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=3276 ROW_FORMAT=DYNAMIC COMMENT='Элементы разделов';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_section_item`
--

LOCK TABLES `shop_section_item` WRITE;
/*!40000 ALTER TABLE `shop_section_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_section_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_section_page`
--

DROP TABLE IF EXISTS `shop_section_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_section_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_section` int(10) unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `page` varchar(255) DEFAULT NULL,
  `se_section` varchar(10) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_section` (`id_section`),
  KEY `page` (`page`),
  CONSTRAINT `shop_section_page_ibfk_1` FOREIGN KEY (`id_section`) REFERENCES `shop_section` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=4096;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_section_page`
--

LOCK TABLES `shop_section_page` WRITE;
/*!40000 ALTER TABLE `shop_section_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_section_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_setting_groups`
--

DROP TABLE IF EXISTS `shop_setting_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_setting_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_setting_groups`
--

LOCK TABLES `shop_setting_groups` WRITE;
/*!40000 ALTER TABLE `shop_setting_groups` DISABLE KEYS */;
INSERT INTO `shop_setting_groups` VALUES (1,'Параметры изображения','Параметры изображения при выгрузке',1,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,'Параметры мер','Параметры мер веса и объема',2,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(3,'Онлайн-касса \"АТОЛ Онлайн\"','Настройки для подключения сервиса онлайн-кассы \"АТОЛ\"',3,'0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `shop_setting_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_setting_values`
--

DROP TABLE IF EXISTS `shop_setting_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_setting_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_main` int(10) unsigned DEFAULT NULL,
  `id_setting` int(10) unsigned NOT NULL,
  `value` varchar(100) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_main` (`id_main`),
  KEY `id_setting` (`id_setting`),
  CONSTRAINT `shop_setting_values_ibfk_1` FOREIGN KEY (`id_main`) REFERENCES `main` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_setting_values_ibfk_2` FOREIGN KEY (`id_setting`) REFERENCES `shop_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_setting_values`
--

LOCK TABLES `shop_setting_values` WRITE;
/*!40000 ALTER TABLE `shop_setting_values` DISABLE KEYS */;
INSERT INTO `shop_setting_values` VALUES (1,NULL,1,'650x650','0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,NULL,1,'650x650','0000-00-00 00:00:00','0000-00-00 00:00:00'),(3,NULL,1,'650x650','0000-00-00 00:00:00','0000-00-00 00:00:00'),(4,NULL,1,'650x650','0000-00-00 00:00:00','0000-00-00 00:00:00'),(27,NULL,2,'163','0000-00-00 00:00:00','0000-00-00 00:00:00'),(28,NULL,3,'111','0000-00-00 00:00:00','0000-00-00 00:00:00'),(29,NULL,4,'163','0000-00-00 00:00:00','0000-00-00 00:00:00'),(30,NULL,5,'111','0000-00-00 00:00:00','0000-00-00 00:00:00'),(31,NULL,6,'superadmin','0000-00-00 00:00:00','0000-00-00 00:00:00'),(32,NULL,7,'gh65Ret3!78','0000-00-00 00:00:00','0000-00-00 00:00:00'),(33,NULL,8,'','0000-00-00 00:00:00','0000-00-00 00:00:00'),(34,NULL,9,'','0000-00-00 00:00:00','0000-00-00 00:00:00'),(35,NULL,10,'','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `shop_setting_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_settings`
--

DROP TABLE IF EXISTS `shop_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `type` varchar(25) NOT NULL DEFAULT 'string' COMMENT 'string - текстовое поле, bool - чекбокс, select - выбор из списка из поля list_values',
  `name` varchar(100) NOT NULL COMMENT 'название параметра',
  `default` varchar(100) NOT NULL COMMENT 'значение по умолчанию',
  `list_values` varchar(255) DEFAULT NULL COMMENT 'список значений в формате value1|name1,value2|name2,value3|name3 ',
  `id_group` int(10) unsigned DEFAULT NULL,
  `description` text COMMENT 'описание параметра',
  `sort` int(10) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 - неактивный параметр',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `sort` (`sort`),
  KEY `type` (`type`),
  KEY `id_group` (`id_group`),
  CONSTRAINT `shop_settings_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `shop_setting_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_settings`
--

LOCK TABLES `shop_settings` WRITE;
/*!40000 ALTER TABLE `shop_settings` DISABLE KEYS */;
INSERT INTO `shop_settings` VALUES (1,'size_picture','string','Максимальный размер изображения','650x650',NULL,1,'При включенном параметре все изображения при загрузке в программе будут сжиматься пропорционально до заданных значений. Значения задаются в пикселях: ШИРИНАxВЫСОТА (пример: 1920x1080).',0,0,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,'weight_view','string','Код отображаемой меры веса','163',NULL,2,'Код ОКЕИ отображаемой меры веса',0,1,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(3,'volume_view','string','Код отображаемой меры объема','111',NULL,2,'Код ОКЕИ отображаемой меры обема',1,1,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(4,'weight_edit','string','Код редактируемой меры веса','163',NULL,2,'Код ОКЕИ редактируемой меры веса',2,1,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(5,'volume_edit','string','Код редактируемой меры объема','111',NULL,2,'Код ОКЕИ редактируемой меры объема',3,1,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(6,'atol_login','string','Логин пользователя API \"АТОЛ Онлайн\"','',NULL,3,'Логин для отправки данных можно получить из файла настроек для CMS в личном кабинете пользователя \"АТОЛ Онлайн\".',1,1,'0000-00-00 00:00:00','2019-01-27 07:54:24'),(7,'atol_password','password','Пароль пользователя API \"АТОЛ Онлайн\"','',NULL,3,'Пароль для отправки данных можно получить из файла настроек для CMS в личном кабинете пользователя \"АТОЛ Онлайн\".',2,1,'0000-00-00 00:00:00','2019-01-27 07:54:24'),(8,'atol_group','string','Идентификатор группы ККТ','',NULL,3,'Идентификатор группы для отправки данных можно получить из файла настроек для CMS в личном кабинете пользователя \"АТОЛ Онлайн\".',3,1,'0000-00-00 00:00:00','2019-01-27 07:54:24'),(9,'atol_inn','string','ИНН организации','',NULL,3,'Используется для предотвращения ошибочных регистраций чеков на ККТ зарегистрированных с другим ИНН (сравнивается со значением в ФН). Допустимое количество символов 10 или 12. Если ИНН имеет длину меньше 12 цифр, то значение дополняется справа пробелами.',4,1,'0000-00-00 00:00:00','2019-01-27 07:54:24'),(10,'atol_payment_address','string','Адрес  места  расчетов','',NULL,3,'Используется для предотвращения ошибочных регистраций чеков на ККТ зарегистрированных с другим адресом места расчёта (сравнивается со значением в ФН). Максимальная длина строки – 256 символов.',5,1,'0000-00-00 00:00:00','2019-01-27 07:54:24');
/*!40000 ALTER TABLE `shop_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_special`
--

DROP TABLE IF EXISTS `shop_special`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_special` (
  `id` int(10) unsigned NOT NULL,
  `id_group` int(10) unsigned DEFAULT NULL,
  `id_price` int(10) unsigned DEFAULT NULL,
  `newprice` double(10,2) DEFAULT NULL,
  `newproc` float(6,4) DEFAULT NULL,
  `curr` char(3) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `expires_date` datetime DEFAULT NULL,
  `status` enum('Y','N') DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_special`
--

LOCK TABLES `shop_special` WRITE;
/*!40000 ALTER TABLE `shop_special` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_special` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_stat_cart`
--

DROP TABLE IF EXISTS `shop_stat_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_stat_cart` (
  `id_session` int(10) unsigned NOT NULL,
  `id_product` int(10) unsigned NOT NULL,
  `modifications` varchar(255) DEFAULT NULL,
  `count` double(10,3) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  KEY `id_session` (`id_session`),
  KEY `id_product` (`id_product`),
  CONSTRAINT `shop_stat_cart_ibfk_1` FOREIGN KEY (`id_session`) REFERENCES `shop_stat_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_stat_cart_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=5461;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_stat_cart`
--

LOCK TABLES `shop_stat_cart` WRITE;
/*!40000 ALTER TABLE `shop_stat_cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_stat_cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_stat_contact`
--

DROP TABLE IF EXISTS `shop_stat_contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_stat_contact` (
  `id_session` int(10) unsigned NOT NULL,
  `contact` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  KEY `id_session` (`id_session`),
  CONSTRAINT `shop_stat_contact_ibfk_1` FOREIGN KEY (`id_session`) REFERENCES `shop_stat_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_stat_contact`
--

LOCK TABLES `shop_stat_contact` WRITE;
/*!40000 ALTER TABLE `shop_stat_contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_stat_contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_stat_events`
--

DROP TABLE IF EXISTS `shop_stat_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_stat_events` (
  `id_session` int(10) unsigned NOT NULL,
  `event` varchar(50) NOT NULL,
  `number` smallint(5) unsigned NOT NULL DEFAULT '0',
  `content` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  KEY `id_session` (`id_session`),
  CONSTRAINT `shop_stat_events_ibfk_1` FOREIGN KEY (`id_session`) REFERENCES `shop_stat_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=2340;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_stat_events`
--

LOCK TABLES `shop_stat_events` WRITE;
/*!40000 ALTER TABLE `shop_stat_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_stat_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_stat_info`
--

DROP TABLE IF EXISTS `shop_stat_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_stat_info` (
  `id_session` int(10) unsigned NOT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `id_session` (`id_session`),
  CONSTRAINT `shop_stat_info_ibfk_1` FOREIGN KEY (`id_session`) REFERENCES `shop_stat_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_stat_info`
--

LOCK TABLES `shop_stat_info` WRITE;
/*!40000 ALTER TABLE `shop_stat_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_stat_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_stat_session`
--

DROP TABLE IF EXISTS `shop_stat_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_stat_session` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` varchar(32) NOT NULL,
  `host` varchar(255) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `id_user` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sid` (`sid`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `shop_stat_session_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `se_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_stat_session`
--

LOCK TABLES `shop_stat_session` WRITE;
/*!40000 ALTER TABLE `shop_stat_session` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_stat_session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_stat_viewgoods`
--

DROP TABLE IF EXISTS `shop_stat_viewgoods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_stat_viewgoods` (
  `id_session` int(10) unsigned NOT NULL,
  `id_product` int(10) unsigned NOT NULL,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  KEY `id_session` (`id_session`),
  KEY `id_product` (`id_product`),
  CONSTRAINT `shop_stat_viewgoods_ibfk_1` FOREIGN KEY (`id_session`) REFERENCES `shop_stat_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_stat_viewgoods_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_stat_viewgoods`
--

LOCK TABLES `shop_stat_viewgoods` WRITE;
/*!40000 ALTER TABLE `shop_stat_viewgoods` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_stat_viewgoods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_tovarorder`
--

DROP TABLE IF EXISTS `shop_tovarorder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_tovarorder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_order` int(10) unsigned NOT NULL,
  `id_price` int(10) unsigned DEFAULT NULL,
  `article` varchar(40) DEFAULT NULL,
  `nameitem` varchar(125) DEFAULT NULL,
  `price` double(10,2) unsigned NOT NULL,
  `price_purchase` decimal(10,2) unsigned DEFAULT NULL,
  `discount` double(10,2) unsigned NOT NULL,
  `count` double(10,3) unsigned NOT NULL,
  `modifications` varchar(255) DEFAULT NULL,
  `license` text,
  `commentary` text,
  `action` varchar(125) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_order` (`id_order`),
  KEY `id_price` (`id_price`),
  CONSTRAINT `shop_tovarorder_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `shop_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_tovarorder_ibfk_2` FOREIGN KEY (`id_price`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_tovarorder`
--

LOCK TABLES `shop_tovarorder` WRITE;
/*!40000 ALTER TABLE `shop_tovarorder` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_tovarorder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_tovarorder_option`
--

DROP TABLE IF EXISTS `shop_tovarorder_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_tovarorder_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_tovarorder` int(10) unsigned NOT NULL,
  `id_option_value` int(10) unsigned NOT NULL,
  `price` double(10,2) NOT NULL DEFAULT '0.00',
  `base_price` double(10,2) NOT NULL DEFAULT '0.00',
  `count` double(10,3) NOT NULL DEFAULT '0.000',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_tovarorder` (`id_tovarorder`),
  CONSTRAINT `shop_tovarorder_option_ibfk_1` FOREIGN KEY (`id_tovarorder`) REFERENCES `shop_tovarorder` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_tovarorder_option`
--

LOCK TABLES `shop_tovarorder_option` WRITE;
/*!40000 ALTER TABLE `shop_tovarorder_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_tovarorder_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_userfield_groups`
--

DROP TABLE IF EXISTS `shop_userfield_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_userfield_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `sort` int(10) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `data` enum('contact','order','company','productgroup','product','public') DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_userfield_groups`
--

LOCK TABLES `shop_userfield_groups` WRITE;
/*!40000 ALTER TABLE `shop_userfield_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_userfield_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_userfields`
--

DROP TABLE IF EXISTS `shop_userfields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_userfields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_group` int(10) unsigned DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('string','text','select','checkbox','radio','date','number') NOT NULL DEFAULT 'string',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `placeholder` varchar(255) DEFAULT NULL,
  `mask` varchar(255) DEFAULT NULL,
  `description` text,
  `values` text,
  `sort` int(10) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `data` enum('contact','order','company','productgroup','product','public') DEFAULT NULL,
  `min` int(10) unsigned DEFAULT NULL,
  `max` int(10) unsigned DEFAULT NULL,
  `def` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_group` (`id_group`),
  CONSTRAINT `shop_userfields_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `shop_userfield_groups` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=2730 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_userfields`
--

LOCK TABLES `shop_userfields` WRITE;
/*!40000 ALTER TABLE `shop_userfields` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_userfields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_variables`
--

DROP TABLE IF EXISTS `shop_variables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_variables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=3276 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_variables`
--

LOCK TABLES `shop_variables` WRITE;
/*!40000 ALTER TABLE `shop_variables` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_variables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_log`
--

DROP TABLE IF EXISTS `sms_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `id_sms` varchar(50) NOT NULL,
  `id_provider` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `code` int(11) unsigned DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `cost` decimal(19,2) DEFAULT NULL,
  `count` int(11) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_log`
--

LOCK TABLES `sms_log` WRITE;
/*!40000 ALTER TABLE `sms_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_providers`
--

DROP TABLE IF EXISTS `sms_providers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_providers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT 'Наименование шлюза',
  `url` varchar(255) DEFAULT NULL,
  `settings` varchar(255) NOT NULL COMMENT 'настройки СМС шлюза (JSON формат)',
  `is_active` tinyint(1) DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_providers`
--

LOCK TABLES `sms_providers` WRITE;
/*!40000 ALTER TABLE `sms_providers` DISABLE KEYS */;
INSERT INTO `sms_providers` VALUES (1,'sms.ru','sms.ru','{\"api_id\":{\"type\":\"string\",\"value\":\"\"},\"_edit_\":{\"value\":true}}',1,'2020-07-23 19:39:28','2016-08-13 13:42:59'),(2,'qtelecom.ru','qtelecom.ru','{\"login\":{\"type\":\"string\",\"value\":\"\"},\"password\":{\"type\":\"string\",\"value\":\"\"}}',0,'2016-09-27 10:33:00','2016-08-13 13:43:00');
/*!40000 ALTER TABLE `sms_providers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_templates`
--

DROP TABLE IF EXISTS `sms_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `phone` varchar(255) DEFAULT NULL COMMENT 'Телефоны получателя по умолчанию',
  `sender` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_templates`
--

LOCK TABLES `sms_templates` WRITE;
/*!40000 ALTER TABLE `sms_templates` DISABLE KEYS */;
INSERT INTO `sms_templates` VALUES (1,'orderadm','SMS о заказе','Здравствуйте, [NAMECLIENT]! Вы оформили предварительный заказ №[SHOP_ORDER_NUM] на сайте, менеджер свяжется с вами в ближайшее время.',0,'','','2016-09-16 07:33:37','2016-08-13 13:43:00'),(2,'orderuser','СМС клиенту о заказе','Здравствуйте, [NAMECLIENT]! Вы оформили предварительный заказ №[SHOP_ORDER_NUM] Сумма заказа и доставки Итого: [SHOP_ORDER_TOTAL]. Менеджер свяжется с вами в ближайшее время.',1,NULL,NULL,'2016-09-20 12:55:28','2016-09-15 13:54:40');
/*!40000 ALTER TABLE `sms_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spr_numbers`
--

DROP TABLE IF EXISTS `spr_numbers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `spr_numbers` (
  `id` int(10) unsigned NOT NULL,
  `lang` char(3) NOT NULL DEFAULT 'rus',
  `registr` varchar(10) DEFAULT NULL,
  `zero` varchar(20) DEFAULT NULL,
  `one` varchar(20) DEFAULT NULL,
  `two` varchar(20) DEFAULT NULL,
  `three` varchar(20) DEFAULT NULL,
  `four` varchar(20) DEFAULT NULL,
  `five` varchar(20) DEFAULT NULL,
  `six` varchar(20) DEFAULT NULL,
  `seven` varchar(20) DEFAULT NULL,
  `eight` varchar(20) DEFAULT NULL,
  `nine` varchar(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spr_numbers`
--

LOCK TABLES `spr_numbers` WRITE;
/*!40000 ALTER TABLE `spr_numbers` DISABLE KEYS */;
INSERT INTO `spr_numbers` VALUES (1,'rus','edin',NULL,'один','два','три','четыре','пять','шесть','семь','восемь','девять','0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,'rus','des','десять','одиннадцать','двенадцать','тринадцать','четырнадцать','пятьнадцать','шестьнадцать','семьнадцать','восемьнадцать','девятьнадцать','0000-00-00 00:00:00','0000-00-00 00:00:00'),(3,'rus','dec',NULL,NULL,'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят','восемьдесят','девяносто','0000-00-00 00:00:00','0000-00-00 00:00:00'),(4,'rus','sot',NULL,'сто','двести','триста','четыреста','пятьсот','шестьсот','семьсот','восемьсот','девятьсот','0000-00-00 00:00:00','0000-00-00 00:00:00'),(5,'rus','mel',NULL,'одна','двe','три','четыре','пять','шесть','семь','восемь','девять','0000-00-00 00:00:00','0000-00-00 00:00:00'),(6,'rus','thou','тысяч','тысяча','тысячи','тысячи','тысячи','тысяч','тысяч','тысяч','тысяч','тысяч','0000-00-00 00:00:00','0000-00-00 00:00:00'),(7,'rus','mill','миллионов','миллион','миллиона','миллиона','миллиона','миллионов','миллионов','миллионов','миллионов','миллионов','0000-00-00 00:00:00','0000-00-00 00:00:00'),(8,'rus','wh','руб.','руб.','руб.','руб.','руб.','руб.','руб.','руб.','руб.','руб.','0000-00-00 00:00:00','0000-00-00 00:00:00'),(9,'rus','fr','коп.','коп.','коп.','коп.','коп.','коп.','коп.','коп.','коп.','коп.','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `spr_numbers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_rekv`
--

DROP TABLE IF EXISTS `user_rekv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_rekv` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_author` int(10) unsigned DEFAULT NULL,
  `lang` char(3) DEFAULT 'rus',
  `rekv_code` varchar(40) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_author` (`id_author`),
  KEY `lang` (`lang`),
  CONSTRAINT `user_rekv_ibfk_1` FOREIGN KEY (`id_author`) REFERENCES `se_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_rekv`
--

LOCK TABLES `user_rekv` WRITE;
/*!40000 ALTER TABLE `user_rekv` DISABLE KEYS */;
INSERT INTO `user_rekv` VALUES (1,1,'rus','inn',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,1,'rus','kpp',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(3,1,'rus','rs',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(4,1,'rus','ks',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(5,1,'rus','bik',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(6,1,'rus','bank',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `user_rekv` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_rekv_type`
--

DROP TABLE IF EXISTS `user_rekv_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_rekv_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang` char(3) DEFAULT 'rus',
  `code` varchar(20) DEFAULT NULL,
  `size` int(10) unsigned DEFAULT '40',
  `title` varchar(125) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_rekv_type`
--

LOCK TABLES `user_rekv_type` WRITE;
/*!40000 ALTER TABLE `user_rekv_type` DISABLE KEYS */;
INSERT INTO `user_rekv_type` VALUES (1,'rus','inn',12,'ИНН','0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,'rus','kpp',9,'КПП','0000-00-00 00:00:00','0000-00-00 00:00:00'),(3,'rus','rs',22,'расч.счет','0000-00-00 00:00:00','0000-00-00 00:00:00'),(4,'rus','ks',22,'кор.счет','0000-00-00 00:00:00','0000-00-00 00:00:00'),(5,'rus','bik',11,'БИК','0000-00-00 00:00:00','0000-00-00 00:00:00'),(6,'rus','bank',255,'БАНК','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `user_rekv_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_urid`
--

DROP TABLE IF EXISTS `user_urid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_urid` (
  `id` int(10) unsigned NOT NULL,
  `company` varchar(250) DEFAULT NULL,
  `director` varchar(80) DEFAULT NULL,
  `posthead` varchar(40) DEFAULT NULL,
  `bookkeeper` varchar(80) DEFAULT NULL,
  `postbookk` int(11) DEFAULT NULL,
  `uradres` text,
  `fizadres` text,
  `tel` varchar(125) DEFAULT NULL,
  `fax` varchar(125) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `user_urid_ibfk_1` FOREIGN KEY (`id`) REFERENCES `se_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_urid`
--

LOCK TABLES `user_urid` WRITE;
/*!40000 ALTER TABLE `user_urid` DISABLE KEYS */;
INSERT INTO `user_urid` VALUES (1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `user_urid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `word_exclude`
--

DROP TABLE IF EXISTS `word_exclude`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `word_exclude` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(255) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Слова исключения для склонения';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `word_exclude`
--

LOCK TABLES `word_exclude` WRITE;
/*!40000 ALTER TABLE `word_exclude` DISABLE KEYS */;
/*!40000 ALTER TABLE `word_exclude` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-07-23 22:42:39

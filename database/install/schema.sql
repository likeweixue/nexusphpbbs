
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
DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `adclicks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `adclicks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `adid` int unsigned DEFAULT NULL,
  `userid` int unsigned DEFAULT NULL,
  `added` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `adminpanel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `adminpanel` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `info` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `advertisements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `advertisements` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `type` enum('bbcodes','xhtml','text','image','flash') COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` enum('header','footer','belownav','belowsearchbox','torrentdetail','comment','interoverforums','forumpost','popup') COLLATE utf8mb4_unicode_ci NOT NULL,
  `displayorder` tinyint NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `parameters` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `starttime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `agent_allowed_exception`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agent_allowed_exception` (
  `family_id` tinyint unsigned NOT NULL DEFAULT '0',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `peer_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `agent` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `family_id` (`family_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `agent_allowed_family`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agent_allowed_family` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `family` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `start_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `peer_id_pattern` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `peer_id_match_num` tinyint unsigned NOT NULL DEFAULT '0',
  `peer_id_matchtype` enum('dec','hex') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dec',
  `peer_id_start` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `agent_pattern` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `agent_match_num` tinyint unsigned NOT NULL DEFAULT '0',
  `agent_matchtype` enum('dec','hex') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dec',
  `agent_start` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `exception` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `allowhttps` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hits` mediumint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `allowedemails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `allowedemails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attachments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `width` smallint unsigned NOT NULL DEFAULT '0',
  `added` datetime DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `dlkey` char(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filetype` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `filesize` bigint unsigned NOT NULL DEFAULT '0',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `downloads` mediumint NOT NULL DEFAULT '0',
  `isimage` tinyint unsigned NOT NULL DEFAULT '0',
  `thumb` tinyint unsigned NOT NULL DEFAULT '0',
  `driver` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local',
  PRIMARY KEY (`id`),
  KEY `pid` (`userid`,`id`),
  KEY `dateline` (`added`,`isimage`,`downloads`),
  KEY `idx_delkey` (`dlkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendance` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0',
  `added` datetime NOT NULL,
  `points` int unsigned NOT NULL DEFAULT '0',
  `days` int unsigned NOT NULL DEFAULT '1',
  `total_days` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `attendance_added_index` (`added`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `attendance_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendance_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `points` int NOT NULL,
  `date` date NOT NULL,
  `is_retroactive` smallint NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendance_logs_uid_date_unique` (`uid`,`date`),
  KEY `attendance_logs_date_index` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `audiocodecs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audiocodecs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sort_index` tinyint unsigned NOT NULL DEFAULT '0',
  `mode` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `avps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `avps` (
  `arg` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value_s` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `value_i` int NOT NULL DEFAULT '0',
  `value_u` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`arg`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bannedemails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bannedemails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bans` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `added` datetime DEFAULT NULL,
  `addedby` mediumint unsigned NOT NULL DEFAULT '0',
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `first` bigint NOT NULL DEFAULT '0',
  `last` bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `first_last` (`first`,`last`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bitbucket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bitbucket` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `owner` mediumint unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `added` datetime DEFAULT NULL,
  `public` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blocks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `blockid` mediumint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userfriend` (`userid`,`blockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bonus_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bonus_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_type` int NOT NULL DEFAULT '0',
  `uid` int NOT NULL,
  `old_total_value` decimal(20,1) NOT NULL,
  `value` decimal(20,1) NOT NULL,
  `new_total_value` decimal(20,1) NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bonus_logs_uid_index` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bookmarks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookmarks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `torrentid` mediumint unsigned NOT NULL DEFAULT '0',
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid_torrentid` (`userid`,`torrentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `mode` tinyint unsigned NOT NULL DEFAULT '1',
  `class_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `sort_index` smallint unsigned NOT NULL DEFAULT '0',
  `icon_id` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mode_sort` (`mode`,`sort_index`)
) ENGINE=InnoDB AUTO_INCREMENT=410 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `caticons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `caticons` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `folder` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cssfile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `multilang` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `secondicon` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `designer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cheaters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cheaters` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `added` datetime DEFAULT NULL,
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `torrentid` mediumint unsigned NOT NULL DEFAULT '0',
  `uploaded` bigint unsigned NOT NULL DEFAULT '0',
  `downloaded` bigint unsigned NOT NULL DEFAULT '0',
  `anctime` mediumint unsigned NOT NULL DEFAULT '0',
  `seeders` mediumint unsigned NOT NULL DEFAULT '0',
  `leechers` mediumint unsigned NOT NULL DEFAULT '0',
  `hit` tinyint unsigned NOT NULL DEFAULT '0',
  `dealtby` mediumint unsigned NOT NULL DEFAULT '0',
  `dealtwith` tinyint(1) NOT NULL DEFAULT '0',
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cheaters_torrentid_index` (`torrentid`),
  KEY `cheaters_userid_index` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `chronicle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chronicle` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `added` datetime DEFAULT NULL,
  `txt` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `added` (`added`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `claims` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `torrent_id` int NOT NULL,
  `snatched_id` int NOT NULL,
  `seed_time_begin` bigint NOT NULL DEFAULT '0',
  `uploaded_begin` bigint NOT NULL DEFAULT '0',
  `last_settle_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `claims_uid_torrent_id_unique` (`uid`,`torrent_id`),
  KEY `claims_torrent_id_index` (`torrent_id`),
  KEY `claims_snatched_id_index` (`snatched_id`),
  KEY `claims_last_settle_at_index` (`last_settle_at`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `codecs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `codecs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sort_index` tinyint unsigned NOT NULL DEFAULT '0',
  `mode` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user` mediumint unsigned NOT NULL DEFAULT '0',
  `torrent` mediumint unsigned NOT NULL DEFAULT '0',
  `added` datetime DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `ori_text` text COLLATE utf8mb4_unicode_ci,
  `editedby` mediumint unsigned NOT NULL DEFAULT '0',
  `editdate` datetime DEFAULT NULL,
  `offer` mediumint unsigned NOT NULL DEFAULT '0',
  `request` int NOT NULL DEFAULT '0',
  `anonymous` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `torrent_id` (`torrent`,`id`),
  KEY `offer_id` (`offer`,`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `complain_replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `complain_replies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `complain` int NOT NULL,
  `userid` int NOT NULL DEFAULT '0',
  `added` datetime NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `complains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `complains` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `added` datetime NOT NULL,
  `answered` smallint NOT NULL DEFAULT '0',
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `countries` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `flagpic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `custom_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_menus` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int unsigned NOT NULL DEFAULT '0',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `target` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `sort` int NOT NULL DEFAULT '0',
  `enabled` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_menus_parent_id_index` (`parent_id`),
  KEY `custom_menus_sort_index` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `downloadspeed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `downloadspeed` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `exam_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exam_progress` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `exam_user_id` int NOT NULL,
  `exam_id` int NOT NULL,
  `uid` int NOT NULL,
  `torrent_id` int NOT NULL,
  `index` int NOT NULL,
  `init_value` bigint NOT NULL DEFAULT '0',
  `value` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_progress_exam_user_id_index` (`exam_user_id`),
  KEY `exam_progress_exam_id_index` (`exam_id`),
  KEY `exam_progress_uid_index` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `exam_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exam_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `exam_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `begin` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `progress` text COLLATE utf8mb4_unicode_ci,
  `is_done` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_users_uid_index` (`uid`),
  KEY `exam_users_exam_id_index` (`exam_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exams` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `begin` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `duration` int NOT NULL DEFAULT '0',
  `filters` text COLLATE utf8mb4_unicode_ci,
  `indexes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `is_discovered` tinyint NOT NULL DEFAULT '0',
  `priority` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `recurring` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` int NOT NULL DEFAULT '1',
  `success_reward_bonus` int NOT NULL DEFAULT '0',
  `fail_deduct_bonus` int NOT NULL DEFAULT '0',
  `max_user_count` int NOT NULL DEFAULT '0',
  `background_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'blue',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faq` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `link_id` smallint unsigned NOT NULL DEFAULT '0',
  `lang_id` smallint unsigned NOT NULL DEFAULT '6',
  `type` enum('categ','item') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'item',
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `flag` tinyint unsigned NOT NULL DEFAULT '1',
  `categ` smallint unsigned NOT NULL DEFAULT '0',
  `order` smallint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=476 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `files` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `torrent` mediumint unsigned NOT NULL DEFAULT '0',
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `size` bigint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `torrent` (`torrent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `forummods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forummods` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `forumid` smallint unsigned NOT NULL DEFAULT '0',
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `forumid` (`forumid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `forums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forums` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `sort` smallint unsigned NOT NULL DEFAULT '0',
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `minclassread` tinyint unsigned NOT NULL DEFAULT '0',
  `minclasswrite` tinyint unsigned NOT NULL DEFAULT '0',
  `postcount` int unsigned NOT NULL DEFAULT '0',
  `topiccount` int unsigned NOT NULL DEFAULT '0',
  `minclasscreate` tinyint unsigned NOT NULL DEFAULT '0',
  `forid` smallint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `friends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `friends` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `friendid` mediumint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userfriend` (`userid`,`friendid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `fun`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fun` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `added` datetime DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `status` enum('normal','dull','notfunny','funny','veryfunny','banned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `funds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `funds` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `usd` decimal(8,2) NOT NULL DEFAULT '0.00',
  `cny` decimal(8,2) NOT NULL DEFAULT '0.00',
  `user` mediumint unsigned NOT NULL DEFAULT '0',
  `added` datetime DEFAULT NULL,
  `memo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `funvotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `funvotes` (
  `funid` mediumint unsigned NOT NULL,
  `userid` mediumint unsigned NOT NULL,
  `added` datetime DEFAULT NULL,
  `vote` enum('fun','dull') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fun',
  PRIMARY KEY (`funid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `hit_and_runs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hit_and_runs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `torrent_id` int NOT NULL,
  `snatched_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `leech_time_no_seeder_begin` bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hit_and_runs_uid_torrent_id_unique` (`uid`,`torrent_id`),
  UNIQUE KEY `hit_and_runs_snatched_id_unique` (`snatched_id`),
  KEY `hit_and_runs_status_index` (`status`),
  KEY `hit_and_runs_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `invites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invites` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `inviter` mediumint unsigned NOT NULL DEFAULT '0',
  `invitee` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `hash` char(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_invited` datetime DEFAULT NULL,
  `valid` tinyint NOT NULL DEFAULT '1',
  `invitee_register_uid` int DEFAULT NULL,
  `invitee_register_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invitee_register_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expired_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pre_register_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pre_register_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`),
  KEY `idx_inviter` (`inviter`),
  KEY `invites_expired_at_index` (`expired_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `iplog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `iplog` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `access` datetime DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `count` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `iplog_access_index` (`access`),
  KEY `iplog_ip_index` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `isp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `isp` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `language` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `lang_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `flagpic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sub_lang` tinyint unsigned NOT NULL DEFAULT '1',
  `rule_lang` tinyint unsigned NOT NULL DEFAULT '0',
  `site_lang` tinyint unsigned NOT NULL DEFAULT '0',
  `site_lang_folder` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `trans_state` enum('up-to-date','outdate','incomplete','need-new','unavailable') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unavailable',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_main` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `location_sub` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `flagpic` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `end_ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `theory_upspeed` int unsigned NOT NULL DEFAULT '10',
  `practical_upspeed` int unsigned NOT NULL DEFAULT '10',
  `theory_downspeed` int unsigned NOT NULL DEFAULT '10',
  `practical_downspeed` int unsigned NOT NULL DEFAULT '10',
  `hit` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `login_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `ip` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `login_logs_uid_index` (`uid`),
  KEY `login_logs_ip_index` (`ip`),
  KEY `login_logs_country_index` (`country`),
  KEY `login_logs_city_index` (`city`),
  KEY `login_logs_client_index` (`client`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `loginattempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loginattempts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `added` datetime DEFAULT NULL,
  `banned` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `attempts` smallint unsigned NOT NULL DEFAULT '0',
  `type` enum('login','recover') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'login',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `magic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `magic` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `torrentid` int NOT NULL DEFAULT '0',
  `userid` int NOT NULL DEFAULT '0',
  `value` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_torrentid` (`torrentid`),
  KEY `idx_userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `medals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `get_type` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_large` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_small` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` int NOT NULL DEFAULT '0',
  `display_on_medal_page` int NOT NULL DEFAULT '1',
  `duration` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sale_begin_time` datetime DEFAULT NULL,
  `sale_end_time` datetime DEFAULT NULL,
  `inventory` int DEFAULT NULL,
  `bonus_addition_factor` float NOT NULL DEFAULT '0',
  `bonus_addition_duration` int NOT NULL DEFAULT '0' COMMENT '魔力加成有效天数，0 永久有效',
  `gift_fee_factor` float NOT NULL DEFAULT '0',
  `priority` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sort_index` tinyint unsigned NOT NULL DEFAULT '0',
  `mode` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `message_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `message_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language_id` int NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `message_templates_name_language_id_unique` (`name`,`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sender` mediumint unsigned NOT NULL DEFAULT '0',
  `receiver` mediumint unsigned NOT NULL DEFAULT '0',
  `added` datetime DEFAULT NULL,
  `subject` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `msg` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `unread` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `location` smallint NOT NULL DEFAULT '1',
  `saved` enum('no','yes') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `sender` (`sender`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=226 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `modpanel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modpanel` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `info` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `added` datetime DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `notify` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `added` (`added`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `client_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `oauth_auth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `skips_authorization` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `oauth_personal_access_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `oauth_providers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_providers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_secret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `authorization_endpoint_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_endpoint_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_info_endpoint_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_claim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username_claim` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_claim` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level_claim` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level_limit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL,
  `priority` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `oauth_providers_uuid_unique` (`uuid`),
  UNIQUE KEY `oauth_providers_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `offers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `offers` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `name` varchar(225) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `descr` text COLLATE utf8mb4_unicode_ci,
  `added` datetime DEFAULT NULL,
  `allowedtime` datetime DEFAULT NULL,
  `yeah` smallint unsigned NOT NULL DEFAULT '0',
  `against` smallint unsigned NOT NULL DEFAULT '0',
  `category` smallint unsigned NOT NULL DEFAULT '0',
  `comments` mediumint unsigned NOT NULL DEFAULT '0',
  `allowed` enum('allowed','pending','denied') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `offervotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `offervotes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `offerid` mediumint unsigned NOT NULL DEFAULT '0',
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `vote` enum('yeah','against') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yeah',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `overforums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `overforums` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `minclassview` tinyint unsigned NOT NULL DEFAULT '0',
  `sort` tinyint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `peers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `torrent` mediumint unsigned NOT NULL DEFAULT '0',
  `peer_id` binary(20) NOT NULL,
  `ip` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `port` smallint unsigned NOT NULL DEFAULT '0',
  `uploaded` bigint unsigned NOT NULL DEFAULT '0',
  `downloaded` bigint unsigned NOT NULL DEFAULT '0',
  `to_go` bigint unsigned NOT NULL DEFAULT '0',
  `seeder` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `started` datetime DEFAULT NULL,
  `last_action` datetime DEFAULT NULL,
  `prev_action` datetime DEFAULT NULL,
  `connectable` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `agent` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `finishedat` int unsigned NOT NULL DEFAULT '0',
  `downloadoffset` bigint unsigned NOT NULL DEFAULT '0',
  `uploadoffset` bigint unsigned NOT NULL DEFAULT '0',
  `passkey` char(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ipv4` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ipv6` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `is_seed_box` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_torrent_peer_user` (`torrent`,`peer_id`,`userid`),
  KEY `peers_userid_index` (`userid`),
  KEY `idx_peer` (`peer_id`),
  KEY `peers_last_action_index` (`last_action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `plugins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plugins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `package_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remote_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `installed_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL DEFAULT '-1',
  `status_result` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `plugins_package_name_unique` (`package_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pmboxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pmboxes` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `boxnumber` tinyint unsigned NOT NULL DEFAULT '2',
  `name` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pollanswers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pollanswers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `pollid` mediumint unsigned NOT NULL DEFAULT '0',
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `selection` tinyint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pollid` (`pollid`),
  KEY `userid` (`userid`),
  KEY `selection` (`selection`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `polls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `polls` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `added` datetime DEFAULT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option0` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option1` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option2` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option3` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option4` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option5` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option6` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option7` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option8` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option9` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option10` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option11` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option12` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option13` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option14` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option15` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option16` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option17` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option18` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option19` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `post_bookmarks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_bookmarks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `topicid` mediumint unsigned NOT NULL DEFAULT '0',
  `postid` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_bookmarks_userid_postid_unique` (`userid`,`postid`),
  KEY `post_bookmarks_userid_index` (`userid`),
  KEY `post_bookmarks_topicid_index` (`topicid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `topicid` mediumint unsigned NOT NULL DEFAULT '0',
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `added` datetime DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `ori_body` text COLLATE utf8mb4_unicode_ci,
  `editedby` mediumint unsigned NOT NULL DEFAULT '0',
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `topicid_id` (`topicid`,`id`),
  KEY `userid` (`userid`),
  KEY `added` (`added`),
  FULLTEXT KEY `body` (`body`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `processings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `processings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sort_index` tinyint unsigned NOT NULL DEFAULT '0',
  `mode` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `prolinkclicks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prolinkclicks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `ip` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `added` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `readposts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `readposts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `topicid` mediumint unsigned NOT NULL DEFAULT '0',
  `lastpostread` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `topicid` (`topicid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `regimages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `regimages` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `imagehash` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `imagestring` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `dateline` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reports` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `addedby` mediumint unsigned NOT NULL DEFAULT '0',
  `added` datetime DEFAULT NULL,
  `reportid` int unsigned NOT NULL DEFAULT '0',
  `type` enum('torrent','user','offer','request','post','comment','subtitle') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'torrent',
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `dealtby` mediumint unsigned NOT NULL DEFAULT '0',
  `dealtwith` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `requests` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `userid` int unsigned NOT NULL DEFAULT '0',
  `request` varchar(225) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `descr` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` int unsigned NOT NULL DEFAULT '0',
  `hits` int unsigned NOT NULL DEFAULT '0',
  `cat` int unsigned NOT NULL DEFAULT '0',
  `filledby` int unsigned NOT NULL DEFAULT '0',
  `torrentid` int unsigned NOT NULL DEFAULT '0',
  `finish` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `amount` int NOT NULL DEFAULT '0',
  `ori_descr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ori_amount` int NOT NULL DEFAULT '0',
  `added` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `finish, userid` (`finish`,`userid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `require_seed_torrents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `require_seed_torrents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `torrent_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `require_seed_torrents_torrent_id_unique` (`torrent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resreq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resreq` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reqid` int NOT NULL DEFAULT '0',
  `torrentid` int NOT NULL DEFAULT '0',
  `chosen` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `reqid` (`reqid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rules` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `lang_id` smallint unsigned NOT NULL DEFAULT '6',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `text` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `schools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `schools` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `searchbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `searchbox` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section_name` json DEFAULT NULL,
  `showsubcat` tinyint(1) NOT NULL DEFAULT '0',
  `showsource` tinyint(1) NOT NULL DEFAULT '0',
  `showmedium` tinyint(1) NOT NULL DEFAULT '0',
  `showcodec` tinyint(1) NOT NULL DEFAULT '0',
  `showstandard` tinyint(1) NOT NULL DEFAULT '0',
  `showprocessing` tinyint(1) NOT NULL DEFAULT '0',
  `showteam` tinyint(1) NOT NULL DEFAULT '0',
  `showaudiocodec` tinyint(1) NOT NULL DEFAULT '0',
  `catsperrow` smallint unsigned NOT NULL DEFAULT '7',
  `catpadding` smallint unsigned NOT NULL DEFAULT '25',
  `custom_fields` text COLLATE utf8mb4_unicode_ci,
  `custom_fields_display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `custom_fields_display` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `searchbox_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `searchbox_fields` (
  `id` int NOT NULL AUTO_INCREMENT,
  `searchbox_id` int NOT NULL,
  `field_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_id` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_searchbox_type_id` (`searchbox_id`,`field_type`,`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `secondicons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `secondicons` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `source` tinyint unsigned NOT NULL DEFAULT '0',
  `medium` tinyint unsigned NOT NULL DEFAULT '0',
  `codec` tinyint unsigned NOT NULL DEFAULT '0',
  `standard` tinyint unsigned NOT NULL DEFAULT '0',
  `processing` tinyint unsigned NOT NULL DEFAULT '0',
  `team` tinyint unsigned NOT NULL DEFAULT '0',
  `audiocodec` tinyint unsigned NOT NULL DEFAULT '0',
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `class_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `mode` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `seed_box_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seed_box_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` int NOT NULL,
  `uid` int NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `operator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bandwidth` int DEFAULT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_begin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_end` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_begin_numeric` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_end_numeric` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` int NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_allowed` int NOT NULL DEFAULT '0',
  `asn` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `seed_box_records_ip_begin_numeric_index` (`ip_begin_numeric`),
  KEY `seed_box_records_ip_end_numeric_index` (`ip_end_numeric`),
  KEY `seed_box_records_asn_index` (`asn`),
  KEY `seed_box_records_uid_index` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `autoload` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqe_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1554 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `shoutbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shoutbox` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `date` int unsigned NOT NULL DEFAULT '0',
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('sb','hb') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sb',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sitelog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sitelog` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `added` datetime DEFAULT NULL,
  `txt` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `security_level` enum('normal','mod') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `uid` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `added` (`added`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `snatched`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `snatched` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `torrentid` mediumint unsigned NOT NULL DEFAULT '0',
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `ip` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `port` smallint unsigned NOT NULL DEFAULT '0',
  `uploaded` bigint unsigned NOT NULL DEFAULT '0',
  `downloaded` bigint unsigned NOT NULL DEFAULT '0',
  `to_go` bigint unsigned NOT NULL DEFAULT '0',
  `seedtime` int unsigned NOT NULL DEFAULT '0',
  `leechtime` int unsigned NOT NULL DEFAULT '0',
  `last_action` datetime DEFAULT NULL,
  `startdat` datetime DEFAULT NULL,
  `completedat` datetime DEFAULT NULL,
  `finished` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `hit_and_run_id` bigint NOT NULL DEFAULT '0',
  `buy_log_id` bigint NOT NULL DEFAULT '0',
  `leech_time_no_seeder` bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `torrentid_userid` (`torrentid`,`userid`),
  KEY `userid` (`userid`),
  KEY `snatched_completedat_index` (`completedat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `social_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `provider_id` bigint NOT NULL,
  `provider_user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `social_accounts_user_id_provider_id_unique` (`user_id`,`provider_id`),
  UNIQUE KEY `social_accounts_provider_id_provider_user_id_unique` (`provider_id`,`provider_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sources` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sort_index` tinyint unsigned NOT NULL DEFAULT '0',
  `mode` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `staffmessages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staffmessages` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `sender` mediumint unsigned NOT NULL DEFAULT '0',
  `added` datetime DEFAULT NULL,
  `msg` text COLLATE utf8mb4_unicode_ci,
  `subject` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `answeredby` mediumint unsigned NOT NULL DEFAULT '0',
  `answered` tinyint(1) NOT NULL DEFAULT '0',
  `answer` text COLLATE utf8mb4_unicode_ci,
  `permission` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `staffmessages_permission_index` (`permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `standards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `standards` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sort_index` tinyint unsigned NOT NULL DEFAULT '0',
  `mode` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `stylesheets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stylesheets` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `addicode` text COLLATE utf8mb4_unicode_ci,
  `designer` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `torrent_id` mediumint unsigned NOT NULL DEFAULT '0',
  `lang_id` smallint unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `added` datetime DEFAULT NULL,
  `size` bigint unsigned NOT NULL DEFAULT '0',
  `uppedby` mediumint unsigned NOT NULL DEFAULT '0',
  `anonymous` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `hits` mediumint unsigned NOT NULL DEFAULT '0',
  `ext` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `torrentid_langid` (`torrent_id`,`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `suggest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suggest` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `keywords` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `adddate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `keywords` (`keywords`),
  KEY `adddate` (`adddate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sysoppanel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sysoppanel` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `info` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `padding` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1px 2px',
  `margin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0 4px 0 0',
  `border_radius` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `font_size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '12px',
  `font_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#ffffff',
  `description` text COLLATE utf8mb4_unicode_ci,
  `mode` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teams` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sort_index` tinyint unsigned NOT NULL DEFAULT '0',
  `mode` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `thanks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `thanks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `torrentid` mediumint unsigned NOT NULL DEFAULT '0',
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `thanks_torrentid_userid_unique` (`torrentid`,`userid`),
  KEY `thanks_userid_index` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `topics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `topics` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint unsigned NOT NULL DEFAULT '0',
  `subject` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `locked` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `forumid` smallint unsigned NOT NULL DEFAULT '0',
  `firstpost` int unsigned NOT NULL DEFAULT '0',
  `lastpost` int unsigned NOT NULL DEFAULT '0',
  `sticky` enum('no','yes') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `hlcolor` tinyint unsigned NOT NULL DEFAULT '0',
  `views` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `forumid_lastpost` (`forumid`,`lastpost`),
  KEY `forumid_sticky_lastpost` (`forumid`,`sticky`,`lastpost`),
  KEY `userid` (`userid`),
  KEY `subject` (`subject`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `torrent_buy_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `torrent_buy_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `torrent_id` int NOT NULL,
  `price` int NOT NULL,
  `channel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `torrent_buy_logs_uid_index` (`uid`),
  KEY `torrent_buy_logs_torrent_id_index` (`torrent_id`),
  KEY `torrent_buy_logs_price_index` (`price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `torrent_deny_reasons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `torrent_deny_reasons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hits` int NOT NULL DEFAULT '0',
  `priority` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `torrent_extras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `torrent_extras` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `torrent_id` int NOT NULL,
  `descr` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `media_info` text COLLATE utf8mb4_unicode_ci,
  `nfo` blob,
  `pt_gen` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `torrent_extras_torrent_id_unique` (`torrent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `torrent_operation_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `torrent_operation_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `torrent_id` int NOT NULL,
  `uid` int NOT NULL,
  `action_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `torrent_operation_logs_torrent_id_index` (`torrent_id`),
  KEY `torrent_operation_logs_uid_index` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `torrent_secrets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `torrent_secrets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `torrent_id` int NOT NULL DEFAULT '0',
  `secret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_torrent_id` (`torrent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `torrent_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `torrent_tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `torrent_id` int NOT NULL,
  `tag_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `torrent_tags_torrent_id_tag_id_unique` (`torrent_id`,`tag_id`),
  KEY `torrent_tags_tag_id_index` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `torrents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `torrents` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `info_hash` binary(20) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `save_as` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `small_descr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `category` smallint unsigned NOT NULL DEFAULT '0',
  `source` tinyint unsigned NOT NULL DEFAULT '0',
  `medium` tinyint unsigned NOT NULL DEFAULT '0',
  `codec` tinyint unsigned NOT NULL DEFAULT '0',
  `standard` tinyint unsigned NOT NULL DEFAULT '0',
  `processing` tinyint unsigned NOT NULL DEFAULT '0',
  `team` tinyint unsigned NOT NULL DEFAULT '0',
  `audiocodec` tinyint unsigned NOT NULL DEFAULT '0',
  `size` bigint unsigned NOT NULL DEFAULT '0',
  `added` datetime DEFAULT NULL,
  `type` enum('single','multi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'single',
  `numfiles` smallint unsigned NOT NULL DEFAULT '0',
  `comments` mediumint unsigned NOT NULL DEFAULT '0',
  `views` int unsigned NOT NULL DEFAULT '0',
  `hits` int unsigned NOT NULL DEFAULT '0',
  `times_completed` mediumint unsigned NOT NULL DEFAULT '0',
  `leechers` mediumint unsigned NOT NULL DEFAULT '0',
  `seeders` mediumint unsigned NOT NULL DEFAULT '0',
  `last_action` datetime DEFAULT NULL,
  `visible` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `banned` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `owner` mediumint unsigned NOT NULL DEFAULT '0',
  `sp_state` tinyint unsigned NOT NULL DEFAULT '1',
  `promotion_time_type` tinyint unsigned NOT NULL DEFAULT '0',
  `promotion_until` datetime DEFAULT NULL,
  `anonymous` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `url` int unsigned DEFAULT NULL,
  `pos_state` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `pos_state_until` datetime DEFAULT NULL,
  `cache_stamp` int NOT NULL DEFAULT '0',
  `picktype` enum('hot','classic','recommended','normal') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `picktime` datetime DEFAULT NULL,
  `last_reseed` datetime DEFAULT NULL,
  `hr` tinyint NOT NULL DEFAULT '0',
  `approval_status` int NOT NULL DEFAULT '0',
  `price` int NOT NULL DEFAULT '0',
  `pieces_hash` char(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `info_hash` (`info_hash`),
  KEY `visible_pos_id` (`visible`,`pos_state`,`id`),
  KEY `category_visible_banned` (`category`,`visible`,`banned`),
  KEY `visible_banned_pos_id` (`visible`,`banned`,`pos_state`,`id`),
  KEY `name` (`name`),
  KEY `owner` (`owner`),
  KEY `url` (`url`),
  KEY `torrents_pieces_hash_index` (`pieces_hash`),
  KEY `torrents_last_action_index` (`last_action`),
  KEY `torrents_added_index` (`added`),
  KEY `torrents_promotion_until_promotion_time_type_index` (`promotion_until`,`promotion_time_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `torrents_custom_field_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `torrents_custom_field_values` (
  `id` int NOT NULL AUTO_INCREMENT,
  `torrent_id` int NOT NULL DEFAULT '0',
  `custom_field_id` int NOT NULL DEFAULT '0',
  `custom_field_value` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_torrent_id` (`torrent_id`),
  KEY `idx_field_id` (`custom_field_id`),
  KEY `custom_field_value` (`custom_field_value`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `torrents_custom_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `torrents_custom_fields` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `type` enum('text','textarea','select','radio','checkbox','image') COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint NOT NULL DEFAULT '0',
  `is_single_row` int NOT NULL DEFAULT '0',
  `options` text COLLATE utf8mb4_unicode_ci,
  `help` text COLLATE utf8mb4_unicode_ci,
  `display` text COLLATE utf8mb4_unicode_ci,
  `priority` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `torrents_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `torrents_state` (
  `global_sp_state` tinyint unsigned NOT NULL DEFAULT '1',
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `deadline` datetime DEFAULT NULL,
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notice_days` int NOT NULL DEFAULT '0',
  `begin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tracker_urls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tracker_urls` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint NOT NULL DEFAULT '1',
  `is_default` tinyint NOT NULL DEFAULT '0',
  `priority` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `uploadspeed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `uploadspeed` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_ban_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_ban_logs` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL DEFAULT '0',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `operator` int NOT NULL DEFAULT '0',
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_medals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_medals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `medal_id` int NOT NULL,
  `expire_at` datetime DEFAULT NULL,
  `bonus_addition_expire_at` datetime DEFAULT NULL COMMENT '魔力加成过期时间，null 永不过期',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `priority` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_medals_uid_index` (`uid`),
  KEY `user_medals_medal_id_index` (`medal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_metas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_metas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deadline` datetime DEFAULT NULL,
  `meta_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_metas_uid_index` (`uid`),
  KEY `user_metas_meta_key_index` (`meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_modify_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_modify_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_modify_logs_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_passkeys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_passkeys` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint unsigned NOT NULL,
  `AAGUID` text COLLATE utf8mb4_unicode_ci,
  `credential_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `public_key` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `counter` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_require_seed_torrents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_require_seed_torrents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `torrent_id` int NOT NULL,
  `seed_time_begin` bigint NOT NULL,
  `uploaded_begin` bigint NOT NULL,
  `last_settlement_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_require_seed_torrents_user_id_torrent_id_unique` (`user_id`,`torrent_id`),
  KEY `user_require_seed_torrents_torrent_id_index` (`torrent_id`),
  KEY `user_require_seed_torrents_last_settlement_at_index` (`last_settlement_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `username_change_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `username_change_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `operator` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `change_type` int NOT NULL DEFAULT '0',
  `username_old` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username_new` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `passhash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `secret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `auth_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `status` enum('pending','confirmed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `added` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_access` datetime DEFAULT NULL,
  `last_home` datetime DEFAULT NULL,
  `last_offer` datetime DEFAULT NULL,
  `forum_access` datetime DEFAULT NULL,
  `last_staffmsg` datetime DEFAULT NULL,
  `last_pm` datetime DEFAULT NULL,
  `last_comment` datetime DEFAULT NULL,
  `last_post` datetime DEFAULT NULL,
  `last_browse` int unsigned NOT NULL DEFAULT '0',
  `last_music` int unsigned NOT NULL DEFAULT '0',
  `last_catchup` int unsigned NOT NULL DEFAULT '0',
  `editsecret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `privacy` enum('strong','normal','low') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `stylesheet` tinyint unsigned NOT NULL DEFAULT '1',
  `caticon` tinyint unsigned NOT NULL DEFAULT '1',
  `fontsize` enum('small','medium','large') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `info` text COLLATE utf8mb4_unicode_ci,
  `acceptpms` enum('yes','friends','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `commentpm` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `ip` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `class` tinyint unsigned NOT NULL DEFAULT '1',
  `max_class_once` tinyint NOT NULL DEFAULT '1',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `uploaded` bigint unsigned NOT NULL DEFAULT '0',
  `downloaded` bigint unsigned NOT NULL DEFAULT '0',
  `seedtime` bigint unsigned NOT NULL DEFAULT '0',
  `leechtime` bigint unsigned NOT NULL DEFAULT '0',
  `title` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `country` smallint unsigned NOT NULL DEFAULT '107',
  `notifs` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enabled` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `avatars` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `donor` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `donated` decimal(8,2) NOT NULL DEFAULT '0.00',
  `donated_cny` decimal(8,2) NOT NULL DEFAULT '0.00',
  `donoruntil` datetime DEFAULT NULL,
  `warned` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `warneduntil` datetime DEFAULT NULL,
  `noad` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `noaduntil` datetime DEFAULT NULL,
  `torrentsperpage` tinyint unsigned NOT NULL DEFAULT '0',
  `topicsperpage` tinyint unsigned NOT NULL DEFAULT '0',
  `postsperpage` tinyint unsigned NOT NULL DEFAULT '0',
  `clicktopic` enum('firstpage','lastpage') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'firstpage',
  `deletepms` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `savepms` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `showhot` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `showclassic` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `support` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `picker` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `stafffor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `supportfor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `pickfor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `supportlang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `passkey` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `promotion_link` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploadpos` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `forumpost` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `downloadpos` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `clientselect` tinyint unsigned NOT NULL DEFAULT '0',
  `signatures` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `signature` varchar(800) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `lang` smallint unsigned NOT NULL DEFAULT '6',
  `cheat` smallint NOT NULL DEFAULT '0',
  `download` int unsigned NOT NULL DEFAULT '0',
  `upload` int unsigned NOT NULL DEFAULT '0',
  `isp` tinyint unsigned NOT NULL DEFAULT '0',
  `invites` smallint unsigned NOT NULL DEFAULT '0',
  `invited_by` mediumint unsigned NOT NULL DEFAULT '0',
  `gender` enum('Male','Female','N/A') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N/A',
  `vip_added` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `vip_until` datetime DEFAULT NULL,
  `seedbonus` decimal(20,1) NOT NULL,
  `charity` decimal(10,1) NOT NULL DEFAULT '0.0',
  `parked` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `leechwarn` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `leechwarnuntil` datetime DEFAULT NULL,
  `lastwarned` datetime DEFAULT NULL,
  `timeswarned` tinyint unsigned NOT NULL DEFAULT '0',
  `warnedby` mediumint unsigned NOT NULL DEFAULT '0',
  `sbnum` smallint unsigned NOT NULL DEFAULT '70',
  `sbrefresh` smallint unsigned NOT NULL DEFAULT '120',
  `hidehb` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT 'no',
  `showimdb` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT 'yes',
  `showdescription` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT 'yes',
  `showcomment` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT 'yes',
  `showclienterror` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `showdlnotice` tinyint(1) NOT NULL DEFAULT '1',
  `tooltip` enum('minorimdb','medianimdb','off') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'off',
  `shownfo` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT 'yes',
  `timetype` enum('timeadded','timealive') COLLATE utf8mb4_unicode_ci DEFAULT 'timealive',
  `appendsticky` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT 'yes',
  `appendnew` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT 'yes',
  `appendpromotion` enum('highlight','word','icon','off') COLLATE utf8mb4_unicode_ci DEFAULT 'icon',
  `appendpicked` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT 'yes',
  `dlicon` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT 'yes',
  `bmicon` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT 'yes',
  `showsmalldescr` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `showcomnum` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT 'yes',
  `showlastcom` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT 'no',
  `showlastpost` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `pmnum` tinyint unsigned NOT NULL DEFAULT '10',
  `school` smallint unsigned NOT NULL DEFAULT '35',
  `showfb` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `page` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `two_step_secret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `seed_points` decimal(20,1) NOT NULL DEFAULT '0.0',
  `seed_points_per_hour` decimal(20,1) NOT NULL DEFAULT '0.0',
  `seed_bonus_per_hour` decimal(20,1) NOT NULL DEFAULT '0.0',
  `attendance_card` int NOT NULL DEFAULT '0',
  `offer_allowed_count` int NOT NULL DEFAULT '0',
  `seed_points_updated_at` datetime DEFAULT NULL,
  `seed_time_updated_at` datetime DEFAULT NULL,
  `provider_id` bigint NOT NULL DEFAULT '0',
  `seeding_torrent_count` int NOT NULL DEFAULT '0',
  `seeding_torrent_size` bigint NOT NULL DEFAULT '0',
  `last_announce_at` datetime DEFAULT NULL,
  `tracker_url_id` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `status_added` (`status`,`added`),
  KEY `last_access` (`last_access`),
  KEY `ip` (`ip`),
  KEY `class` (`class`),
  KEY `uploaded` (`uploaded`),
  KEY `downloaded` (`downloaded`),
  KEY `country` (`country`),
  KEY `enabled` (`enabled`),
  KEY `warned` (`warned`),
  KEY `passkey` (`passkey`),
  KEY `cheat` (`cheat`)
) ENGINE=InnoDB AUTO_INCREMENT=10003 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;


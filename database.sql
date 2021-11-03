SET foreign_key_checks = 0;
DROP TABLE IF EXISTS `holiday`;
CREATE TABLE `holiday` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `ondate` date DEFAULT NULL,
  `is_nation` tinyint(1) unsigned NOT NULL COMMENT 'apakah libur nasional',
  `is_office` tinyint(1) unsigned NOT NULL COMMENT 'apakah libur kerja',
  `is_offwork` tinyint(1) unsigned NOT NULL COMMENT 'apakah hari cuti?',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

INSERT INTO `holiday` VALUES (1,'Tahun Baru Masehi 2021','2021-01-01',1,1,0),(2,'Tahun Baru Imlek 2572 Kongzili','2021-02-12',1,1,0),(3,'Isra\' Mi\'raj Nabi Muhammad SAW','2021-03-11',1,0,0),(4,'Cuti Bersama Isra\' Mi\'raj Nabi Muhammad SAW','2021-03-12',1,0,0),(5,'Switch Isra\' Mi\'raj Nabi Muhammad SAW','2021-03-12',0,1,0),(6,'Hari Suci Nyepi Tahun Baru Saka 1943','2021-03-14',1,1,0),(7,'Wafat Isa Al Masih','2021-04-02',1,1,0),(8,'Hari Buruh Internasional','2021-05-01',1,1,0),(9,'Cuti Bersama Hari Raya Idul Fitri 1442 H','2021-05-12',1,1,1),(10,'Wafat Isa Al Masih','2021-05-13',1,1,0),(11,'Hari Raya Idul Fitri 1442 H','2021-05-13',1,1,0),(12,'Hari Raya Idul Fitri 1442 H','2021-05-14',1,1,0),(13,'Cuti Bersama Hari Raya Idul Fitri 1442 H','2021-05-17',1,1,1),(14,'Cuti Bersama Hari Raya Idul Fitri 1442 H','2021-05-18',1,1,1),(15,'Cuti Bersama Hari Raya Idul Fitri 1442 H','2021-05-19',1,1,1),(16,'Cuti Bersama Hari Raya Idul Fitri 1442 H','2021-05-20',0,1,1),(17,'Switch Hari Raya Waisak 2565','2021-05-21',0,1,0),(18,'Hari Raya Waisak 2565','2021-05-26',1,0,0),(19,'Hari Lahir Pancasila','2021-06-01',1,0,0),(20,'Switch Hari Lahir Pancasila','2021-06-04',0,1,0),(21,'Hari Raya Idul Adha 1442 H','2021-07-20',1,1,0),(22,'Tahun Baru Islam 1443 H','2021-08-10',1,1,0),(23,'Hari Kemerdekaan Republik Indonesia','2021-08-17',1,1,0),(24,'Maulid Nabi Muhammad SAW','2021-10-19',1,0,0),(25,'Switch Maulid Nabi Muhammad SAW','2021-10-22',0,1,0),(26,'Cuti Bersama Hari Raya Natal','2021-12-24',1,1,1),(27,'Hari Raya Natal','2021-12-25',1,1,0),(28,'Cuti Bersama Hari Raya Natal','2021-12-27',1,0,0);
DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `join` date DEFAULT NULL,
  `params` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

INSERT INTO `member` VALUES (1,'Ahmad Syafiq',NULL,'syafiq@fisip.net','2015-07-12',NULL),(2,'Munawwar Kholil',NULL,'kholil@fisip.net','2015-10-10',NULL),(3,'Muhammad Bagus Yulianto',NULL,'bagus@fisip.net','2018-01-15',NULL),(4,'Imam Turmuzdi',NULL,'imam@fisip.net','2018-03-20',NULL),(5,'Muhammad Abdul Mukhlis',NULL,'mukhlis@fisip.net','2019-11-10',NULL),(6,'Muhammad Nuruh Huda',NULL,'huda@fisip.net','2019-11-11',NULL),(7,'Catur Ilham',NULL,'catur@fisip.net','2020-10-07',NULL),(8,'Noor Wachid',NULL,'wachid@fisip.net','2021-02-02',NULL),(9,'Muhammad Rafiful Hana',NULL,'anang@fisip.net','2021-10-25',NULL);
DROP TABLE IF EXISTS `member_absent`;
CREATE TABLE `member_absent` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) unsigned NOT NULL,
  `type_id` tinyint(1) unsigned NOT NULL,
  `ondate` date DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  CONSTRAINT `member_absent_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

INSERT INTO `member_absent` VALUES (1,6,2,'2021-01-20','Ijin Acara Keluarga'),(2,2,2,'2021-03-03','Mudik'),(3,2,2,'2021-03-04','Mudik'),(4,2,2,'2021-03-05','Mudik'),(5,2,2,'2021-03-08','Mudik'),(6,2,2,'2021-03-09','Mudik'),(8,4,2,'2021-03-18','Saudara Nikah'),(9,5,1,'2021-05-28','Sakit'),(10,3,1,'2021-05-31','Sakit'),(11,3,1,'2021-06-01','Sakit'),(12,2,1,'2021-06-01','Sakit'),(13,3,1,'2021-06-02','Sakit'),(14,2,1,'2021-06-02','Sakit'),(15,6,1,'2021-06-02','Sakit'),(16,3,1,'2021-06-03','Sakit'),(17,6,1,'2021-06-08','Sakit'),(18,6,1,'2021-06-09','Sakit'),(19,6,3,'2021-06-15','Pasang Internet'),(20,2,2,'2021-06-28','Tes Laborat Istri'),(21,1,1,'2021-06-30','Sakit'),(22,2,2,'2021-07-09','Istri Melahirkan'),(23,2,2,'2021-07-12','Istri Melahirkan'),(24,2,2,'2021-07-28','Adik Nikahan'),(25,2,2,'2021-07-29','Aqiqah Anak'),(26,6,1,'2021-09-07','Sakit'),(27,7,3,'2021-10-28','Ambil Ijasah'),(28,5,2,'2021-11-02','Saudara Nikah');
DROP TABLE IF EXISTS `member_absent_type`;
CREATE TABLE `member_absent_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `member_absent_type` VALUES (1,'Sakit'),(2,'Keperluan Keluarga'),(3,'Keperluan Lain'),(4,'Ganti Hari Kerja');


SET foreign_key_checks = 1;

/* 4:10:48 PM localhost followsms */ ALTER TABLE `week` ADD `from` INT  NULL  DEFAULT NULL  AFTER `quarter_id`;
/* 4:10:51 PM localhost followsms */ ALTER TABLE `week` ADD `to` INT  NULL  DEFAULT NULL  AFTER `from`;
/* 4:43:13 PM localhost followsms */ ALTER TABLE `classes` ADD `classSubjects` TEXT  NULL  AFTER `stage_id`;

CREATE TABLE `class_ages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `academic_year_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `from` int(11) DEFAULT NULL,
  `to` int(11) DEFAULT NULL,
  `years` int(11) DEFAULT NULL,
  `months` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academic_year_id` (`academic_year_id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `class_ages_ibfk_1` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_year` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_ages_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

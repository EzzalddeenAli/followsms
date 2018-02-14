/* 7:47:41 AM localhost followsms */ ALTER TABLE `building` ADD `lft` INT(11)  NULL  DEFAULT NULL  AFTER `active`;
/* 7:47:49 AM localhost followsms */ ALTER TABLE `building` ADD `rght` INT(11)  NULL  DEFAULT NULL  AFTER `lft`;
/* 7:48:01 AM localhost followsms */ ALTER TABLE `building` ADD `depth` INT  NULL  DEFAULT NULL  AFTER `rght`;
/* 6:25:59 AM localhost followsms */ ALTER TABLE `sections` ADD FOREIGN KEY (`building_id`) REFERENCES `building` (`id`) ON DELETE CASCADE;


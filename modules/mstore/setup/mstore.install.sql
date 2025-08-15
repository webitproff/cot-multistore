/**
 * Mstore module DB installation (file mstore.install.sql)
 */

-- Права доступа для категорий
INSERT INTO `cot_auth` (`auth_groupid`, `auth_code`, `auth_option`, `auth_rights`, `auth_rights_lock`, `auth_setbyuserid`) VALUES
(1, 'mstore', 'e-scooter', 5, 250, 1),
(2, 'mstore', 'e-scooter', 1, 254, 1),
(3, 'mstore', 'e-scooter', 0, 255, 1),
(4, 'mstore', 'e-scooter', 7, 0, 1),
(5, 'mstore', 'e-scooter', 255, 255, 1),
(6, 'mstore', 'e-scooter', 135, 0, 1),
(1, 'mstore', 'e-bike', 5, 250, 1),
(2, 'mstore', 'e-bike', 1, 254, 1),
(3, 'mstore', 'e-bike', 0, 255, 1),
(4, 'mstore', 'e-bike', 7, 0, 1),
(5, 'mstore', 'e-bike', 255, 255, 1),
(6, 'mstore', 'e-bike', 135, 0, 1),
(1, 'mstore', 'system', 5, 250, 1),
(2, 'mstore', 'system', 1, 254, 1),
(3, 'mstore', 'system', 0, 255, 1),
(4, 'mstore', 'system', 7, 0, 1),
(5, 'mstore', 'system', 255, 255, 1),
(6, 'mstore', 'system', 135, 0, 1),
(1, 'mstore', 'tricycle', 5, 250, 1),
(2, 'mstore', 'tricycle', 1, 254, 1),
(3, 'mstore', 'tricycle', 0, 255, 1),
(4, 'mstore', 'tricycle', 7, 0, 1),
(5, 'mstore', 'tricycle', 255, 255, 1),
(6, 'mstore', 'tricycle', 135, 0, 1);

-- Таблица товаров (без полей msitem_file, msitem_url, msitem_size, msitem_count, msitem_filecount)
CREATE TABLE IF NOT EXISTS `cot_mstore` (
  `msitem_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `msitem_alias` varchar(255) NOT NULL DEFAULT '',
  `msitem_state` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `msitem_cat` varchar(255) NOT NULL,
  `msitem_title` varchar(255) NOT NULL,
  `msitem_desc` varchar(255) DEFAULT '',
  `msitem_metatitle` varchar(255) DEFAULT '',
  `msitem_metadesc` varchar(255) DEFAULT '',
  `msitem_text` MEDIUMTEXT DEFAULT NULL,
  `msitem_costdflt` decimal(10,2) NOT NULL DEFAULT '0.00',
  `msitem_parser` varchar(64) DEFAULT '',
  `msitem_ownerid` int UNSIGNED NOT NULL DEFAULT '0',
  `msitem_date` int UNSIGNED NOT NULL DEFAULT '0',
  `msitem_updated` int UNSIGNED NOT NULL DEFAULT '0',
  `msitem_count`  mediumint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`msitem_id`),
  KEY `msitem_cat` (`msitem_cat`),
  KEY `msitem_alias` (`msitem_alias`),
  KEY `msitem_date` (`msitem_date`),
  KEY `msitem_ownerid` (`msitem_ownerid`),
  KEY `msitem_title` (`msitem_title`)
);


-- Категории магазина
INSERT INTO `cot_structure` (`structure_area`, `structure_code`, `structure_path`, `structure_tpl`, `structure_title`, `structure_desc`, `structure_icon`, `structure_locked`, `structure_count`) VALUES
('mstore', 'e-scooter', '001', '', 'E-scooter', 'Electric scooters for urban mobility', '', 0, 0),
('mstore', 'system', '999', '', 'System', 'System category for internal use', '', 0, 0),
('mstore', 'e-bike', '002', '', 'E-bike', 'Electric bicycles for eco-friendly transport', '', 0, 0),
('mstore', 'tricycle', '003', '', 'Tricycle', 'Three-wheeled electric vehicles for stability', '', 0, 1);

-- Пример товара
INSERT INTO `cot_mstore` (`msitem_state`, `msitem_cat`, `msitem_title`, `msitem_desc`, `msitem_text`, `msitem_costdflt`, `msitem_ownerid`, `msitem_date`, `msitem_alias`, `msitem_count`) VALUES
(0, 'tricycle', 'Crosser TR7 Tricycle', 'Stable and modern electric tricycle', 'The Crosser TR7 electric tricycle is an excellent choice for those seeking stability, safety, and modern design for urban or suburban routes. This electric trike is designed with a focus on comfort and confidence while driving. Thanks to modern technologies and reliable components, the TR7 combines the best qualities of a scooter, moped, and convenient everyday transport.', '0.00', 1, UNIX_TIMESTAMP(), '', 227);

/**
 * Удаление модуля mstore из базы данных
 */

-- Удаление таблицы товаров
DROP TABLE IF EXISTS `cot_mstore`;

-- Удаление прав доступа mstore
DELETE FROM `cot_auth` WHERE `auth_code` = 'mstore';

-- Удаление категорий mstore
DELETE FROM `cot_structure` WHERE `structure_area` = 'mstore';
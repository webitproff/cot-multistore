-- mstoremailorder.install.sql
-- @package MStoreEmailOrder for CMF Cotonti Siena v.0.9.26 on PHP 8.4
-- Version=2.0.1
-- Date=2025-09-05
-- @author webitproff
-- @copyright Copyright (c) 2025 webitproff | https://github.com/webitproff
-- @license BSD License


-- Удаление таблиц с учётом зависимостей
DROP TABLE IF EXISTS `cot_mstoremailorder_complaint`;
DROP TABLE IF EXISTS `cot_mstoremailorder_history`;
DROP TABLE IF EXISTS `cot_mstoremailorders`;

-- Создание таблицы cot_mstoremailorders
CREATE TABLE `cot_mstoremailorders` (
  `order_id` int UNSIGNED NOT NULL,
  `order_item_id` int UNSIGNED NOT NULL,
  `order_user_id` int UNSIGNED DEFAULT NULL,
  `order_seller_id` int UNSIGNED NOT NULL,
  `order_buyer_nickname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_seller_nickname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_costdflt` decimal(10,2) NOT NULL DEFAULT '0.00',
  `order_quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `order_phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_email` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order_date` int UNSIGNED NOT NULL,
  `order_ip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_status` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `order_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Индексы и AUTO_INCREMENT для cot_mstoremailorders
ALTER TABLE `cot_mstoremailorders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `order_item_id` (`order_item_id`),
  ADD KEY `order_user_id` (`order_user_id`),
  ADD KEY `order_seller_id` (`order_seller_id`),
  MODIFY `order_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

-- Внешние ключи для cot_mstoremailorders
ALTER TABLE `cot_mstoremailorders`
  ADD CONSTRAINT `fk_order_item_id` FOREIGN KEY (`order_item_id`) REFERENCES `cot_mstore` (`msitem_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_seller_id` FOREIGN KEY (`order_seller_id`) REFERENCES `cot_users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_user_id` FOREIGN KEY (`order_user_id`) REFERENCES `cot_users` (`user_id`) ON DELETE SET NULL;

-- Создание таблицы cot_mstoremailorder_complaint
CREATE TABLE `cot_mstoremailorder_complaint` (
  `complaint_id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `complaint_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `complaint_date` int UNSIGNED NOT NULL,
  `complaint_status` tinyint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Индексы и AUTO_INCREMENT для cot_mstoremailorder_complaint
ALTER TABLE `cot_mstoremailorder_complaint`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`),
  MODIFY `complaint_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

-- Внешние ключи для cot_mstoremailorder_complaint
ALTER TABLE `cot_mstoremailorder_complaint`
  ADD CONSTRAINT `fk_complaint_order_id` FOREIGN KEY (`order_id`) REFERENCES `cot_mstoremailorders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_complaint_user_id` FOREIGN KEY (`user_id`) REFERENCES `cot_users` (`user_id`) ON DELETE SET NULL;

-- Создание таблицы cot_mstoremailorder_history
CREATE TABLE `cot_mstoremailorder_history` (
  `history_id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED NOT NULL,
  `status` tinyint UNSIGNED NOT NULL,
  `change_date` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Индексы и AUTO_INCREMENT для cot_mstoremailorder_history
ALTER TABLE `cot_mstoremailorder_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `order_id` (`order_id`),
  MODIFY `history_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

-- Внешние ключи для cot_mstoremailorder_history
ALTER TABLE `cot_mstoremailorder_history`
  ADD CONSTRAINT `fk_history_order_id` FOREIGN KEY (`order_id`) REFERENCES `cot_mstoremailorders` (`order_id`) ON DELETE CASCADE;
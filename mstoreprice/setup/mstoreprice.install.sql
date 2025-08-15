CREATE TABLE IF NOT EXISTS `cot_mstore_currency` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(10) NOT NULL UNIQUE,
  `title` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cot_mstore_price_types` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(20) NOT NULL UNIQUE,
  `title` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cot_mstore_prices` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT UNSIGNED NOT NULL,
  `currency_id` INT UNSIGNED NOT NULL,
  `price_type_id` INT UNSIGNED NOT NULL,
  `price` DECIMAL(12,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_price` (`product_id`, `currency_id`, `price_type_id`),
  FOREIGN KEY (`currency_id`) REFERENCES `cot_mstore_currency`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`price_type_id`) REFERENCES `cot_mstore_price_types`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Добавление тестовых валют
INSERT INTO `cot_mstore_currency` (`code`, `title`) VALUES
('USD', 'Доллар США'),
('EUR', 'Евро'),
('RUB', 'Российский рубль');

-- Добавление тестовых типов цен
INSERT INTO `cot_mstore_price_types` (`code`, `title`) VALUES
('retail', 'Розничная цена'),
('wholesale', 'Оптовая цена');

-- Добавление тестовых цен для товара с product_id=1 (Crosser TR7 Tricycle)
INSERT INTO `cot_mstore_prices` (`product_id`, `currency_id`, `price_type_id`, `price`) VALUES
(1, 1, 1, 599.99), -- USD, retail, 599.99
(1, 1, 2, 499.99), -- USD, wholesale, 499.99
(1, 2, 1, 549.99), -- EUR, retail, 549.99
(1, 2, 2, 449.99), -- EUR, wholesale, 449.99
(1, 3, 1, 59999.00), -- RUB, retail, 59999.00
(1, 3, 2, 49999.00); -- RUB, wholesale, 49999.00
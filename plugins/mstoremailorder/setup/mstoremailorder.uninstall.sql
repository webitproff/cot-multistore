
-- Удаляем таблицы, чтобы очистить структуру плагина MStoreEmailOrder
-- Удаляем в порядке, обратном созданию, из-за внешних ключей
DROP TABLE IF EXISTS `cot_mstoremailorder_history`;
DROP TABLE IF EXISTS `cot_mstoremailorder_complaint`;
DROP TABLE IF EXISTS `cot_mstoremailorders`;

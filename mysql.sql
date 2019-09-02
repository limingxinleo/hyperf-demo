CREATE TABLE `order` (
  `id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `total_fee` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单金额',
  `sku_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0初始化 1已支付 2已取消',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `INDEX_USER_ID` (`user_id`),
  KEY `INDEX_SKU_ID` (`sku_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单表';

CREATE TABLE `order_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `total_fee` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单金额',
  `sku_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0初始化 1已支付 2已取消',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `INDEX_ORDER_ID` (`order_id`),
  KEY `INDEX_USER_ID` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单日志表';
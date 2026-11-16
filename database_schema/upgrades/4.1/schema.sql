-- Nano (XNO) payments: one confirmed send block credits one credit purchase,
-- once. The block hash is the primary key and the cart id is unique, so the
-- check and the claim are one atomic INSERT, and the record outlives a restart.
CREATE TABLE IF NOT EXISTS `nano_payment_claims` (
  `block_hash`   CHAR(64)              NOT NULL,
  `order_id`     VARCHAR(50)           NOT NULL,
  `date_created` DATETIME              NOT NULL,
  PRIMARY KEY (`block_hash`),
  UNIQUE KEY `order_id` (`order_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARACTER SET utf8;

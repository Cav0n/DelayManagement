
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- order_delay
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `order_delay`;

CREATE TABLE `order_delay`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `order_id` INTEGER NOT NULL,
    `email_sent_at` DATETIME,
    `email_object` VARCHAR(255),
    `email_text` TEXT,
    `type` VARCHAR(100),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `order_delay_fi_75704f` (`order_id`),
    CONSTRAINT `order_delay_fk_75704f`
        FOREIGN KEY (`order_id`)
        REFERENCES `order` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;

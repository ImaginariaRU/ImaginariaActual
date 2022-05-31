CREATE TABLE IF NOT EXISTS `prefix_changemail` (
  `changemail_code`        varchar(32)         NOT NULL,
  `user_id`                int(11) unsigned    NOT NULL,
  `changemail_date_add`    datetime            NOT NULL,
  `changemail_date_used`   datetime                     DEFAULT '0000-00-00 00:00:00',
  `changemail_date_expire` datetime            NOT NULL,
  `changemail_mail_to`     varchar(250)        NOT NULL,
  `changemail_is_used`     tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`changemail_code`),
  UNIQUE KEY `user_id` (`user_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
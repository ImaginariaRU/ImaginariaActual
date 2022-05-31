CREATE TABLE IF NOT EXISTS `prefix_shistory` (
  `id`          int(11)      NOT NULL AUTO_INCREMENT,
  `user_id`     int(11)      NOT NULL,
  `enter_date`  date         NOT NULL,
  `enter_time`  time         NOT NULL,
  `session_key` varchar(32)  NOT NULL,
  `user_ip`     varchar(255) NOT NULL,
  `user_os`     varchar(255) NOT NULL,
  `user_agent`  varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`, `session_key`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  AUTO_INCREMENT = 1;

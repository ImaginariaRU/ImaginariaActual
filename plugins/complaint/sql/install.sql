DROP TABLE IF EXISTS `prefix_complaint`;

CREATE TABLE `prefix_complaint`
(
  `complaint_id`       int(9) unsigned NOT NULL AUTO_INCREMENT,
  `user_id`            int(9) unsigned NOT NULL,
  `topic_id`           int(9) unsigned NOT NULL,
  `complaint_text`     text            NOT NULL,
  `complaint_date_add` datetime        NOT NULL,
  `complaint_status`   int(1) DEFAULT '0',
  PRIMARY KEY (`complaint_id`),
  KEY `idxTarget` (`user_id`, `topic_id`),
  KEY `idxUser` (`user_id`),
  KEY `idxTopic` (`topic_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
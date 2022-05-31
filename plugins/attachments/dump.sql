simpCREATE TABLE `attachments` (
  `attachment_id`        INT          NOT NULL AUTO_INCREMENT,
  `topic_id`             INT,
  `user_id`              INT          NOT NULL,
  `attachment_name`      VARCHAR(250) NOT NULL,
  `attachment_size`      INT,
  `attachment_extension` VARCHAR(10),
  `attachment_url`       VARCHAR(500) NOT NULL,
  `attachment_form_id`   VARCHAR(13),
  PRIMARY KEY (`attachment_id`),
  UNIQUE INDEX `attachment_id_UNIQUE` (`attachment_id` ASC),
  INDEX `topic_id` (`topic_id` ASC),
  INDEX `user_id` (`user_id` ASC)
)
  DEFAULT CHARACTER SET = utf8
  COLLATE = utf8_general_ci;

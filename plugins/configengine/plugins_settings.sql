CREATE TABLE `prefix_plugins_settings` (

  `id`         INT          NOT NULL AUTO_INCREMENT,
  `pluginname` VARCHAR(100) NOT NULL,
  `serialized` LONGTEXT     NOT NULL,
  `autoload`   INT(1)       NOT NULL default 0,

  PRIMARY KEY (`id`),
  UNIQUE `pluginname` (`pluginname` ASC),
  INDEX `autoload` (`autoload` DESC)

)

  ENGINE = InnoDB
  DEFAULT CHARACTER SET = utf8
  COLLATE = utf8_general_ci;

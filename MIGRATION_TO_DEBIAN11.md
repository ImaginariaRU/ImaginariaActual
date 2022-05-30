# Патчи к БД

ALTER TABLE ls_wall MODIFY COLUMN last_reply varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;
ALTER TABLE ls_wall MODIFY COLUMN ip varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;

`Column 'comment_edit_count' cannot be null [query] => UPDATE ls_comment SET comment_edit_count= NULL, comment_edit_date='2022-05-29 20:50:50' WHERE comment_id = 241613 [context] => /var/www.imaginaria/imaginaria.actual/engine/modules/database/DbSimpleWrapper.class.php line 104 )`  

ALTER TABLE ls_comment MODIFY COLUMN comment_edit_count int(11) DEFAULT 0 NULL;

# Пакеты сделанные, но не внедренные

composer require imaginaria/datetimeparser      вместо  /var/www.imaginaria/imaginaria.actual/engine/lib/external/DateTime/DateTimeParser.php

composer require ivan1986/dbsimple              вместо  /var/www.imaginaria/imaginaria.actual/engine/lib/external/DbSimple                          ???
composer require livestreet/dbsimple        

livestreet/dklabcache                           вместо  /var/www.imaginaria/imaginaria.actual/engine/lib/external/DklabCache

composer require mr-lexx/hacker-console         вместо  /var/www.imaginaria/imaginaria.actual/engine/lib/external/HackerConsole

composer require ganlvtech/kcaptcha             вместо /var/www.imaginaria/imaginaria.actual/engine/lib/external/kcaptcha (только нужны доработки -> ajur-media/kcaptcha)

composer require phpmailer/phpmailer            вместо phpMailer







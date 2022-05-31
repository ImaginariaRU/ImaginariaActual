<?php
/**
 * Blacklist - проверка E-Mail пользователей на наличие в базах спамеров.
 *
 * Версия:    1.2
 *
 **/

$config = [
    // Проверять e-mail по базам
    'check_mail'        =>  true,

    // Порог для срабатывания проверки e-mail (не менее указанного значения)
    'check_mail_limit'  =>  1,

    // Проверять IP по базам
    'check_ip'          =>  false,

    // Порог для срабатывания проверки IP (не менее указанного значения)
    'check_ip_limit'    =>  1,

    // Строгая проверка IP (e-mail и IP должны быть в базе одновременно)
    'check_ip_exact'    =>  false,

    // Использовать базу сайта botscout.com
    'use_botscout_com'  =>  false,

    // Ключ для сайта botscout.com - http://botscout.com/getkey.htm
    'key_botscout_com'  =>  '',

    // Использовать базу сайта fspamlist.com
    'use_fspamlist_com' =>  false,

    // Ключ для сайта fspamlist.com - http://fspamlist.com/index.php?c=register
    'key_fspamlist_com' =>  '',

    // Проверять e-mail и IP при авторизации
    'check_authorization'   => true,

    // Белый список доменных зон (без точек)
    'whitelist_zones'       =>  [],

    // Черный список доменных зон (без точек)
    'blacklist_zones'       =>  [],

    // Белый список пользователей (логины)
    'whitelist_users_name'  =>  [],

    // Черный список пользователей (логины)
    'blacklist_users_name'  =>  [],

    // Белый список пользователей (e-mail)
    'whitelist_users_mail'  =>  [],

    // Черный список пользователей (e-mail)
    'blacklist_users_mail'  =>  [],

    // Белый список пользователей (IP)
    'whitelist_users_ip'    =>  [],

    // Черный список пользователей (IP)
    'blacklist_users_ip'    =>  [],

    // Время в секундах, в течении которого данные о предыдущей проверке пользователя считаются корректными
    'recheck_time'          =>  60 * 60 * 24 * 1,
];

/* === Черный и белый списки доменов - это текстовые файлы в /config/ === */

// Белый список доменов
$config['whitelist_filename'] = Config::Get('path.root.server') . '/config/plugins/blacklist/whitelist.txt';

$config['whitelist_domains'] =
    is_readable($config['whitelist_filename'])
    ? file( $config['whitelist_filename'], FILE_IGNORE_NEW_LINES )
    : [];

// Черный список доменов
$config['blacklist_filename'] = Config::Get('path.root.server') . '/config/plugins/blacklist/blacklist.txt';

$config['blacklist_domains'] =
    is_readable($config['blacklist_filename'])
    ? file( $config['blacklist_filename'], FILE_IGNORE_NEW_LINES )
    : [];

Config::Set('db.table.blacklist', '___db.table.prefix___blacklist');

return $config;
<?php
/*
	Configengine plugin
	(P) PSNet, 2008 - 2013
	http://psnet.lookformp3.net/
	http://livestreet.ru/profile/PSNet/
	https://catalog.livestreetcms.com/profile/PSNet/
	http://livestreetguide.com/developer/PSNet/
*/

$config = array();

// Количество плагинов в списке на странице администрирования конфигов плагинов
$config ['Plugins_Count_Per_Page'] = 20;

// ---

// plugin`s settings table name
$config ['table'] ['config'] = '___db.table.prefix___plugins_settings';

// router settings
$config ['url'] = 'configengine';
Config::Set('router.page.' . $config ['url'], 'PluginConfigengine_ActionConfigengine');

// prefix for custom plugin`s key-values data
$config ['plugins_data_signature_prefix'] = '_data_';

// AC system
$config ['AC_Exclude_Config_Keys'] = array(
    // исключения для локального конфига
    'table.config',
    'plugins_data_signature_prefix',
    'url'
);

$config['hook_priority'] = [
    'lang_init_start'     =>  PHP_INT_MAX,
];

return $config;

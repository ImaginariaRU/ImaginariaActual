<?php

/*-------------------------------------------------------
*
*   CMS LiveStreet (1.x)
*   Plugin Complaint v1.0.0
*   Сделано руками @ Сергей Сарафанов (sersar)
*   ВК: vk.com/sersar | E-mail: sersar@ukr.net
*
---------------------------------------------------------
*/

/**
 * Настройки
 */
$config = array();

// Отправлять ли администратору уведомление о жалобе?
$config['send_admin_mail'] = true;

// E-mail администратора
$config['admin_mail'] = 'admin@admin.adm';

// Максимальная длина текста жалобы
$config['text']['max_length'] = 250;

// Минимальная длина текста жалобы
$config['text']['min_length'] = 3;

// Сколько жалоб выводить на страницы админки
$config['per_page'] = 20;

/*-------------------- СТРОЧКИ НИЖЕ НЕ ТРОГАТЬ! --------------------*/

Config::Set('db.table.complaint.complaint', '___db.table.prefix___complaint');
Config::Set('router.page.complaint', 'PluginComplaint_ActionComplaint');

return $config;
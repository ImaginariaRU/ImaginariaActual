<?php
/*
  Urlredirect plugin
  (P) PSNet, 2008 - 2012
  http://psnet.lookformp3.net/
  http://livestreet.ru/profile/PSNet/
  http://livestreetcms.com/profile/PSNet/
*/

$config = array();

// Открывать ли все ссылки на другие сайты в новом окне
$config ['Open_All_Foreign_Links_In_New_Window'] = true;

// Добавить ко всем ссылкам на сторонние сайты класс, если не нужно - оставить пустым
$config ['Add_Special_Class_For_All_Foreign_Links'] = 'foreignlinks'; // class="foreignlinks"

// Белый список сайтов - позволяет всегда переходить к ним без окна предупреждения.
// Ссылки прописывать БЕЗ "http://" и последнего слеша, сайты с "www." и без него - разные.
// Допускается регэкспа в выражениях (выполняется preg_match для ссылки).
$config ['Always_Trusted_Sites'] = array(
    '/^(www\.)?google.com$/i',
    '/^(www\.)?docs.google.com$/i',
    '/^(www\.)?youtube.com$/i',
    '/^(www\.)?youtu.be$/i',
    '/^(www\.)?yandex.ru$/i',
    '/^(www\.)?livestreet.ru$/i',
    '/^(www\.)?livestreetcms.com$/i',
    '/^(.*)(\.)?lookformp3.net$/i',
    '/^(www\.)?playmp3.org.ua$/i',
    '/^(www\.)?dropbox.com$/i',
    '/^(www\.)?kickstarter.com$/i',
    '/^(www\.)?rpg-world.org$/i',
    '/^(www\.)?wizards.com$/i',
    '/^(www\.)?kramaran.ru$/i',
    '/^(www\.)?rutracker.org$/i',
    '/^(www\.)?nntt.org$/i',
    '/^(www\.)?ru.rpg.wikia.com$/i',
    '/^(www\.)?scpfoundation.ru$/i',
    '/^(www\.)?new-age.kingdoms.spb.ru$/i',
    '/^(.*)$/i',
);

// Черный список сайтов - всегда блокировать переход с предупреждением
$config ['Sites_With_Bad_Reputation'] = array(
    '/^(www\.)?accessdenied.com$/i',
);

// Запрещать ли индексацию страницы выхода (в т.ч. и внешних ссылок) для поисковиков, значение мета тега robots.
// Если хотите открыть индексацию внешних ссылок - оставьте пустое значение (мета тег не будет добавлен).
// Можно заносить и свои параметры.
$config ['Meta_Robots'] = 'noindex, nofollow';  // noindex, nofollow

// Оборачивать все внешние ссылки в base64. Также это может помочь решить проблему с некоторыми серверами на nginx.
// Если включено, то новые ссылки будут кодироватся, но и старые (не кодированные) также будут работать.
$config ['Wrap_Links_In_Base64'] = true;

// Проверяет поле HTTP_REFERER на страницах выхода.
// Если на страницу ссылался не ваш сайт, то вместо ссылки на внешний сайт будет проставлена ссылка на ваш сайт.
// Позволяет защитится от черного СЕО, когда подставляют ссылки чужих сайтов в адресную строку
// и пингуют их (ваш сайт) с помощью специальных сервисов, наращивая таким образом себе пузомерки на счет вашего сайта.
$config ['Check_For_Referer'] = true;

// Время (секунд), через которое будет осуществлен автоматический переход по ссылке в окне выхода.
// Если не нужно, то установить в минусовое число (-1).
$config ['Time_For_Auto_Going'] = 5; // sec

// "Подсвечивать" внешние ссылки специальной иконкой после самой ссылки
$config ['Highlight_External_Links'] = false;

//
// --- Дальше редактировать ничего не надо ---
//

// Добавочный УРЛ для ссылки с нормальным переходом (не черный и не белый списки)
// Только символы a-z
$config ['URL_For_Good_Sites'] = 'redirect';

// Добавочный УРЛ для ссылки с черного списка
// Только символы a-z
$config ['URL_For_Bad_Sites'] = 'dontgo';

// ---

Config::Set('router.page.' . $config ['URL_For_Good_Sites'], 'PluginUrlredirect_ActionUrlredirect');
Config::Set('router.page.' . $config ['URL_For_Bad_Sites'], 'PluginUrlredirect_ActionBadUrlredirect');

return $config;

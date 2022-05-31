<?php
/* ---------------------------------------------------------------------------
 * @Plugin Name: reCAPTCHA v2
 * @Description: Replaces the standard captcha for reCAPTCHA v2
 *
 * @LiveStreet Version: 1.0.4
 * @Plugin Version:	1.1
 * ----------------------------------------------------------------------------
*/

// Ключи можно получить здесь https://www.google.com/recaptcha/admin/create

/*
Конфиг описывается в файле /config/config.local.php так

$config['recaptcha'] = [
    'public_key'    =>  '',
    'private_key'   =>  ''
];

*/

// Получение значений из конфига `config.local.php`
$config = Config::Get('recaptcha');

return $config;

// хотя это двойная работа, в общем-то. Правильно объявлять $config['plugin']['recaptcha'] = ... глобально, а тут ничего не делать
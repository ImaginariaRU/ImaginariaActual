<?php
/* ---------------------------------------------------------------------------
 * @Plugin Name: reCAPTCHA v2
 * @Description: Replaces the standard captcha for reCAPTCHA v2
 *
 * @LiveStreet Version: 1.0.4
 * @Plugin Version:	1.1
 * ----------------------------------------------------------------------------
*/

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die(__FILE__ . ' : Hacking attemp!');
}


class PluginRecaptcha extends Plugin
{

    protected $aInherits = array(
        'entity' => array('ModuleUser_EntityUser')
    );

    public function Activate()
    {
        return true;
    }

    public function Deactivate()
    {
        return true;
    }

    public function Init()
    {
        $this->Viewer_AppendScript(Plugin::GetTemplatePath(__CLASS__) . "js/script.js");
        Config::Set('module.user.captcha_use_registration', false);
    }
}


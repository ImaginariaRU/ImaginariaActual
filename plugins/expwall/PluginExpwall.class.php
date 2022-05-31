<?php

/* -------------------------------------------------------
 *
 *   LiveStreet (v1.x)
 *   Plugin Expanded wall (v.0.3)
 *   Copyright © 2011 Bishovec Nikolay
 *
 * --------------------------------------------------------
 *
 *   Plugin Page: http://netlanc.net
 *   Contact e-mail: netlanc@yandex.ru
 *
  ---------------------------------------------------------
 */

if (!class_exists('Plugin')) {
    die(__FILE__ . ' : Hacking attemp!');
}


class PluginExpwall extends Plugin
{

    public $aDelegates = array(
        'template' => array(
            'actions/ActionProfile/wall.tpl' => '_actions/ActionWall/wall.tpl',
        ),
    );

    // Активация плагина
    public function Activate()
    {
        return true;
    }


    public function Deactivate()
    {
        return true;
    }


    // Инициализация плагина
    public function Init()
    {
        $this->Viewer_AppendStyle(Plugin::GetTemplatePath(__CLASS__) . "/css/style.css"); // Добавление своего CSS
        $this->Viewer_Assign('sTPWall', rtrim(Plugin::GetTemplatePath('expwall'), '/'));
        $this->Viewer_Assign('sTWWall', rtrim(Plugin::GetTemplateWebPath('expwall'), '/'));
    }
}


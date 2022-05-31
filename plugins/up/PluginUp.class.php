<?php

if (!class_exists('Plugin')) {
    die(__FILE__ . ' : Hacking attemp!');
}

class PluginUp extends Plugin
{
    /**
     * Активация плагина
     */
    public function Activate()
    {
        return true;
    }

    /**
     * Деактивация плагина
     */
    public function Deactivate()
    {
        return true;
    }

    /**
     * Инициализация плагина
     */
    public function Init()
    {
        $this->Lang_AddLangJs(array('plugin.up.button_up'));
        $this->Viewer_AppendScript(Plugin::GetTemplateWebPath(__CLASS__) . 'js/up.js');//getTemplatePathPlugin()
        $this->Viewer_AppendStyle(Plugin::GetTemplateWebPath(__CLASS__) . 'css/up.css');
        return true;
    }
}

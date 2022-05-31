<?php

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die(__FILE__ . ' : Hacking attemp!');
}


class PluginUsername extends Plugin
{

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
        $this->Viewer_AppendScript(Plugin::GetTemplatePath(__CLASS__) . "/js/jquery.ba-replacetext.min.js");
    }
}


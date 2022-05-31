<?php

/*-------------------------------------------------------
*
*   CMS LiveStreet (1.x)
*   Plugin Complaint v1.1.0
*   Сделано руками @ Сергей Сарафанов (sersar)
*   ВК: vk.com/sersar | E-mail: sersar@ukr.net
*
---------------------------------------------------------
*/

if (!class_exists('Plugin')) {
    die('Hacking attemp!');
}

class PluginComplaint extends Plugin
{
    public function Activate()
    {
        $this->Cache_Clean();
        if (!$this->isTableExists('prefix_complaint')) {
            $resutls = $this->ExportSQL(dirname(__FILE__) . '/sql/install.sql');
        }
        return true;
    }

    public function Init()
    {
        $this->Viewer_AppendScript(Plugin::GetTemplatePath(__CLASS__) . 'js/script.js');
        $this->Viewer_AppendStyle(Plugin::GetTemplateWebPath(__CLASS__) . 'css/style.css');
    }
}
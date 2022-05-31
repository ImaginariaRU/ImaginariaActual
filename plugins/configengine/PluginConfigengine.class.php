<?php
/*
	Configengine plugin
	(P) PSNet, 2008 - 2013
	http://psnet.lookformp3.net/
	http://livestreet.ru/profile/PSNet/
	https://catalog.livestreetcms.com/profile/PSNet/
	http://livestreetguide.com/developer/PSNet/
*/

if (!class_exists('Plugin')) {
    die ('Kokobubble!');
}

class PluginConfigengine extends Plugin
{

    public function Activate()
    {
        if (!$this->isTableExists('prefix_plugins_settings')) {
            $this->ExportSQL(dirname(__FILE__) . '/plugins_settings.sql');
        }
        return true;
    }

    // ---

    public function Init()
    {
    }

}

?>
<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: changemail
 * @Description: Change mail plugin
 * @Author URI: http://lsmods.ru
 * @LiveStreet Version: 0.5.1
 * @Plugin Version:	1.0.0
 * ----------------------------------------------------------------------------
 */

if (!class_exists('Plugin')) {
    die(__FILE__ . ' : Hacking attemp!');
}


class Pluginchangemail extends Plugin
{

    protected $aInherits = array(
        'action' => array('ActionSettings', 'ActionLogin'),
        'module' => array('ModuleUser'),
        'mapper' => array('ModuleUser_MapperUser' => '_ModuleUser_MapperUser')
    );

    public function Activate()
    {
        if (file_exists($file = dirname(__FILE__) . "/install.sql"))
            $this->ExportSQL($file);
        return true;
    }

    public function Deactivate()
    {
        return true;
    }

    public function Init()
    {

    }
}

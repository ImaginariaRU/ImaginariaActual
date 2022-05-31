<?php

if (!class_exists('Plugin')) {
    die(__FILE__ . ' : Hacking attemp!');
}


class PluginSHistory extends Plugin
{

    protected $aInherits = array(
        'module' => array('Sessions')
    );

    public function Init()
    {
        if ($this->User_IsAuthorization()) {
            $this->oUserCurrent = $this->User_GetUserCurrent();
            $this->PluginSHistory_Sessions_update($this->oUserCurrent);
        }
        return true;
    }

    public function Activate()
    {
        $this->ExportSQL(dirname(__FILE__) . '/install.sql');
        return true;
    }

    public function Deactivate()
    {
        $this->ExportSQL(dirname(__FILE__) . '/delete.sql');
        return true;
    }
}

<?php
/*
  Delayed post plugin
  (P) Rafrica.net Studio, 2010 - 2012
  http://we.rafrica.net/
*/

class PluginDelayedpost_ActionDelayedpost extends ActionPlugin
{
    protected $oUserCurrent = null;

    public function Init()
    {
        if ($this->User_IsAuthorization()) {
            $this->oUserCurrent = $this->User_GetUserCurrent();
        } else return Router::Action('error');

        $this->SetDefaultEvent('delayed');
    }

    //*****************************************************************************************
    protected function RegisterEvent()
    {
        $this->AddEvent('delayed', 'EventDelayed');
    }

    //******************************************************************************************
    protected function EventDelayed()
    {
        $aTopics = $this->PluginDelayedpost_Delayedpost_GetDelayedTopics($this->oUserCurrent->getId());
        $aCollection = $aTopics['collection'];

        $this->Viewer_Assign('aTopics', $aCollection);
        $this->Viewer_Assign('sMenuItemSelect', 'add_blog');
        $this->Viewer_Assign('sMenuItemSelectForDP', 'delayed');
    }

}

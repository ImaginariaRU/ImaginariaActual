<?php

class PluginSHistory_ActionSessions extends ActionSettings
{

    protected $sMenuSubItemSelect = 'sessions';

    public function Init()
    {
        /**
         * Проверяем авторизован ли юзер
         */
        if (!$this->User_IsAuthorization()) {
            $this->Message_AddErrorSingle($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            return Router::Action('error');
        }
        $this->oUserCurrent = $this->User_GetUserCurrent();
        $this->Viewer_Assign('sTemplateWebPathPlugin', Plugin::GetTemplateWebPath(__CLASS__));
        $this->SetDefaultEvent('index');
    }

    function EventIndex()
    {
        $this->Viewer_AddHtmlTitle('История сессий');

        $userBrowser = $this->PluginSHistory_Sessions_getUserBrowser();
        $userOs = $this->PluginSHistory_Sessions_getUserOs();

        $gTest = '';
        $this->Viewer_Assign('gTest', $gTest);

        $this->Viewer_Assign('aHistoryRows', $this->PluginSHistory_Sessions_getSessionsRows($this->oUserCurrent->getId()));
    }

    protected function RegisterEvent()
    {
        $this->AddEvent('index', 'EventIndex');
    }

}

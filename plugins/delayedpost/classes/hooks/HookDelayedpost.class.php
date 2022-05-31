<?php
/*
  Delayed post plugin
  (P) Rafrica.net Studio, 2010 - 2012
  http://we.rafrica.net/
*/

if (!class_exists('Plugin')) {
    die(__FILE__ . ' : Hacking attemp!');
}


class PluginDelayedpost_HookDelayedpost extends Hook
{
    const ConfigKey = 'delayedpost';
    const HooksArray = [
        'module_topic_addtopic_before'          => 'InitHoldAdd',
        'module_topic_updatetopic_before'       => 'InitHoldUpdate',
        'module_topic_updatetopic_after'        => 'DoHoldUpdate',
        'init_action'                           => 'AssignRequest',
        'template_form_add_topic_topic_end'     => 'TopicDateField',
        'template_form_add_topic_question_end'  => 'TopicDateField',
        'template_form_add_topic_link_end'      => 'TopicDateField',
        'template_form_add_topic_photoset_end'  => 'TopicDateField',
        'template_menu_topic_action'            => 'MenuTopicAction',
        'template_body_begin'                   => 'HookBodyBegin',
        'topic_show'                            => 'ShowTopic',
        'template_main_menu_item'               => 'MainMenu'
    ];

    /**
     *
     */
    public function RegisterHook()
    {
        $plugin_config_key = $this::ConfigKey;
        foreach ($this::HooksArray as $hook => $callback) {
            $this->AddHook(
                $hook,
                $callback,
                __CLASS__,
                Config::Get("plugin.{$plugin_config_key}.hook_priority.{$hook}") ?? 1
            );
        }
    }

    /**
     * @param $aParams
     */
    public function ShowTopic($aParams)
    {
        $oTopic = $aParams['oTopic'];
        $iAuthorId = $oTopic->getUserId();

        $sDateNow = date("Y-m-d H:i:s");
        $sTopicAddDate = $oTopic->getDateAdd();

        if (strtotime($sTopicAddDate) > strtotime($sDateNow)) {
            if ($this->User_IsAuthorization()) {
                $oUserCurrent = $this->User_GetUserCurrent();
                if (($oUserCurrent->getId() !== $iAuthorId) and (!$oUserCurrent->isAdministrator())) {
                    $this->Error();
                }
            } else $this->Error();
        }
    }

    /**
     * Запрещаем всем кроме автора и админа просматривать отложенный топик
     *
     * @param null $sMessage
     * @return mixed
     */
    protected function Error($sMessage = null)
    {
        if ($sMessage) $this->Message_AddErrorSingle($sMessage, '', true);
        return Router::Location(Router::GetPath('error'));
    }

    /**
     *
     */
    public function AssignRequest()
    {
        if (isPost('delayedpost_topic_date_add')) {
            $_REQUEST['topic_date_add'] = getRequest('delayedpost_topic_date_add', '', 'post');
        }
    }

    /**
     *
     */
    public function TopicDateField()
    {
        if (!$this->Can()) {
            return;
        }
        $sActionEvent = Router::GetActionEvent();
        $iTopicId = (int)Router::GetParam(0);
        $ThisTopicDate = '';
        if ($sActionEvent == 'edit' && $iTopicId) {
            $oTopic = $this->Topic_GetTopicById($iTopicId);
            if ($oTopic) {
                $ThisTopicDate = $oTopic->getDateAdd();
                $_REQUEST ['topic_date_add'] = $ThisTopicDate;
            }
        }
        $oViewer = $this->Viewer_GetLocalViewer();

        if (empty ($ThisTopicDate)) {
            $ThisTopicDate = date("Y-m-d H:i:s");
        }

        if (!empty ($ThisTopicDate)) {
            $this->Viewer_Assign('aTopicYear', mb_substr($ThisTopicDate, 0, 4, 'utf-8')); // need to be accessable in sidebar
            $this->Viewer_Assign('aTopicMonth', mb_substr($ThisTopicDate, 5, 2, 'utf-8'));
            $this->Viewer_Assign('aTopicDay', mb_substr($ThisTopicDate, 8, 2, 'utf-8'));
            $this->Viewer_Assign('aTopicHour', mb_substr($ThisTopicDate, 11, 2, 'utf-8'));
            $this->Viewer_Assign('aTopicMinute', mb_substr($ThisTopicDate, 14, 2, 'utf-8'));
            $oViewer->Assign('oUserCurrent', $this->User_GetUserCurrent());
        }
        return $oViewer->Fetch(Plugin::GetTemplatePath(__CLASS__) . 'delayedpost.form.tpl');
    }

    /**
     * @return bool|mixed
     */
    protected function Can()
    {
        $bCan = false;

        if ($this->User_IsAuthorization()) {
            $oUserCurrent = $this->User_GetUserCurrent();
            $bCan = max($oUserCurrent->isAdministrator(),
                Config::Get('plugin.delayedpost.UserAllowed')
            );
        }

        return $bCan;
    }

    /**
     * @param $aVars
     */
    public function InitHoldUpdate($aVars)
    {
        if (!$this->Can()) {
            return;
        }
        if ($sDateAdd = getRequest('delayedpost_topic_date_add', '', 'post')) {
            $GLOBALS['HOLD_OTOPIC'] = &$aVars[0];
            $GLOBALS['HOLD_OTOPIC']->setDateAdd($sDateAdd);
            unset($_POST['delayedpost_topic_date_add']);
        }
    }

    /**
     * @param $aVars
     * @return mixed
     */
    public function DoHoldUpdate($aVars)
    {
        if (!$aVars['result'] || !isset($GLOBALS['HOLD_OTOPIC'])) {
            return $aVars['result'];
        }
        $oTopic = $GLOBALS['HOLD_OTOPIC'];
        unset($GLOBALS['HOLD_OTOPIC']);
        $this->Topic_UpdateTopic($oTopic);
    }

    /**
     * @param $aVars
     */
    public function InitHoldAdd($aVars)
    {
        if (!$this->Can()) {
            return;
        }
        if ($sDateAdd = getRequest('delayedpost_topic_date_add', '', 'post')) {
            $oTopic = &$aVars[0];
            $oTopic->setDateAdd($sDateAdd);
            unset($_POST['delayedpost_topic_date_add']);
        }
    }

    /**
     * @return mixed
     */
    public function MenuTopicAction()
    {
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'menu_topic.tpl');
    }

    /**
     * @return mixed
     */
    public function HookBodyBegin()
    {
        $sCurrentEvent = Router::GetActionEvent();
        if ($sCurrentEvent == 'add' or $sCurrentEvent == 'edit')
            return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'body_begin.tpl');
    }

    /**
     * @return mixed
     */
    public function MainMenu()
    {
        if ($this->User_IsAuthorization()) {
            $oUserCurrent = $this->User_GetUserCurrent();
            if ($oUserCurrent) {

                $iByUserId = $oUserCurrent->getId();
                $iTopicsCount = 0;

                $aTopics = $this->PluginDelayedpost_Delayedpost_GetDelayedTopics($iByUserId);
                if ($aTopics) $iTopicsCount = $aTopics['count'];
                if ($iTopicsCount) {
                    $this->Viewer_Assign('iTopicsCount', $iTopicsCount);
                    return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'main_menu.tpl');
                }

            }
        }
    }


}

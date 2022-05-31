<?php

/*-------------------------------------------------------
*
*   CMS LiveStreet (1.x)
*   Plugin Complaint v1.0.0
*   Сделано руками @ Сергей Сарафанов (sersar)
*   ВК: vk.com/sersar | E-mail: sersar@ukr.net
*
---------------------------------------------------------
*/

class PluginComplaint_ActionComplaint extends ActionPlugin
{
    protected $oUserCurrent = null;
    protected $sMenuHeadItemSelect = 'complaint';
    protected $sMenuItemSelect = 'complaint';

    public function Init()
    {
        $this->oUserCurrent = $this->User_GetUserCurrent();
        $this->SetTemplateAction('index');
    }

    /**
     * Обработка
     */
    public function EventAjaxComplaint()
    {
        $this->Viewer_SetResponseAjax('json');
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('need_authorization'), $this->Lang_Get('error'));
            return;
        }
        /**
         * Топик существует?
         */
        if (!($oTopic = $this->Topic_GetTopicById(getRequestStr('topic_id', null, 'post')))) {
            $this->Message_AddErrorSingle($this->Lang_Get('plugin.complaint.error.topic_not_found'), $this->Lang_Get('error'));
            return;
        }
        /**
         * Самому себе нельзя довалять жалобу
         */
        if ($oTopic->getUserId() == $this->oUserCurrent->getId()) {
            $this->Message_AddErrorSingle($this->Lang_Get('plugin.complaint.error.topic_user_user'), $this->Lang_Get('attention'));
            return;
        }
        /**
         * Проверяем была уже добавлена жалоба от данного пользователя
         */
        if ($this->PluginComplaint_Complaint_CheckComplaintByTarget($oTopic->getId(), $this->oUserCurrent->getId())) {
            $this->Message_AddErrorSingle($this->Lang_Get('plugin.complaint.error.already_send'), $this->Lang_Get('error'));
            return;
        }
        $sText = trim(strip_tags(getRequest('complaint_text', null, 'post')));
        $oComplaint = Engine::GetEntity('PluginComplaint_ModuleComplaint_EntityComplaint');
        $oComplaint->_setValidateScenario('complaint');
        $oComplaint->setTopicId($oTopic->getId());
        $oComplaint->setUserId($this->oUserCurrent->getId());
        $oComplaint->setText($sText);
        if (!$oComplaint->_Validate()) {
            $this->Message_AddError($oComplaint->_getValidateError(), $this->Lang_Get('error'));
            return;
        }
        if ($this->PluginComplaint_Complaint_AddComplaint($oComplaint)) {
            if (Config::Get('plugin.complaint.send_admin_mail') and Config::Get('plugin.complaint.admin_mail')) {
                $this->PluginComplaint_Complaint_AddAdminNotice($oComplaint);
            }
            $this->Message_AddNoticeSingle($this->Lang_Get('plugin.complaint.add_ok'), $this->Lang_Get('attention'));
        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'), $this->Lang_Get('error'));
        }
    }

    /**
     * Админка
     */
    public function EventAdmin()
    {
        if (!$this->oUserCurrent or !$this->oUserCurrent->isAdministrator()) {
            return parent::EventNotFound();
        }
        $iPage = $this->GetParamEventMatch(0, 2) ? $this->GetParamEventMatch(0, 2) : 1;
        /**
         * Получаем список жалоб
         */
        $aResult = $this->PluginComplaint_Complaint_GetComplaintsByFilter(array(), $iPage, Config::Get('plugin.complaint.per_page'));
        $aComplaints = $aResult['collection'];
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('plugin.complaint.per_page'), Config::Get('pagination.pages.count'), Router::GetPath('complaint') . 'admin');
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('aComplaints', $aComplaints);
        $this->Viewer_Assign('aPaging', $aPaging);
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('admin');
    }

    /**
     * Регистрируем евенты
     */
    protected function RegisterEvent()
    {
        $this->AddEvent('ajax_complaint', 'EventAjaxComplaint');
        $this->AddEventPreg('/^admin$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventAdmin');
        $this->AddEvent('admin', 'EventAdmin');
    }
}
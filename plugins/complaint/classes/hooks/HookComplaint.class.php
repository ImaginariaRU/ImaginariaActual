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

class PluginComplaint_HookComplaint extends Hook
{
    public function RegisterHook()
    {
        $this->AddHook('template_admin_action_item', 'InjectAdmin');
        $this->AddHook('template_topic_show_info', 'TplTopicFooterComplaint');
    }

    /**
     * Добавляем в стандартную админку ссылку на админку жалоб
     * @return string
     */
    public function InjectAdmin()
    {
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'inject.admin.menu.tpl');
    }

    /**
     * Добавляем в футер топика кнопку
     * @param array $aVars
     * @return string
     */
    public function TplTopicFooterComplaint($aVars)
    {
        // Проверяем, что страница топика
        if (Router::GetAction() != 'blog') return '';

        $oTopic = $aVars['topic'];
        $oUserCurrent = $this->User_GetUserCurrent();

        // Проверяем авторизован ли пользователь
        if ($oUserCurrent) {

            // Жаловаться на себя нельзя
            if ($oTopic->getUserId() == $oUserCurrent->getId()) {
                return '';
            }

            //  Проверка, разрешено ли подавать жалобу
            if (!$this->PluginComplaint_Complaint_CheckAbusingAvailable()) {
                return '';
            }

            // Проверяем была уже добавлена жалоба от данного пользователя?
            if ($this->PluginComplaint_Complaint_CheckComplaintByTarget($oTopic->getId(), $oUserCurrent->getId())) {
                return '';
            }

            //  Всё хорошо. Выводим.
            $this->Viewer_Assign("oTopic", $oTopic);
            return $this->Viewer_Fetch(Plugin::GetTemplatePath('complaint') . 'hook.topic.complaint.tpl');
        }
        return '';
    }
}
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

class PluginComplaint_ModuleComplaint extends Module
{
    protected $oMapper;

    /**
     * Инициализация плагина
     *
     * @return void
     */
    public function Init()
    {
        $this->oMapper = Engine::GetMapper(__CLASS__);
    }

    /**
     * Добавляем жалобу
     *
     * @param PluginComplaint_ModuleComplaint_EntityComplaint $oComplaint
     * @return bool
     */
    public function AddComplaint(PluginComplaint_ModuleComplaint_EntityComplaint $oComplaint)
    {
        if ($sId = $this->oMapper->AddComplaint($oComplaint)) {
            $oComplaint->setId($sId);
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('complaint_new'));
            return true;
        }
        return false;
    }

    /**
     * Обновляем жалобу
     *
     * @param PluginComplaint_ModuleComplaint_EntityComplaint $oComplaint
     * @return bool
     */
    public function UpdateComplaint(PluginComplaint_ModuleComplaint_EntityComplaint $oComplaint)
    {
        if ($this->oMapper->UpdateComplaint($oComplaint)) {
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('complaint_update', "complaint_update_{$oComplaint->getId()}"));
            $this->Cache_Delete("complaint_{$oComplaint->getId()}");
            return true;
        }
        return false;
    }

    /**
     * Удаляем жалобу
     *
     * @param integer $iComplaintId
     * @return bool
     */
    public function DeleteComplaint($iComplaintId)
    {
        if ($iComplaintId instanceof PluginComplaint_ModuleComplaint_EntityComplaint) {
            $iComplaintId = $iComplaintId->getId();
        }
        $this->Cache_Clean(
            Zend_Cache::CLEANING_MODE_MATCHING_TAG, array(
                "complaint_update"
            )
        );
        $this->Cache_Delete("complaint_{$iComplaintId}");
        $this->oMapper->DeleteComplaint($iComplaintId);
        return true;
    }

    /**
     * Проверяем отправлял ли пользователь жалобу на топик
     * @param integer $iTopicId
     * @param integer $iUserId
     * @return integer
     */
    public function CheckComplaintByTarget($iTopicId, $iUserId)
    {
        $aFilter = array(
            'topic_id' => $iTopicId,
            'user_id' => $iUserId,
        );
        $aComplaints = $this->GetComplaintsByFilter($aFilter, 0, 0, array(), true);
        return $aComplaints['count'];
    }

    /**
     * Поиск по-фильтру жалобы
     * @param array $aFilter
     * @param integer $iPage
     * @param integer $iPerPage
     * @param array $aAllowData
     * @param bool $bOnlyIds
     * @return array
     */
    public function GetComplaintsByFilter($aFilter, $iPage = 0, $iPerPage = 0, $aAllowData = array(), $bOnlyIds = false)
    {
        $s = serialize($aFilter);
        $sCacheKey = "complaint_filter_{$s}_{$iPage}_{$iPerPage}";
        $iCount = 0;
        if (false === ($data = $this->Cache_Get($sCacheKey))) {
            $data = ($iPage * $iPerPage != 0) ? array(
                'collection' => $this->oMapper->GetComplaintsByFilter($aFilter, $iCount, $iPage, $iPerPage),
                'count' => $iCount
            ) : array(
                'collection' => $this->oMapper->GetAllComplaintsByFilter($aFilter),
                'count' => $this->GetCountComplaintsByFilter($aFilter)
            );
            $this->Cache_Set($data, $sCacheKey, array('complaint_update', 'complaint_new'), 60 * 60 * 24 * 3);
        }
        if (!$bOnlyIds) {
            $data['collection'] = $this->GetComplaintsAdditionalData($data['collection'], $aAllowData);
        }
        return $data;
    }

    /**
     * Количество жалоб по-фильтру
     * @param array $aFilter
     * @return integer
     */
    public function GetCountComplaintsByFilter($aFilter)
    {
        $s = serialize($aFilter);
        if (false === ($data = $this->Cache_Get("complaint_count_{$s}"))) {
            $data = $this->oMapper->GetCountComplaintsByFilter($aFilter);
            $this->Cache_Set($data, "complaint_count_{$s}", array('complaint_update', 'complaint_new'), 60 * 60 * 24 * 1);
        }
        return $data;
    }

    /**
     * Получаем дополнительные данные объекта
     * @param array $aComplaints
     * @param array $aAllowData
     * @throws
     * @return array
     */
    public function GetComplaintsAdditionalData($aComplaints, $aAllowData = array())
    {
        $aComplaintsNew = array();
        foreach ($aComplaints as $key => $aComplaint) {
            $aComplaintsNew[$key] = Engine::GetEntity('PluginComplaint_ModuleComplaint_EntityComplaint', $aComplaint);;
        }
        return $aComplaintsNew;
    }

    /**
     * Отправляем админу уведомление
     * @param object $oComplaint
     * @return void
     */
    public function AddAdminNotice($oComplaint)
    {
        $this->Notify_Send(
            Config::Get('plugin.complaint.admin_mail'),
            'notify.complaint.tpl',
            $this->Lang_Get('plugin.complaint.notify_admin'),
            array(
                'oComplaint' => $oComplaint,
                'oUser' => $oComplaint->getUser(),
                'oTopic' => $oComplaint->getTopic(),
            ),
            __CLASS__
        );
    }

    /**
     * Заглушка функции "можно ли подавать жалобу?"
     *
     * планируется ограничение по дате регистрации, рейтингу и так далее
     *
     * @return bool
     */
    public function CheckAbusingAvailable()
    {
        return true;
    }
}
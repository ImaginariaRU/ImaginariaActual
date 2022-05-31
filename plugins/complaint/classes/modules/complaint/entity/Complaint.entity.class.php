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

class PluginComplaint_ModuleComplaint_EntityComplaint extends Entity
{
    public function Init()
    {
        parent::Init();
        $this->aValidateRules[] = array(
            'complaint_text',
            'string',
            'max' => Config::Get('plugin.complaint.text.max_length'),
            'min' => Config::Get('plugin.complaint.text.min_length'),
            'allowEmpty' => false,
            'label' => $this->Lang_Get('plugin.complaint.error.complaint_text_limit'),
            'on' => array('complaint'));
    }

    public function getId()
    {
        return $this->_getDataOne('complaint_id');
    }

    public function getUser()
    {
        return $this->User_GetUserById($this->getUserId());
    }

    public function getUserId()
    {
        return $this->_getDataOne('user_id');
    }

    public function getTopic()
    {
        return $this->Topic_GetTopicById($this->getTopicId());
    }

    public function getTopicId()
    {
        return $this->_getDataOne('topic_id');
    }

    public function getText()
    {
        return $this->_getDataOne('complaint_text');
    }

    public function getDateAdd()
    {
        return $this->_getDataOne('complaint_date_add');
    }

    public function getStatus()
    {
        return $this->_getDataOne('complaint_status');
    }

    public function setId($data)
    {
        $this->_aData['complaint_id'] = $data;
    }

    public function setUserId($data)
    {
        $this->_aData['user_id'] = $data;
    }

    public function setTopicId($data)
    {
        $this->_aData['topic_id'] = $data;
    }

    public function setText($data)
    {
        $this->_aData['complaint_text'] = $data;
    }

    public function setDateAdd($data)
    {
        $this->_aData['complaint_date_add'] = $data;
    }

    public function setStatus($data)
    {
        $this->_aData['complaint_status'] = $data;
    }
}
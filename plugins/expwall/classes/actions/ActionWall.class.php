<?php

/* -------------------------------------------------------
 *
 *   LiveStreet (v1.x)
 *   Plugin Expanded wall (v.0.3)
 *   Copyright © 2011 Bishovec Nikolay
 *
 * --------------------------------------------------------
 *
 *   Plugin Page: http://netlanc.net
 *   Contact e-mail: netlanc@yandex.ru
 *
  ---------------------------------------------------------
 */

class PluginExpwall_ActionWall extends ActionPlugin
{

    /**
     * Текущий юзер
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent = null;
    protected $sMenuHeadItemSelect = 'wall';

    /**
     * Инициализация экшена
     */
    public function Init()
    {

        /**
         * Инициализация текущего юзера
         */
        $this->oUserCurrent = $this->User_GetUserCurrent();

        $this->SetDefaultEvent('index');
    }

    public function EventAdd()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');

        $iUserId = getRequest('iuser');

        if (!$oUser = $this->User_GetUserById($iUserId)) {
            return parent::EventNotFound();
        }
        
        /**
         * Создаем запись
         */
        $oWall = Engine::GetEntity('Wall');
        $oWall->_setValidateScenario('add');
        $oWall->setWallUserId($oUser->getId());
        $oWall->setUserId($this->oUserCurrent->getId());
        $oWall->setText(getRequest('sText'));
        $oWall->setPid(getRequest('iPid'));
        // print_r($_POST);
        $this->Hook_Run('wall_add_validate_before', array('oWall' => $oWall));
        if ($oWall->_Validate()) {
            /**
             * Экранируем текст и добавляем запись в БД
             */
            $oWall->setText($this->Text_Parser($oWall->getText()));
            $this->Hook_Run('wall_add_before', array('oWall' => $oWall));
            if ($this->Wall_AddWall($oWall)) {
                $this->Hook_Run('wall_add_after', array('oWall' => $oWall));
                /**
                 * Отправляем уведомления
                 */
                
                if ($oWallParent = $oWall->GetPidWall() and $oWallParent->getUserId() != $oWall->getUserId()) {

                    // Ответ на ваше сообщение на стене
                    $this->Notify_SendWallReply($oWallParent, $oWall, $this->oUserCurrent);
                    
                } elseif ($oWall->getWallUserId() != $oWall->getUserId()) {

                    // Новое сообщение на вашей стене
                    $this->Notify_SendWallNew($oWall, $this->oUserCurrent);
                }
                
                // Новое сообщение на вашей стене
/*
                if ($oWall->getWallUserId() != $oWall->getUserId()) {
                    $this->Notify_SendWallNew($oWall, $this->oUserCurrent);
                }
                
                // Ответ на ваше сообщение на стене
                if ($oWallParent = $oWall->GetPidWall() and $oWallParent->getUserId() != $oWall->getUserId()) {
                    $this->Notify_SendWallReply($oWallParent, $oWall, $this->oUserCurrent);
                }
*/
                /**
                 * Добавляем событие в ленту
                 */
                $this->Stream_Write($oWall->getUserId(), 'add_wall', $oWall->getId());
            } else {
                $this->Message_AddError($this->Lang_Get('wall_add_error'), $this->Lang_Get('error'));
            }
        } else {
            $this->Message_AddError($oWall->_getValidateError(), $this->Lang_Get('error'));
        }
    }

    /**
     * Ajax подгрузка сообщений стены
     */
    public function EventLoad()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        $iPage = getRequest('ipage');
        /**
         * Формируем фильтр для запроса к БД
         */
        $aFilter = array(
            'pid' => null
        );
        if (is_numeric(getRequest('iIdLess'))) {
            $aFilter['id_less'] = getRequest('iIdLess');
        } elseif (is_numeric(getRequest('iIdMore'))) {
            $aFilter['id_more'] = getRequest('iIdMore');
        }
        /**
         * Получаем сообщения и формируем ответ
         */
        $aWall = $this->Wall_GetWall($aFilter, array(), $iPage, Config::Get('module.wall.per_page'));
        $this->Viewer_Assign('aWall', $aWall['collection']);
        $this->Viewer_Assign('oUserCurrent', $this->oUserCurrent); // хак, т.к. к этому моменту текущий юзер не загружен в шаблон
        $this->Viewer_AssignAjax('sText', $this->Viewer_Fetch(Plugin::GetTemplatePath('expwall') . '/wall_items.tpl'));
        $this->Viewer_AssignAjax('iCountWall', $aWall['count']);
        $this->Viewer_AssignAjax('iCountWallReturn', count($aWall['collection']));
    }

    /**
     * Завершение работы экшена
     */
    public function EventShutdown()
    {
        $this->Viewer_Assign('sMenuHeadItemSelect', $this->sMenuHeadItemSelect);
    }

    /**
     * Регистрируем евенты
     */
    protected function RegisterEvent()
    {
        $this->AddEvent('index', 'EventIndex');
        $this->AddEventPreg('/^ajax$/i', '/^load-reply$/i', 'EventReply');
        $this->AddEventPreg('/^ajax$/i', '/^load$/i', 'EventLoad');
        $this->AddEventPreg('/^ajax$/i', '/^add$/i', 'EventAdd');
    }

    protected function EventReply()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        $iId = getRequest('iId');
        if (!($oWall = $this->Wall_GetWallById($iId)) or $oWall->getPid()) {
            return parent::EventNotFound();
        }

        $aFilter = array(
            'pid' => $oWall->getId()
        );
        $aWall = $this->Wall_GetWall($aFilter, array('id' => 'asc'), 1, 300);
        $this->Viewer_Assign('aReplyWall', $aWall['collection']);

        /**
         * Внимание, вопрос, почему здесь грузится шаблон из \templates\skin\synio\actions\ActionProfile\wall_items_reply.tpl
         * А не из <plugin>\templates\skin\default\wall_items_reply.tpl ???
         */

        $this->Viewer_AssignAjax('sText', $this->Viewer_Fetch('actions/ActionProfile/wall_items_reply.tpl'));
        $this->Viewer_AssignAjax('iCountWall', $aWall['count']);
        $this->Viewer_AssignAjax('iCountWallReturn', count($aWall['collection']));
    }

    protected function EventIndex()
    {
        $iPage = $this->GetEventMatch(2) ? $this->GetEventMatch(2) : 1;

        $aResult = $this->Wall_GetWall(array('pid' => null), array(), $iPage, Config::Get('module.wall.per_page'));
        $aWall = $aResult['collection'];
        $this->Viewer_Assign('iCountWall', $aResult['count']);
        $this->Viewer_Assign('iCountWallReturn', count($aResult['collection']));

        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.wall.per_page'), Config::Get('pagination.pages.count'), Router::GetPath('index'));
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('aWall', $aWall);
        $this->Viewer_Assign('aPaging', $aPaging);
    }
}

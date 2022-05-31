<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

/**
 * Модуль для работы с пользователями
 *
 */
class PluginRememberme_ModuleUser extends PluginRememberme_Inherit_ModuleUser
{

    /**
     * Redefine Init() method
     *
     */
    public function Init()
    {
        $this->oMapper = Engine::GetMapper(__CLASS__);
        /**
         * Проверяем есть ли у юзера сессия, т.е. залогинен или нет
         */
        $sUserId = $this->Session_Get('user_id');
        if ($sUserId and $oUser = $this->GetUserById($sUserId) and $oUser->getActivate()) {
            /**
             * There is no sense to get user session by module method twice.
             * Cause we can get it from User Entity
             */
            if ($this->oSession = $oUser->getSession()) {
                /**
                 * Check if user has other sessions started and use the only one equal to $_COOKIE['key']
                 */
                $this->oSession->checkUserSessionForMultiplicity();
                /**
                 * Сюда можно вставить условие на проверку айпишника сессии
                 */
                $this->oUserCurrent = $oUser;
            }
        }
        /**
         * Запускаем автозалогинивание
         * В куках стоит время на сколько запоминать юзера
         */
        $this->AutoLogin();
        /**
         * Обновляем сессию
         */
        if (isset($this->oSession)) {
            $this->UpdateSession();
        }
    }

    public function Logout()
    {
        if ($this->oSession && $this->oMapper->DeleteSession($this->oSession)) {
            parent::Logout();
        }
    }

}

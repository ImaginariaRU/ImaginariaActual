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

class PluginRememberme_ModuleUser_EntitySession extends PluginRememberme_Inherit_ModuleUser_EntitySession
{
    /**
     * Array to store all user sessions
     * @var array
     */
    protected $aMultiSessionArray = array();

    public function addSession(PluginRememberme_ModuleUser_EntitySession $oSession)
    {
        $this->aMultiSessionArray[$oSession->getKey()] = $oSession;
    }

    public function checkUserSessionForMultiplicity()
    {
        if (array_key_exists('key', $_COOKIE) && $sKey = $_COOKIE['key']) {
            if (array_key_exists($sKey, $this->aMultiSessionArray)) {
                $this->_aData = $this->aMultiSessionArray[$sKey]->_aData;
            }
        }
    }
}

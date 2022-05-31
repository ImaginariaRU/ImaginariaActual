<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Сделано руками @ Сергей Сарафанов (sersar)
*   ICQ: 172440790 | E-mail: sersar@ukr.net
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

class PluginIgnore_ModuleTalk extends PluginIgnore_Inherit_ModuleTalk {
	public function AddUserToBlacklist($sTargetId, $sUserId) {
		$args =  func_get_args();
		if (!call_user_func_array(array('parent',__FUNCTION__),$args)) {
			return false;
		}
        $sTargetId = (int)$args[0];
        $sUserId   = (int)$args[1];
        $this->PluginIgnore_Ignore_UpdateUserIgnoreType($sUserId, $sTargetId, PluginIgnore_ModuleIgnore::IGNORE_POST_ME_PM, 'add');
        return true;
	}
    
	public function AddUserArrayToBlacklist($aTargetId, $sUserId) {
		$args =  func_get_args();
		if (!call_user_func_array(array('parent',__FUNCTION__),$args)) {
			return false;
		}
        $aTargetId = (array)$args[0];
        $sUserId   = (int)$args[1];
		foreach ((array)$aTargetId as $oUser) {
			$sTargetId = $oUser instanceof ModuleUser_EntityUser ? $oUser->getId() : (int)$oUser;
            $this->PluginIgnore_Ignore_UpdateUserIgnoreType($sUserId, $sTargetId, PluginIgnore_ModuleIgnore::IGNORE_POST_ME_PM, 'add');
		}
        return true;
	}
    
	public function DeleteUserFromBlacklist($sTargetId, $sUserId) {
		$args =  func_get_args();
		if (!call_user_func_array(array('parent',__FUNCTION__),$args)) {
			return false;
		}
        $sTargetId = (int)$args[0];
        $sUserId   = (int)$args[1];
        $this->PluginIgnore_Ignore_UpdateUserIgnoreType($sUserId, $sTargetId, PluginIgnore_ModuleIgnore::IGNORE_POST_ME_PM, 'delete');
        return true;
	}
}












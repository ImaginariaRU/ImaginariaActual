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

class PluginIgnore_ModuleUser_EntityUser extends PluginIgnore_Inherit_ModuleUser_EntityUser
{
	public function getCountIgnore() {
		return $this->_getDataOne('user_count_ignore');
	}
    
	public function setCountIgnore($data) {
		$this->_aData['user_count_ignore']=$data;
	}
}


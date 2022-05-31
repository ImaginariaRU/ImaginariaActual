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

class PluginIgnore_ModuleTopic_MapperTopic extends PluginIgnore_Inherit_ModuleTopic_MapperTopic {
	protected function buildFilter($aFilter) {
        $sWhere = parent::buildFilter($aFilter);
		if (isset($aFilter['ignore_user'])) {
			$sWhere.=is_array($aFilter['ignore_user'])
				? " AND t.user_id NOT IN(".implode(', ',$aFilter['ignore_user']).")"
				: " AND t.user_id !=  ".(int)$aFilter['ignore_user'];
		}
        return $sWhere;
	}
}










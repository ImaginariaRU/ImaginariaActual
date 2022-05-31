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

class PluginIgnore_ModuleIgnore_MapperIgnore extends Mapper {

	public function UpdateCountUserIgnore($sUserId) {
		$sql = "
			UPDATE ".Config::Get('db.table.user')."
			SET
				user_count_ignore = (SELECT
                                            count(distinct user_id) as count
                                        FROM
                                            " . Config::Get('plugin.ignore.table.ignore') . "
                                        WHERE
                                            ignore_target_id = ?d
                                        )
			WHERE
				user_id = ?d
		";
		if($this->oDb->query($sql,$sUserId,$sUserId)) {
			return true;
		}
		return false;
	}
}

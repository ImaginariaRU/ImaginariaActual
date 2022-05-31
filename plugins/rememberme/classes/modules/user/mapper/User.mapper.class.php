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

class PluginRememberme_ModuleUser_MapperUser extends PluginRememberme_Inherit_ModuleUser_MapperUser
{

    public function CreateSession(ModuleUser_EntitySession $oSession)
    {
        // Drop previous session record
        $table = Config::Get('db.table.session');

        if (in_array('key', $_COOKIE) && $sKey = $_COOKIE['key']) {
            $this->oDb->query("DELETE FROM {$table}	WHERE session_key = ?", $sKey);
        }

        return parent::CreateSession($oSession);
    }

    public function UpdateSession(ModuleUser_EntitySession $oSession)
    {
        $table = Config::Get('db.table.session');

        // change condition from user_id to session_key
        $sql = "
            UPDATE {$table}
			SET
				session_ip_last = ? ,
				session_date_last = ?
			WHERE session_key = ?
		";
        return $this->oDb->query($sql, $oSession->getIpLast(), $oSession->getDateLast(), $oSession->getKey());
    }

    public function DeleteSession(ModuleUser_EntitySession $oSession)
    {
        $table = Config::Get('db.table.session');

        // change condition from user_id to session_key
        $sql = "
			DELETE FROM {$table} 
			WHERE session_key = ?
		";

        return $this->oDb->query($sql, $oSession->getKey());
    }

    public function GetCountUsersActive($sDateActive)
    {
        $table = Config::Get('db.table.session');

        $sql = "
            SELECT count(DISTINCT user_id) as count 
			FROM {$table} 
			WHERE session_date_last >= ?";

        $result = $this->oDb->selectRow($sql, $sDateActive);
        return $result['count'];
    }

    public function GetUsersByDateLast($iLimit)
    {
        $aResult = parent::GetUsersByDateLast($iLimit);
        return is_array($aResult) ? array_unique($aResult) : $aResult;
    }

    public function GetSessionsByArrayId($aArrayId)
    {
        $table = Config::Get('db.table.session');

        if (!is_array($aArrayId) or count($aArrayId) == 0) {
            return array();
        }
        /**
         * Change query order to get last sessions of user in first place
         */
        $sql = "SELECT
					s.*
				FROM
					{$table} as s
				WHERE
					s.user_id IN(?a) 
				ORDER BY 
					s.user_id ASC,
					s.session_date_last DESC
				";
        $aRes = array();
        if ($aRows = $this->oDb->select($sql, $aArrayId)) {
            foreach ($aRows as $aRow) {
                if (!isset($aRes[$aRow['user_id']])) {
                    $aRes[$aRow['user_id']] = Engine::GetEntity('User_Session', $aRow);
                }
                $aRes[$aRow['user_id']]->addSession(Engine::GetEntity('User_Session', $aRow));
            }
        }
        return $aRes;
    }

}

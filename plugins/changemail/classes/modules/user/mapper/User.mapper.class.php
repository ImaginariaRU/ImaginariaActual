<?php
/*-------------------------------------------------------
*
*	Plugin "Changemail"
*	Author: Vladimir Yuriev ( extravert )
*	Contact e-mail: support@lsmods.ru
*	Site: http://lsmods.ru
*
*/

class PluginChangemail_ModuleUser_MapperUser extends PluginChangemail_Inherit_ModuleUser_MapperUser
{


    public function UpdateChangemail($oChangemail)
    {
        return $this->AddChangemail($oChangemail);
    }

    public function AddChangemail($oChangemail)
    {
        $table = Config::Get('db.table.changemail');

        $sql = "REPLACE {$table}
			SET
				changemail_code = ? ,
				user_id = ? ,
				changemail_date_add = ? ,
				changemail_date_used = ? ,
				changemail_date_expire = ? ,
				changemail_is_used = ?,
                changemail_mail_to = ?
		";
        return $this->oDb->query($sql, $oChangemail->getCode(), $oChangemail->getUserId(), $oChangemail->getDateAdd(), $oChangemail->getDateUsed(), $oChangemail->getDateExpire(), $oChangemail->getIsUsed(), $oChangemail->getChangeMailTo());
    }

    public function GetChangemailByCode($sCode)
    {
        $table = Config::Get('db.table.changemail');

        $sql = "SELECT
					*
				FROM
					{$table}
				WHERE
					changemail_code = ?";
        if ($aRow = $this->oDb->selectRow($sql, $sCode)) {
            return new PluginChangemail_ModuleUser_EntityChangemail($aRow);
        }
        return null;
    }

}

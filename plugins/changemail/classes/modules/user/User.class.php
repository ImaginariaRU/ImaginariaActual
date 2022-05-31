<?php

/*-------------------------------------------------------
*
*	Plugin "Changemail"
*	Author: Vladimir Yuriev ( extravert )
*	Contact e-mail: support@lsmods.ru
*	Site: http://lsmods.ru
*
*/

class PluginChangemail_ModuleUser extends PluginChangemail_Inherit_ModuleUser
{

    public function AddChangemail($oChangemail)
    {
        return $this->oMapper->AddChangemail($oChangemail);
    }

    public function UpdateChangemail($oChangemail)
    {
        return $this->oMapper->UpdateChangemail($oChangemail);
    }

    public function GetChangemailByCode($sCode)
    {
        return $this->oMapper->GetChangemailByCode($sCode);
    }

}

?>
<?php
/*-------------------------------------------------------
*
*	Plugin "Changemail"
*	Author: Vladimir Yuriev (extravert)
*	Site: lsmods.ru
*	Contact e-mail: support@lsmods.ru
*
---------------------------------------------------------
*/

class PluginChangemail_ActionSettings extends PluginChangemail_Inherit_ActionSettings
{


    protected function EventAccount()
    {

        if (isPost('submit_account_edit')) {
            $this->Security_ValidateSendForm();
            if (getRequest('mail') && $this->oUserCurrent->getMail() != getRequest('mail') && func_check(getRequest('mail'), 'mail')) {
                $sChangeMailTo = getRequest('mail');
                unset($_REQUEST['mail']);
                $_REQUEST['mail'] = $this->oUserCurrent->getMail();
            }
        }

        parent::EventAccount();

        if (isset($sChangeMailTo)) {

            $oChangeMail = Engine::GetEntity('PluginChangemail_ModuleUser_EntityChangemail');
            $oChangeMail->setCode(func_generator(32));
            $oChangeMail->setDateAdd(date("Y-m-d H:i:s"));
            $oChangeMail->setDateExpire(date("Y-m-d H:i:s", time() + 60 * 60 * 24 * 7));
            $oChangeMail->setDateUsed(null);
            $oChangeMail->setIsUsed(0);
            $oChangeMail->setUserId($this->oUserCurrent->getId());
            $oChangeMail->setChangeMailTo($sChangeMailTo);
            if ($this->User_AddChangemail($oChangeMail)) {
                $this->Notify_Send($this->oUserCurrent, 'notify.changemail.tpl', $this->Lang_Get('plugin.changemail.notify_changemail_report_title'), array('oChangeMail' => $oChangeMail, 'oUser' => $this->oUserCurrent), 'changemail');

            }

            $this->Message_AddNotice($this->Lang_Get('plugin.changemail.settings_profile_mail_change_need_continue', array('mail' => $this->oUserCurrent->getMail())));
        }
    }

}

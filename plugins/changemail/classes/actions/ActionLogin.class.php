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

class PluginChangemail_ActionLogin extends PluginChangemail_Inherit_ActionLogin
{


    protected function RegisterEvent()
    {
        parent::RegisterEvent();
        $this->AddEvent('changemail', 'EventChangemail');
    }

    /**
     * Обработка напоминания пароля
     *
     */
    protected function EventChangemail()
    {
        $this->Viewer_AddHtmlTitle($this->Lang_Get('plugin.changemail.changemail'));

        /**
         * Проверка кода на восстановление пароля и генерация нового пароля
         */
        if (func_check($this->GetParam(0), 'md5')) {
            if ($oChangeMail = $this->User_GetChangemailByCode($this->GetParam(0))) {
                if (!$oChangeMail->getIsUsed() and strtotime($oChangeMail->getDateExpire()) > time() and $oUser = $this->User_GetUserById($oChangeMail->getUserId())) {
                    $oUser->setMail($oChangeMail->getChangeMailTo());
                    if ($this->User_Update($oUser)) {
                        $oChangeMail->setDateUsed(date("Y-m-d H:i:s"));
                        $oChangeMail->setIsUsed(1);
                        $this->User_UpdateChangemail($oChangeMail);
                        $this->Viewer_Assign('bRefreshToHome', true);
                        $this->Message_AddNotice($this->Lang_Get('plugin.changemail.setting_email_change_succes'));

                        return;
                    }
                }
            }
            $this->Message_AddErrorSingle($this->Lang_Get('plugin.changemail.changemail_bad_code'), $this->Lang_Get('error'));
            return Router::Action('error');
        }

    }

}

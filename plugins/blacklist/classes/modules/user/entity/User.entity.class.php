<?php
/**
 * Blacklist - проверка E-Mail пользователей на наличие в базах спамеров.
 *
 * Версия:    1.2
 *
 **/

class PluginBlacklist_ModuleUser_EntityUser extends PluginBlacklist_Inherit_ModuleUser_EntityUser
{
    /**
     * Проверка емайла на существование
     *
     * @param string $sValue Валидируемое значение
     * @param array $aParams Параметры
     * @return bool
     */
    public function ValidateMailExists($sValue, $aParams)
    {
        if ($this->PluginBlacklist_ModuleBlacklist_checkCredentialsBlocked($sValue)) {
            return $this->Lang_Get(Config::Get('plugin.blacklist.check_ip_exact') ? 'plugin.blacklist.user_in_exact_blacklist' : 'plugin.blacklist.user_in_blacklist');
        }

        return parent::ValidateMailExists($sValue, $aParams);
    }
}

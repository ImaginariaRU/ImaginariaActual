<?php
/**
 * Blacklist - проверка E-Mail пользователей на наличие в базах спамеров.
 *
 * Версия:    1.2
 * Автор:    Karel Wintersky
 *
 **/

/**
 * Class PluginBlacklist_ModuleBlacklist
 */
class PluginBlacklist_ModuleBlacklist extends Module
{
    const SERVICE_STOPFORUMSPAM_COM = 1;
    const SERVICE_BOTSCOUT_COM = 2;
    const SERVICE_FSPAMLIST_COM = 3;
    const TYPE_MAIL = 'mail';
    const TYPE_IP = 'ip';
    const DEBUG = false;
    protected $oMapper;

    /**
     *
     */
    public function Init()
    {
        $this->oMapper = Engine::GetMapper(__CLASS__);
    }

    /**
     * Главная функция. Возвращает TRUE если емейл не прошел одну из проверок на валидность
     * (то есть его нет в white-list или он есть в blacklist)
     *
     * @param $sMail
     * @param null $sName
     * @return bool
     */
    public function checkCredentialsBlocked($sMail, $sName = null)
    {
        $sMail = mb_strtolower($sMail);
        
        if (!$this->check_Whitelist_Domain($sMail)) {
            \header("HTTP/500", true, 500);
            return true;
            
            die('Your email not allowed');
        }
        
        return false;
    }
    
    /* ==================================== МЫЛО =============================== */ 

    /**
     * TRUE если мыло в черном списке 
     * 
     * @param $sMail
     * @return bool
     */
    public function check_Blacklist_EMail($sMail){
        if (empty($sMail)) {
            return true;
        }
        return in_array($sMail, Config::Get('plugin.blacklist.blacklist_users_mail'));
    }

    /**
     * TRUE если мыло в белом списке
     *
     * @param $sMail
     * @return bool
     */
    public function check_Whitelist_EMail($sMail){
        if (empty($sMail)) {
            return true;
        }
        return in_array($sMail, Config::Get('plugin.blacklist.whitelist_users_mail'));
    }

    /* ==================================== ДОМЕН =============================== */
    /**
     * TRUE если домен+зона в черном списке
     * @param $sMail
     * @return bool
     */
    public function check_Blacklist_Domain($sMail){
        $aMail = explode("@", $sMail);
        $sDomain = (count($aMail) > 1 ? $aMail[1] : '');

        return in_array($sDomain, Config::Get('plugin.blacklist.blacklist_domains'));
    }

    /**
     * TRUE если домен+зона в белом списке
     *
     * @param $sMail
     * @return bool
     */
    public function check_Whitelist_Domain($sMail){
        $aMail = explode("@", $sMail);
        $sDomain = (count($aMail) > 1 ? $aMail[1] : '');

        return in_array($sDomain, Config::Get('plugin.blacklist.whitelist_domains'));
    }


    /* ==================================== ДОМЕННАЯ ЗОНА======================== */
    /**
     * TRUE если зона в черном списке
     *
     * @param $sMail
     * @return bool
     */
    public function check_Blacklist_Zone($sMail){
        $aMail = explode("@", $sMail);
        $sDomain = count($aMail) > 1 ? $aMail[1] : '';
        $aDomain = explode('.', $sDomain);
        $sZone = count($aDomain) > 1 ? $aDomain[count($aDomain) - 1] : $sDomain;

        return in_array($sZone, Config::Get('plugin.blacklist.blacklist_zones'));
    }

    /**
     * TRUE если зона в белом списке
     *
     * @param $sMail
     * @return bool
     */
    public function check_Whitelist_Zone($sMail){
        $aMail = explode("@", $sMail);
        $sDomain = count($aMail) > 1 ? $aMail[1] : '';
        $aDomain = explode('.', $sDomain);
        $sZone = count($aDomain) > 1 ? $aDomain[count($aDomain) - 1] : $sDomain;

        return in_array($sZone, Config::Get('plugin.blacklist.blacklist_zones'));
    }


    /* ==================================== НИКНЕЙМ ============================= */
    /**
     * Проверка по белому списку юзернеймов
     *
     * @param $sName
     * @return bool
     */
    public function check_Whitelist_Username($sName)
    {
        if (empty($sName)) {
            return false;
        }

        $whitelist_users_name = Config::Get('plugin.blacklist.whitelist_users_name');
        return in_array(mb_strtolower($sName), $whitelist_users_name);
    }

    /**
     * Проверка по черному списку юзернеймов
     *
     * @param $sName
     * @return bool
     */
    public function check_Blacklist_Username($sName)
    {
        if (empty($sName)) {
            return false;
        }

        $whitelist_users_name = Config::Get('plugin.blacklist.blacklist_users_name');
        return in_array(mb_strtolower($sName), $whitelist_users_name);
    }

    /* ==================================== АЙПИ ================================ */
    /**
     * Проверка по белому списку IP
     *
     * @param $sIp
     * @return bool
     */
    public function check_whitelist_users_ip($sIp)
    {
        $whitelist_users_ip = Config::Get('plugin.blacklist.whitelist_users_ip');

        return in_array(mb_strtolower($sIp), $whitelist_users_ip);
    }
    /**
     * @param $sIp
     * @return bool
     */
    public function check_blacklist_users_ip($sIp)
    {
        return in_array(mb_strtolower($sIp), Config::Get('plugin.blacklist.blacklist_users_ip'));
    }
    

}

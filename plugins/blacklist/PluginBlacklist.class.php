<?php
/**
 * Blacklist - проверка E-Mail пользователей на наличие в базах спамеров.
 *
 * Версия:    1.2
 *
 **/

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die(__FILE__ . ' : Hacking attemp!');
}


/**
 * Class PluginBlacklist
 */
class PluginBlacklist extends Plugin
{

    protected $aInherits = array(
        'entity' => array('ModuleUser_EntityUser'),
        'module' => array('ModuleUser'),
    );

    /**
     * @param $sMail
     * @return mixed
     */
    static function blackMail($sMail)
    {
        $oEngine = Engine::getInstance();
        return $oEngine->PluginBlacklist_ModuleBlacklist_checkCredentialsBlocked($sMail);
    }

    /**
     * Активация плагина
     */
    public function Activate()
    {
        if (!$this->isTableExists('prefix_blacklist')) {
            $this->ExportSQL(dirname(__FILE__) . '/dump.sql');
        }
        return true;
    }

    /**
     * Инициализация плагина
     */
    public function Init()
    {
    }
}

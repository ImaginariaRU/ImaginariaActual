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

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die(__FILE__ . ' : Hacking attemp!');
}


class PluginRememberme extends Plugin
{

    protected $aInherits = array(
        'module' => array('ModuleUser'),
        'mapper' => array('ModuleUser_MapperUser'),
        'entity' => array('ModuleUser_EntitySession'),
    );


    /**
     * Активация плагина.
     */
    public function Activate()
    {
        Engine::getInstance()->Cache_Clean();
        $this->ExportSQL(dirname(__FILE__) . '/sql.sql');
        return true;
    }

    /**
     * Деактивация плагина.
     */
    public function Deactivate()
    {
        Engine::getInstance()->Cache_Clean();
        $this->ExportSQL(dirname(__FILE__) . '/deactivate.sql');
        return true;
    }

    /**
     * Инициализация плагина
     */
    public function Init()
    {
    }
}

<?php
/*-------------------------------------------------------
*
*   Simple Rating
*   Copyright  2012 Denis Shakhov
*
*--------------------------------------------------------
*
*   Contact e-mail: work@deniart.ru
*
---------------------------------------------------------
*/

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die(__FILE__ . ' : Hacking attemp!');
}


class PluginOnlyupvote extends Plugin
{

    protected $aInherits = array(
        'action' => array('ActionAjax'),
    );


    /**
     * Активация плагина
     */
    public function Activate()
    {
        return true;
    }

    public function Init()
    {
        $this->Viewer_AppendStyle(Plugin::GetTemplatePath(__CLASS__) . "css/style.css"); // Добавление своего CSS
    }
}

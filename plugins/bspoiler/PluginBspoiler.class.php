<?php
/*-------------------------------------------------------
*
*	Plugin Spoiler
*	Shpinev Konstantin
*	Contact e-mail: thedoublekey@gmail.com
*
*---------------------------------------------------------
*
*	Spoiler :: Plugin
*	Modified by fedorov mich © 2014
*	[ LS :: 1.0.3 | Habra Style ]
*
*/

if (!class_exists('Plugin')) {
    die(__FILE__ . ' : Hacking attemp!');
}


class PluginBspoiler extends Plugin
{
    protected $sTemplatesUrl = "";

    /**
     * Делегаты
     *
     * @var unknown_type
     */
    protected $aInherits = array(
        'module' => array('ModuleText' => '_ModuleBspoiler')
    );

    // Активация плагина
    public function Activate()
    {
        return TRUE;
    }

    // Деактивация плагина
    public function Deactivate()
    {
        return TRUE;
    }

    /**
     * Инициализация плагина
     *
     * (non-PHPdoc)
     * @see engine/classes/Plugin#Init()
     */
    public function Init()
    {
        $sTemplatesUrl = Plugin::GetTemplatePath('PluginBspoiler');

        // Добавление своего CSS и JS
        $this->Viewer_AppendStyle($sTemplatesUrl . "/css/style.css");
        $this->Viewer_AppendScript($sTemplatesUrl . "/js/bspoiler.js");
    }

}

<?php

/* -------------------------------------------------------
 *
 *   LiveStreet (v1.x)
 *   Plugin Show hide sidebar (v.0.2)
 *   Copyright © 2013 Bishovec Nikolay
 *
 * --------------------------------------------------------
 *
 *   Plugin Page: http://netlanc.net
 *   Contact e-mail: netlanc@yandex.ru
 *
 ---------------------------------------------------------
 */

if (!class_exists('Plugin')) {
    die(__FILE__ . ' : Hacking attemp!');
}


class PluginShowhidesidebar extends Plugin
{
    public $aDelegates = array(
        'template' => array(
            'toolbar_showhidesidebar.tpl' => '_toolbar_showhidesidebar.tpl',
        ),
    );

    public function Activate()
    {
        return true;
    }

    public function Init()
    {
        $this->Viewer_AppendStyle(Plugin::GetTemplateWebPath('showhidesidebar') . 'css/style.css');

        /**
         * Раньше это подключалось так:
         * в plugins/showhidesidebar/templates/skin/default/toolbar_showhidesidebar.tpl
         *
         * <script type='text/javascript' src='{$oConfig->get('path.root.web')}/plugins/showhidesidebar/js/jquery-ui.min.v1.8.24.js'></script>
         *
         * Но тут подключается огромная версия на 200 килобайт минифицированная. JQuery UI 1.8.24
         * Попытки подключить другую версию пока неуспешны :(
         *
         * https://jqueryui.com/download/
         */
        $this->Viewer_AppendScript(Plugin::GetTemplatePath(__CLASS__) . 'js/jquery-ui.min.v1.8.24.js'); // работает
    }

}


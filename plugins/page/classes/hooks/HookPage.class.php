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
 * Регистрация хука для вывода меню страниц
 *
 */
class PluginPage_HookPage extends Hook
{
    const ConfigKey = 'page';
    const HooksArray = [
        'template_main_menu_item'  =>  'Menu',
    ];

    public function RegisterHook()
    {
        $plugin_config_key = $this::ConfigKey;
        foreach ($this::HooksArray as $hook => $callback) {
            $this->AddHook(
                $hook,
                $callback,
                __CLASS__,
                Config::Get("plugin.{$plugin_config_key}.hook_priority.{$hook}") ?? 1
            );
        }
    }

    public function Menu()
    {
        $aPages = $this->PluginPage_Page_GetPages(array('pid' => null, 'main' => 1, 'active' => 1));


        $this->Viewer_Assign('aPagesMain', $aPages);
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'main_menu.tpl');
    }
}

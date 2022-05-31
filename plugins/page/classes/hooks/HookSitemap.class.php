<?php

/* -------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
* --------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
* ---------------------------------------------------------
*/

class PluginPage_HookSitemap extends Hook
{
    const ConfigKey = 'page';
    const HooksArray = [
        'sitemap_index_counters'  =>  'SitemapIndex',
    ];

    /**
     * Цепляем обработчики на хуки
     *
     * @return void
     */
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

    /**
     * Добавляем ссылку на Sitemap страниц в Sitemap Index
     *
     * @param array $aCounters
     * @return void
     */
    public function SitemapIndex($aCounters)
    {
        $aCounters['pages'] = ceil($this->PluginSitemap_Page_GetActivePagesCount() / Config::Get('plugin.sitemap.objects_per_page'));
    }

}
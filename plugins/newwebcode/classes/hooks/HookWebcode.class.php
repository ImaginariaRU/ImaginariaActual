<?php
/**
 * New Webcode - плагин для простого добавления счетчиков
 *
 * Версия:    1.0.2
 * Автор:    Александр Вереник
 * Профиль:    http://livestreet.ru/profile/Wasja/
 * GitHub:    https://github.com/wasja1982/livestreet_newsocialcomments
 *
 * Основан на плагине "Webcode" (автор: Артем Сошников) - https://catalog.livestreetcms.com/addon/view/171/
 *
 * Плагин использует хранилище ConfigEngine (http://livestreetcms.com/addons/view/380/) от PSNet (http://psnet.lookformp3.net/).
 *
 **/

class PluginNewwebcode_HookWebcode extends Hook
{
    const ConfigKey = 'newwebcode';
    const HooksArray = [
        'template_html_head_end'  =>  'html_head_end',
        'template_body_begin'  =>  'body_begin',
        'template_body_end'  =>  'body_end',
        'template_main_menu_item'  =>  'menu_admin',
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

    public function menu_admin()
    {
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'menu_admin.tpl');
    }

    public function __call($name, $arguments)
    {
        try {
            $aHooks = Config::Get('plugin.newwebcode.hooks');
            if (in_array($name, $aHooks)) {
                $oCode = new PluginNewwebcode_ModuleWebcode_EntityCode(array('name' => $name));
                return $oCode->getCode();
            } else {
                return parent::__call($name, $arguments);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        return;
    }
}
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

class PluginShowhidesidebar_HookShowhidesidebar extends Hook
{
    const ConfigKey = 'showhidesidebar';
    const HooksArray = [
        'template_body_begin'  =>  'BodyBegin', // 201
        'template_wrapper_class'    =>  'WrapperClass',  //201
        'template_copyright'    =>  'Copyright'
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

    public function WrapperClass()
    {
        if (in_array(Router::GetAction(), Config::Get('plugin.showhidesidebar.action_off'))) {
            return;
        }
        $sClass = '';
        if (!empty($_COOKIE['shs'])) {
            $sClass = 'no-sidebar';
        }
        return $sClass;
    }

    public function BodyBegin()
    {
        if (in_array(Router::GetAction(), Config::Get('plugin.showhidesidebar.action_off'))) {
            return;
        }
        return $this->Viewer_Fetch(Plugin::GetTemplatePath('showhidesidebar') . '/body_begin.tpl');
    }

    public function Copyright()
    {
        return;
    }

}


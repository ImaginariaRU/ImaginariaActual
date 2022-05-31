<?php

class PluginSHistory_HookSHistory extends Hook
{
    const ConfigKey = 'shistory';
    const HooksArray = [
        'template_menu_settings_settings_item'  =>  'profile',
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

    public function profile()
    {
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'inject_profile.tpl');
    }

}

<?php

class PluginUsername_HookUsername extends Hook
{
    const ConfigKey = 'username';
    const HooksArray = [
        'template_html_head_end'  =>  'UserName',
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

    public function UserName()
    {
        $sSelector = 'body *';
        if (Config::Get('plugin.username.article')) {
            $sSelector = '.topic-content';
        }
        if ($this->User_IsAuthorization()) {
            $this->Viewer_Assign('sSelector', $sSelector);
            return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'username.tpl');
        }
    }
}


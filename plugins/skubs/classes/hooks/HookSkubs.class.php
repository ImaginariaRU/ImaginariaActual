<?php

class PluginSkubs_HookSkubs extends Hook
{
    const ConfigKey = 'skubs';
    const HooksArray = [
        'template_profile_whois_activity_item'  =>  'InsertSkubStat',
    ];

    /*
     * Регистрация событий на хуки
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

    public function InsertSkubStat($aData)
    {
        $oUser = $aData['oUserProfile'];
        $this->Viewer_Assign('aSkubBlogs', $this->PluginSkubs_ModuleSkubs_GetSkubStat($oUser->getId(), Config::Get('plugin.skubs.blogs_limit')));
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'skubs.tpl');
    }
}

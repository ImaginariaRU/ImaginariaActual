<?php
/*
	Configengine plugin
	(P) PSNet, 2008 - 2013
	http://psnet.lookformp3.net/
	http://livestreet.ru/profile/PSNet/
	https://catalog.livestreetcms.com/profile/PSNet/
	http://livestreetguide.com/developer/PSNet/
*/

class PluginConfigengine_HookConfigengine extends Hook
{
    const ConfigKey = 'configengine';
    const HooksArray = [
        'lang_init_start'       =>  'LangInitStart',
        'engine_init_complete'  =>  'EngineInitComplete'
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

    // ---

    public function LangInitStart()
    {
        // load configs
        $this->PluginConfigengine_Config_AutoLoadConfigs();
    }

    // ---

    public function EngineInitComplete()
    {
        // add CSS and JS
        $sTemplateWebPath = Plugin::GetTemplateWebPath(__CLASS__);
        $this->Viewer_AppendStyle($sTemplateWebPath . 'css/style.css');
        $this->Viewer_AppendScript($sTemplateWebPath . 'js/init.js');
    }

}

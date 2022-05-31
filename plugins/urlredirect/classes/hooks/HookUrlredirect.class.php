<?php
/*
  Urlredirect plugin
  (P) PSNet, 2008 - 2012
  http://psnet.lookformp3.net/
  http://livestreet.ru/profile/PSNet/
  http://livestreetcms.com/profile/PSNet/
*/

class PluginUrlredirect_HookUrlredirect extends Hook
{
    const ConfigKey = 'urlredirect';
    const HooksArray = [
        'init_action'  =>  'AddStylesAndJS',
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

    public function AddStylesAndJS()
    {
        if (!Config::Get('plugin.urlredirect.Highlight_External_Links')) return false;

        $sTemplateWebPath = Plugin::GetTemplateWebPath(__CLASS__);
        $this->Viewer_AppendStyle($sTemplateWebPath . 'css/style.css');
    }

}

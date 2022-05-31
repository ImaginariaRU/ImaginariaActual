<?php
/*
  Fontsize plugin
  (P) PSNet, 2008 - 2013
  http://psnet.lookformp3.net/
  http://livestreet.ru/profile/PSNet/
  http://livestreetcms.com/profile/PSNet/
  http://livestreetguide.com/developer/PSNet/
*/

class PluginFontsize_HookFontsize extends Hook
{
    const ConfigKey = 'fontsize';
    const HooksArray = [
        'engine_init_complete'          =>  'EngineInitComplete',
        'template_topic_content_begin'  =>  'TopicContent',
        'template_topic_content_end'    =>  'TopicContent',
        'template_footer_end'           =>  'FooterEnd',
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

    public function EngineInitComplete()
    {
        // add CSS and JS
        $sTemplateWebPath = Plugin::GetTemplateWebPath(__CLASS__);
        $this->Viewer_AppendStyle($sTemplateWebPath . 'css/style.css');
        $this->Viewer_AppendScript($sTemplateWebPath . 'js/init.js');
    }

    // ---

    public function TopicContent()
    {
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'topic_content.tpl');
    }

    // ---

    public function FooterEnd()
    {
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'footer_end.tpl');
    }

}

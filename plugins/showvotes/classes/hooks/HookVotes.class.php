<?php

/**
 * ShowVotes
 * by Stanislav Nevolin, stanislav@nevolin.info
 * + Karel Wintersky
 */
class PluginShowvotes_HookVotes extends Hook
{
    const ConfigKey = 'showvotes';
    const HooksArray = [
        'template_topic_show_info'  =>  'VotesShow',
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

    public function VotesShow($aParams)
    {
        $oTopic = $aParams['topic'];
        $aVotes = $this->PluginShowvotes_ModuleVote_GetTopicVoters($oTopic);
        if ($aVotes) {
            $this->Viewer_Assign('aVotes', $aVotes);
            $this->Viewer_Assign('oTopic', $oTopic);
            return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'topic_voters.tpl');
        }
    }
}

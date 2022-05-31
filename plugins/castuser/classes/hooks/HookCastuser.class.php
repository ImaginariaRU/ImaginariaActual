<?php

class PluginCastuser_HookCastuser extends Hook
{
    const ConfigKey = 'castuser';
    const HooksArray = [
        'topic_add_after'   =>  'NotifyCastedUserTopic',
        'topic_edit_after'  =>  'NotifyCastedUserTopic',
        'comment_add_after' =>  'NotifyCastedUserComment'
    ];

    /**
     * Register hooks
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

    public function NotifyCastedUserTopic($aParams)
    {        
    	$oTopic = $aParams['oTopic'];
    	if ($oTopic->getPublish()==1 ){    		
    		$oTopic->setBlog($this->Blog_GetBlogById($oTopic->getBlogId()));
    		$this->PluginCastuser_Cast_sendCastNotify('topic',$oTopic,null,$oTopic->getTextSource());
    	}
    }

    
    public function NotifyCastedUserComment($aParams)
    {        
    	$oTarget = $aParams['oCommentNew'];
    	$oParrentTarget = $aParams['oTopic'];
    	$this->PluginCastuser_Cast_sendCastNotify('comment',$oTarget,$oParrentTarget,$oTarget->getText());
    }    
    
}

<?php

/********************************************
 * Author: Vladimir Linkevich
 * e-mail: Vladimir.Linkevich@gmail.com
 * since 2011-02-25
 ********************************************/



class PluginAutocut_HookAutocut extends Hook
{
    const ConfigKey = 'autocut';
    const HooksArray = [
        'topic_edit_before' =>  'CheckTopicFieldsCut',
        'topic_add_before'  =>  'CheckTopicFieldsCut'
        /*
        'check_photoset_fields' =>  'CheckTopicFieldsCut'
         */
    ];

    /**
     *
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

    /**
     * @param $var
     * @return mixed
     */
    public function CheckTopicFieldsCut($var)
    {

        //Disables AutoCut for Admin
        $oUser = $this->User_GetUserCurrent();
        if ($oUser->isAdministrator() && !Config::Get('plugin.autocut.cut_admin_topics')) {
            return $var;
        }
        $oTopic = $var['oTopic'];

        // check if we are posting to personal blog and if we need to cut personal topics
        if (getRequest('blog_id') == 0 && !Config::Get('plugin.autocut.cut_personal_topics')) {
            return $var;
        }

        /**
         * Получаем и устанавливаем разрезанный текст по тегу <cut>
         */
        list($sTextShort, $sTextNew, $sTextCut) = $this->Text_Cut($this->PluginAutocut_Autocut_CutAdd($oTopic->getTextSource()));

        $oTopic->setCutText($sTextCut);
        $oTopic->setText($this->Text_Parser($sTextNew));
        $oTopic->setTextShort($this->Text_Parser($sTextShort));
        $var['oTopic'] = $oTopic;
        return $var;
    }
// #End of class
}

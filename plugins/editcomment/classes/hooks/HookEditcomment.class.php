<?php
/*-------------------------------------------------------
*
*   kEditComment.
*   Copyright © 2012 Alexei Lukin
*
*--------------------------------------------------------
*
*   Official site: http://kerbystudio.ru
*   Contact e-mail: kerby@kerbystudio.ru
*
---------------------------------------------------------
*/

class PluginEditcomment_HookEditcomment extends Hook
{
    const ConfigKey = 'editcomment';
    const HooksArray = [
        'template_comment_action'   =>  'InjectEditLink',
        'template_comment_tree_end' =>  'InjectEditButtonCode',
        'template_topic_show_end'   =>  'InjectSponsorLink'
    ];

    protected $oUserCurrent;

    public function RegisterHook()
    {
        $this->oUserCurrent = $this->User_GetUserCurrent();
        if (!$this->oUserCurrent)
            return;

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

    public function InjectEditLink($aParam)
    {
        $this->oUserCurrent = $this->User_GetUserCurrent();

        if (Config::Get('plugin.editcomment.template_check_edit_rights'))
            if ($this->ACL_UserCanEditComment($this->oUserCurrent, $aParam['comment'], Config::Get('plugin.editcomment.template_check_edit_rights')) !== true)
                return;

        $this->Viewer_Assign('iCommentId', $aParam['comment']->getId());
        return $this->Viewer_Fetch($this->PluginEditcomment_Editcomment_GetTemplateFilePath(__CLASS__, 'inject_editcomment_command.tpl'));
    }

    public function InjectEditButtonCode($aParam)
    {
        $this->Viewer_Assign('iTargetId', $aParam['iTargetId']);
        $this->Viewer_Assign('sTargetType', $aParam['sTargetType']);
        $this->Viewer_Assign('oUserCurrent', $this->User_GetUserCurrent());
        $sText = $this->Viewer_Fetch($this->PluginEditcomment_Editcomment_GetTemplateFilePath(__CLASS__, 'inject_edit_button_code.tpl'));
        return $sText . $this->Viewer_Fetch($this->PluginEditcomment_Editcomment_GetTemplateFilePath(__CLASS__, 'window_history.tpl'));
    }

    public function InjectSponsorLink($aParam)
    {
        if (Config::Get('plugin.editcomment.donated')) return "";

        return $this->Viewer_Fetch($this->PluginEditcomment_Editcomment_GetTemplateFilePath(__CLASS__, 'inject_sponsor_link.tpl'));
    }
}

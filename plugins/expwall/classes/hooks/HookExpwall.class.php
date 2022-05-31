<?php

/* -------------------------------------------------------
 *
 *   LiveStreet (v1.x)
 *   Plugin Expanded wall (v.0.3)
 *   Copyright © 2011 Bishovec Nikolay
 *
 * --------------------------------------------------------
 *
 *   Plugin Page: http://netlanc.net
 *   Contact e-mail: netlanc@yandex.ru
 *
  ---------------------------------------------------------
 */

class PluginExpwall_HookExpwall extends Hook
{
    const ConfigKey = 'expwall';
    const HooksArray = [
        'template_main_menu_item'       =>  'MenuMain',
        'template_profile_whois_end'    =>  'ProfileWhois'
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

    public function ProfileWhois($aVars)
    {
        $oUserProfile = $aVars['oUserProfile'];
        $aWall = $this->Wall_GetWall(array('wall_user_id' => $oUserProfile->getId(), 'pid' => null), array('id' => 'desc'), 1, Config::Get('module.wall.per_page'));
        $this->Viewer_Assign('aWall', $aWall['collection']);
        $this->Viewer_Assign('iCountWall', $aWall['count']);

        return $this->Viewer_Fetch(Plugin::GetTemplatePath('expwall') . 'profile_whois.tpl');
    }

    public function MenuMain()
    {
        return $this->Viewer_Fetch(Plugin::GetTemplatePath('expwall') . 'main_menu.tpl');
    }

}


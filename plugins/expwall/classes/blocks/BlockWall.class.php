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

class PluginExpwall_BlockWall extends Block
{
    public function Exec()
    {
        $aResult = $this->Wall_GetWall(array('pid' => null), array(), 1, Config::Get('module.wall.per_page'));
        $aWall = $aResult['collection'];
        $this->Viewer_Assign('aWall', $aWall);
    }
}


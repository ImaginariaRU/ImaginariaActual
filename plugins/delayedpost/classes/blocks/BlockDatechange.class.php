<?php
/*
  Delayed post plugin
  (P) Rafrica.net Studio, 2010 - 2012
  http://we.rafrica.net/
*/

class PluginDelayedpost_BlockDatechange extends Block
{

    public function Exec()
    {
        $this->Viewer_Assign('aYears', Config::Get('plugin.delayedpost.PossibleYears'));
        $this->Viewer_Assign('aMonths', Config::Get('plugin.delayedpost.PossibleMonths'));
        $this->Viewer_Assign('aDays', Config::Get('plugin.delayedpost.PossibleDays'));
        $this->Viewer_Assign('aHours', Config::Get('plugin.delayedpost.TimeHours'));
        $this->Viewer_Assign('aMinutes', Config::Get('plugin.delayedpost.TimeMinutes'));
    }

}

<?php
/*
  Simplesearch plugin
  (P) PSNet, 2008 - 2013
  http://psnet.lookformp3.net/
  http://livestreet.ru/profile/PSNet/
  http://livestreetcms.com/profile/PSNet/
  http://livestreetguide.com/developer/PSNet/
*/

if (!class_exists('Plugin')) {
    die ('Kokobubble!');
}

class PluginSimplesearch extends Plugin
{

    protected $aInherits = array(
        'action' => array('ActionSearch'),
        'module' => array('ModuleTopic_MapperTopic')
    );

    // ---

    public function Activate()
    {
        return true;
    }

    // ---

    public function Init()
    {
    }

}

?>
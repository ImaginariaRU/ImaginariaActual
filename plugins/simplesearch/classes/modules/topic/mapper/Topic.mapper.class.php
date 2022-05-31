<?php
/*
  Simplesearch plugin
  (P) PSNet, 2008 - 2013
  http://psnet.lookformp3.net/
  http://livestreet.ru/profile/PSNet/
  http://livestreetcms.com/profile/PSNet/
  http://livestreetguide.com/developer/PSNet/
*/

class PluginSimplesearch_ModuleTopic_MapperTopic extends PluginSimplesearch_Inherit_ModuleTopic_MapperTopic
{

    public function GetTopicTagsByLike($sTag, $iLimit)
    {
        return parent::GetTopicTagsByLike('%' . $sTag, $iLimit);
    }

}

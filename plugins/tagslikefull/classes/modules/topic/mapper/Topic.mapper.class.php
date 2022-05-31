<?php
/*
  Tagslikefull plugin
  (P) PSNet, 2008 - 2012
  http://psnet.lookformp3.net/
  http://livestreet.ru/profile/PSNet/
  http://livestreetcms.com/profile/PSNet/
*/

class PluginTagslikefull_ModuleTopic_MapperTopic extends PluginTagslikefull_Inherit_ModuleTopic_MapperTopic
{

    public function GetTopicTagsByLike($sTag, $iLimit)
    {
        return parent::GetTopicTagsByLike('%' . $sTag, $iLimit);
    }

}

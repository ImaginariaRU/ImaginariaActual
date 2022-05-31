<?php
/*
  Delayed post plugin
  (P) Rafrica.net Studio, 2010 - 2012
  http://we.rafrica.net/
*/

class PluginDelayedpost_ModuleDelayedpost extends Module
{

    public function Init()
    {
    }

    public function GetDelayedTopics($iUserId)
    {
        $aFilter = array(
            'topic_publish' => 1,
            'user_id' => $iUserId,
            'blog_type' => array('open', 'personal', 'close'),
            'delayed' => true
        );

        return $this->Topic_GetTopicsByFilter($aFilter, 1, 100);
    }

}

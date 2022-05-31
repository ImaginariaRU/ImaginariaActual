<?php
/*
  Delayed post plugin
  (P) Rafrica.net Studio, 2010 - 2012
  http://we.rafrica.net/
*/

class PluginDelayedpost_ModuleTopic extends PluginDelayedpost_Inherit_ModuleTopic
{

    /**
     * @param $oBlog
     * @param $oTopic
     * @param $oUserTopic
     * @return bool
     */
    public function SendNotifyTopicNew($oBlog, $oTopic, $oUserTopic)
    {
        $sDateNow = date("Y-m-d H:i:s");
        $sTopicAddDate = $oTopic->getDateAdd();

        if (strtotime($sTopicAddDate) > strtotime($sDateNow)) return false;

        $aBlogUsersResult = $this->Blog_GetBlogUsersByBlogId($oBlog->getId(), null, null); // нужно постранично пробегаться по всем
        $aBlogUsers = $aBlogUsersResult['collection'];
        foreach ($aBlogUsers as $oBlogUser) {
            if ($oBlogUser->getUserId() == $oUserTopic->getId()) {
                continue;
            }
            $this->Notify_SendTopicNewToSubscribeBlog($oBlogUser->getUser(), $oTopic, $oBlog, $oUserTopic);
        }
        //отправляем создателю блога
        if ($oBlog->getOwnerId() != $oUserTopic->getId()) {
            $this->Notify_SendTopicNewToSubscribeBlog($oBlog->getOwner(), $oTopic, $oBlog, $oUserTopic);
        }
    }

    /**
     * @param $aFilter
     * @param int $iPage
     * @param int $iPerPage
     * @param array $aAllowData
     * @return array
     */

    public function GetTopicsByFilter($aFilter, $iPage = 0, $iPerPage = 0, $aAllowData = array('user' => array(), 'blog' => array('owner' => array(), 'relation_user'), 'vote', 'favourite', 'comment_new'))
    {
        //@todo: грязная магия: iCount возвращается по ссылке, используется дальше, но не объявляется нигде прежде
        $iCount = null;

        $s = serialize($aFilter);
        $iSecondsToNextDelayedTopicDatetime = $this->GetSecondsToNextDelayedTopic();

        if (false === ($data = $this->Cache_Get("topic_filter_{$s}_{$iPage}_{$iPerPage}"))) {

            $data = ($iPage * $iPerPage != 0)
                ? array(
                    'collection' => $this->oMapperTopic->GetTopics($aFilter, $iCount, $iPage, $iPerPage),
                    'count' => $iCount
                )
                : array(
                    'collection' => $this->oMapperTopic->GetAllTopics($aFilter),
                    'count' => $this->GetCountTopicsByFilter($aFilter)
                );
            $this->Cache_Set($data, "topic_filter_{$s}_{$iPage}_{$iPerPage}", array('topic_update', 'topic_new'), $iSecondsToNextDelayedTopicDatetime);
        }
        $data['collection'] = $this->GetTopicsAdditionalData($data['collection'], $aAllowData);
        return $data;
    }

    /**
     * Переделаем функцию, чтобы она устанавливала время протухания кэша на датувремя
     * публикации следующего отложенного топика
     *
     * @return false|float|int
     */
    public function GetSecondsToNextDelayedTopic()
    {
        $aResult = $this->oMapperTopic->GetNextDelayedTopicDate();
        if ($aResult) {
            $sDatetime = $aResult['topic_date_add'];
            $iSecondsTo = strtotime($sDatetime) - time() + 1;
            return $iSecondsTo;
        } else return 60 * 60 * 24 * 1;
    }

    /**
     * @param $aFilter
     * @return mixed
     */
    public function GetCountTopicsByFilter($aFilter)
    {
        $iSecondsToNextDelayedTopicDatetime = $this->GetSecondsToNextDelayedTopic();
        $s = serialize($aFilter);
        if (false === ($data = $this->Cache_Get("topic_count_{$s}"))) {
            $data = $this->oMapperTopic->GetCountTopics($aFilter);
            $this->Cache_Set($data, "topic_count_{$s}", array('topic_update', 'topic_new'), $iSecondsToNextDelayedTopicDatetime);
        }
        return $data;
    }

}
